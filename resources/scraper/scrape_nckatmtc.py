import requests
from bs4 import BeautifulSoup
import pytesseract
import warnings
import os
import sys

# 🚫 Temporarily silence stdout and stderr before importing noisy modules
original_stdout = sys.stdout
original_stderr = sys.stderr
sys.stdout = open(os.devnull, 'w')
sys.stderr = open(os.devnull, 'w')

# 🧠 Import modules that print noisy logs (like PaddleOCR via ocr_router)
from ocr_parser import extract_flat_ocr_lines
from ocr_router import route_ocr_to_parser

# ✅ Restore stdout and stderr so final print(json) actually works!
sys.stdout = original_stdout
sys.stderr = original_stderr

# ✅ Setup
warnings.filterwarnings("ignore")
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# ✅ MAIN scraping function
def scrape(url):
    try:
        response = requests.get(url, timeout=10)
        if response.status_code != 200:
            return {"error": f"Invalid URL or product page not found (status {response.status_code})"}
    except requests.exceptions.RequestException as e:
        return {"error": f"Failed to connect to URL: {e}"}

    soup = BeautifulSoup(response.text, 'html.parser')

    # Front Images
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

    # Product Info
    name_tag = soup.select_one('div.product__title h1')
    product_name = name_tag.text.strip() if name_tag else None

    price_tag = soup.select_one('span.price-item')
    price = price_tag.text.strip().replace('₱', '').replace(',', '').replace(' PHP', '') if price_tag else None

    desc_tag = soup.select_one('div.product__description')
    description = desc_tag.text.strip() if desc_tag else None

    # Size Chart
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

    if size_chart:
        flat_lines = extract_flat_ocr_lines(size_chart)
        size_chart_data = route_ocr_to_parser(flat_lines)

        

    # Final result package
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