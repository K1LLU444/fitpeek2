import sys
import requests
from bs4 import BeautifulSoup
import json
import pytesseract
import re
import mysql.connector
from rembg import remove
import os
import warnings

# Suppress paddle/pip-related warnings
warnings.filterwarnings("ignore")

# Suppress stdout logs from imported packages
sys.stderr = open(os.devnull, 'w')

# 🚀 Modular OCR Routing
from ocr_parser import extract_flat_ocr_lines
from ocr_router import route_ocr_to_parser

pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

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
media_gallery = soup.select_one('media-gallery')

if media_gallery:
    nested_imgs = media_gallery.select('img')
    for img in nested_imgs:
        src = img.get('src', '')
        if ('.jpg' in src or '.png' in src or 'Artboard' in src) and 'nckatmtc.com' in src:
            if src.startswith('//'):
                src = 'https:' + src
            images.append(src)

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
    size_chart_data = route_ocr_to_parser(flat_lines)


# Final data package
result = {
    "product_url": url,
    "product_images": images,
    "product_name": product_name,
    "price": price,
    "description": description,
    "size_chart_image": size_chart,
    "size_chart_data": size_chart_data
}


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
            (product_url, product_name, product_images, size_chart_image, size_chart_data, price, description, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                product_name = VALUES(product_name),
                product_images = VALUES(product_images),
                size_chart_image = VALUES(size_chart_image),
                size_chart_data = VALUES(size_chart_data),
                price = VALUES(price),
                description = VALUES(description),
                updated_at = NOW()
        """

        cursor.execute(query, (
            data.get("product_url"),
            data.get("product_name"),
            json.dumps(data.get("product_images")),
            data.get("size_chart_image"),
            json.dumps(data.get("size_chart_data")),
            data.get("price"),
            data.get("description")
        ))

        connection.commit()

    except mysql.connector.Error as err:
        print(f"❌ DB Error: {err}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

print(json.dumps(result, indent=2))

save_to_mysql(result)
