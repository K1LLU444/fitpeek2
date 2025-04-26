import requests
from bs4 import BeautifulSoup
import pytesseract
import warnings
import os
import sys

# 🚫 Silence noisy modules during import
original_stdout = sys.stdout
original_stderr = sys.stderr
sys.stdout = open(os.devnull, 'w')
sys.stderr = open(os.devnull, 'w')

from ocr_parser import extract_flat_ocr_lines
from ocr_router import route_ocr_to_parser

sys.stdout = original_stdout
sys.stderr = original_stderr

# ✅ Setup
warnings.filterwarnings("ignore")
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'


def scrape(url):
    try:
        response = requests.get(url, timeout=10)
        if response.status_code != 200:
            return {"error": f"Invalid URL or product page not found (status {response.status_code})"}
    except requests.exceptions.RequestException as e:
        return {"error": f"Failed to connect to URL: {e}"}

    soup = BeautifulSoup(response.text, 'html.parser')
    # 🖼️ Product Images
    images = []

    # Find all product image wrappers
    image_wrappers = soup.select('div.product-single__photos div.product-single__image-wrapper img')

    for img_tag in image_wrappers:
        # First priority: use data-zoom (larger original image)
        src = img_tag.get('data-zoom') or img_tag.get('src') or ''
        if src:
            if src.startswith('//'):
                src = 'https:' + src
            elif src.startswith('/'):
                src = 'https://hso.com.ph' + src
            images.append(src)

    # 🛍️ Product Name
    name_tag = soup.select_one('h1[itemprop="name"]')
    product_name = name_tag.text.strip() if name_tag else None

    # 💰 Product Price
    price_tag = soup.select_one('span#ProductPrice')
    price = price_tag.text.strip().replace('₱', '').replace(',', '').strip() if price_tag else None

    # 📄 Product Description and Size Chart
    desc_div = soup.select_one('div.product-description.rte')

    description = None
    size_chart = None
    size_chart_data = None

    if desc_div:
        # 📄 Get all text as description
        description = desc_div.get_text(separator="\n").strip()

        # 🖼️ Find the first image inside (likely the size chart)
        img_tag = desc_div.find('img')
        if img_tag:
            src = img_tag.get('src') or ''
            if src:
                if src.startswith('//'):
                    size_chart = 'https:' + src
                elif src.startswith('/'):
                    size_chart = 'https://hso.com.ph' + src
                else:
                    size_chart = src

    # OCR the size chart if it exists
    if size_chart:
        flat_lines = extract_flat_ocr_lines(size_chart)
        size_chart_data = route_ocr_to_parser(flat_lines)

    return {
        "product_url": url,
        "product_images": images,
        "product_name": product_name,
        "price": price,
        "description": description,
        "size_chart_image": size_chart,
        "size_chart_data": size_chart_data
    }


# ✅ If called directly
if __name__ == "__main__":
    import json
    url = sys.argv[1]
    result = scrape(url)
    print(json.dumps(result))
