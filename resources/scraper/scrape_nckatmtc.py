import sys
import requests
from bs4 import BeautifulSoup
import json
from PIL import Image
from io import BytesIO
import pytesseract
import re
import mysql.connector

# 👇 Point to your installed Tesseract
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'


def extract_size_chart_table(image_url):
    try:
        print(f"📥 Downloading size chart image: {image_url}")
        response = requests.get(image_url)
        img = Image.open(BytesIO(response.content))

        # OCR with layout mode (table-ish)
        custom_config = r'--psm 6'
        raw_text = pytesseract.image_to_string(img, config=custom_config)

        print("\n🔍 RAW OCR TEXT ↓↓↓↓↓")
        print(raw_text)
        print("🔚 RAW OCR END\n")

        lines = [line.strip() for line in raw_text.split('\n') if line.strip()]
        data_rows = []

        for line in lines:
            parts = line.split()
            if len(parts) == 4:
                size, width, length, sleeve = parts
                try:
                    data_rows.append({
                        'size': size,
                        'width': float(width),
                        'length': float(length),
                        'sleeve_length': float(sleeve)
                    })
                except ValueError:
                    continue  # Skip rows that aren't parseable

        if not data_rows:
            print("⚠️ OCR succeeded, but no valid rows parsed.")
        else:
            print("✅ Parsed size chart:")
            print(json.dumps(data_rows, indent=2))

        return data_rows if data_rows else None

    except Exception as e:
        print(f"❌ OCR error: {e}")
        return f"OCR parsing failed: {str(e)}"


# 🔍 Scrape the product page
url = sys.argv[1]
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

# ✅ Front Image (Artboard)
images = []
for img in soup.find_all('img'):
    src = img.get('src', '')
    if 'Artboard' in src and 'nckatmtc.com' in src:
        if src.startswith('//'):
            src = 'https:' + src
        images.append(src)
        break

# ✅ Product Name
name_tag = soup.select_one('div.product__title h1')
product_name = name_tag.text.strip() if name_tag else None

# ✅ Price
price_tag = soup.select_one('span.price-item')
price = price_tag.text.strip().replace('₱', '').replace(',', '').replace(' PHP', '') if price_tag else None

# ✅ Description
desc_tag = soup.select_one('div.product__description')
description = desc_tag.text.strip() if desc_tag else None

# ✅ Size Chart Image and Data
size_chart = None
size_chart_data = None

for img in soup.select('img'):
    src = img.get('src', '')
    alt = img.get('alt', '').lower()
    if 'size' in alt or 'chart' in src.lower():
        size_chart = 'https:' + src if src.startswith('//') else src
        size_chart_data = extract_size_chart_table(size_chart)
        break

# ✅ Final scraped result
result = {
    "product_url": url,
    "front_image": images[0] if images else None,
    "product_name": product_name,
    "price": price,
    "description": description,
    "size_chart_image": size_chart,
    "size_chart_data": size_chart_data
}

# Output to terminal for debug
print("\n📦 Final Scraped Data:")
print(json.dumps(result, indent=2))


# ✅ Save to MySQL
def save_to_mysql(data):
    try:
        connection = mysql.connector.connect(
            host='127.0.0.1',
            user='root',         # ← Change this
            password='',     # ← Change this
            database='example_app2'  # ← Change this
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


# Save to DB
save_to_mysql(result)
