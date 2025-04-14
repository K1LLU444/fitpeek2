import sys
import requests
from bs4 import BeautifulSoup
import json
from PIL import Image, ImageEnhance, ImageFilter
from io import BytesIO
import pytesseract
import re
import mysql.connector
from rembg import remove
import os

# 🚀 Modular OCR Routing
from ocr_parser import extract_flat_ocr_lines
from ocr_router import route_ocr_to_parser

pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'


def slugify(text):
    text = text.lower()
    text = re.sub(r'[^a-z0-9]+', '-', text)
    return text.strip('-')


def fallback_extract_size_chart_table(image_url):
    try:
        response = requests.get(image_url)
        img = Image.open(BytesIO(response.content)).convert("L")
        img = img.filter(ImageFilter.SHARPEN)
        enhancer = ImageEnhance.Contrast(img)
        img = enhancer.enhance(2.0)

        config = r'--psm 6'
        raw_text = pytesseract.image_to_string(img, config=config)
        print("\n🔍 [Fallback OCR] RAW TEXT ↓↓↓↓\n", raw_text)

        lines = [line.strip() for line in raw_text.split('\n') if line.strip()]
        data = []
        for line in lines:
            parts = line.split()
            if len(parts) >= 4:
                try:
                    size = parts[0].upper()
                    width = float(parts[1].replace('"', '').replace('”', ''))
                    length = float(parts[2].replace('"', '').replace('”', ''))
                    sleeve = float(parts[3].replace('"', '').replace('”', ''))

                    if 15 <= width <= 30 and 20 <= length <= 35 and 5 <= sleeve <= 15:
                        data.append({
                            'size': size,
                            'width': round(width, 1),
                            'length': round(length, 1),
                            'sleeve_length': round(sleeve, 1)
                        })
                except:
                    continue
        return data if data else None
    except Exception as e:
        print(f"[Fallback OCR Error] {e}")
        return None


def remove_background_from_url(image_url, product_name):
    try:
        response = requests.get(image_url)
        input_image = Image.open(BytesIO(response.content)).convert("RGBA")
        output_image = remove(input_image)

        slug = slugify(product_name)
        filename = f"cleaned-{slug}.png"
        output_path = os.path.join("public", "products", filename)

        os.makedirs(os.path.dirname(output_path), exist_ok=True)
        output_image.save(output_path)

        return f"products/{filename}"
    except Exception as e:
        print(f"❌ Background removal failed: {e}")
        return image_url


# Scrape the product page
url = sys.argv[1]
try:
    response = requests.get(url, timeout=10)
    if response.status_code != 200:
        print(f"Invalid URL or product page not found (status code: {response.status_code})")
        sys.exit(1)
except requests.exceptions.RequestException as e:
    print(f"Failed to connect to the URL: {e}")
    sys.exit(1)

soup = BeautifulSoup(response.text, 'html.parser')

# Front Image (Artboard)
images = []
main_product = soup.select_one('product-info')

if main_product:
    nested_imgs = main_product.select('img')
    for img in nested_imgs:
        src = img.get('src', '')
        if ('.jpg' in src or '.png' in src) and 'nckatmtc.com' in src:
            if src.startswith('//'):
                src = 'https:' + src
            images.append(src)
            break



# Product Name
name_tag = soup.select_one('div.product__title h1')
product_name = name_tag.text.strip() if name_tag else None

# Price
price_tag = soup.select_one('span.price-item')
price = price_tag.text.strip().replace('₱', '').replace(',', '').replace(' PHP', '') if price_tag else None

# Description
desc_tag = soup.select_one('div.product__description')
description = desc_tag.text.strip() if desc_tag else None

# Size Chart Detection
size_chart = None
size_chart_data = None

for img in soup.select('img'):
    src = img.get('src', '')
    alt = img.get('alt', '').lower()
    if 'size' in alt or 'chart' in src.lower():
        size_chart = 'https:' + src if src.startswith('//') else src
        break

if not size_chart:
    desc_div = soup.select_one('div.product__description')
    if desc_div:
        fallback_img = desc_div.find('img')
        if fallback_img:
            src = fallback_img.get('src', '')
            size_chart = 'https:' + src if src.startswith('//') else src

# ✅ OCR → flat lines → router
if size_chart:
    flat_lines = extract_flat_ocr_lines(size_chart)
    print("🧐 FLAT TEXT:", flat_lines)
    size_chart_data = route_ocr_to_parser(flat_lines)

    # Optional fallback (commented out)
    # if not size_chart_data:
    #     size_chart_data = fallback_extract_size_chart_table(size_chart)

    print("\n🧠 [PaddleOCR Extracted Data]")
    print(json.dumps(size_chart_data, indent=2))


# Remove background
cleaned_image = remove_background_from_url(images[0], product_name) if images and product_name else None

# Final data package
result = {
    "product_url": url,
    "front_image": cleaned_image,
    "product_name": product_name,
    "price": price,
    "description": description,
    "size_chart_image": size_chart,
    "size_chart_data": size_chart_data
}

print("\n📦 Final Scraped Data:")
print(json.dumps(result, indent=2))


def save_to_mysql(data):
    try:
        connection = mysql.connector.connect(
            host='127.0.0.1',
            user='root',
            password='',
            database='example_app2'
        )
        cursor = connection.cursor()

        query = """
            INSERT INTO products
            (product_url, product_name, front_image, size_chart_image, size_chart_data, price, description, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                product_name = VALUES(product_name),
                front_image = VALUES(front_image),
                size_chart_image = VALUES(size_chart_image),
                size_chart_data = VALUES(size_chart_data),
                price = VALUES(price),
                description = VALUES(description),
                updated_at = NOW()
        """
        print("✅ Attempting to insert with query:")
        print(query)
        print("✅ Parameters:")
        print(data.get("product_url"), data.get("product_name"), data.get("front_image"), ...)

        cursor.execute(query, (
            data.get("product_url"),
            data.get("product_name"),
            data.get("front_image"),
            data.get("size_chart_image"),
            json.dumps(data.get("size_chart_data")),
            data.get("price"),
            data.get("description")
        ))

        connection.commit()
        print("✅ Saved to database.")

    except mysql.connector.Error as err:
        print(f"❌ DB Error: {err}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

print("\n📝 Ready to save to DB with values:")
print(json.dumps(result, indent=2))

save_to_mysql(result)
