import sys
import requests
from bs4 import BeautifulSoup
import warnings
import os
import pytesseract

# ✅ Silencing warnings
warnings.filterwarnings("ignore")

# ✅ Try importing OCR helper modules
try:
    from ocr_parser import extract_flat_ocr_lines
    from ocr_router import route_ocr_to_parser
except ImportError:
    pass

# ✅ MAIN scraping function
def scrape(url):
    try:
        response = requests.get(url, timeout=10)
        if response.status_code != 200:
            return {"error": f"Invalid URL or product page not found (status {response.status_code})"}
    except requests.exceptions.RequestException as e:
        return {"error": f"Failed to connect to URL: {e}"}

    soup = BeautifulSoup(response.text, 'html.parser')

    # 🖼️ Front Images
    images = []
    media_gallery = soup.select_one('media-gallery')
    if media_gallery:
        nested_imgs = media_gallery.select('img')
        for img in nested_imgs:
            src = img.get('src', '')
            if src and ('.jpg' in src or '.png' in src):
                if src.startswith('//'):
                    src = 'https:' + src
                elif src.startswith('/'):
                    src = 'https://nckatmtc.com' + src
                images.append(src)

    images = [img for img in images if img.strip() != '']

    # 🛍️ Corrected Product Name selector
    name_tag = soup.select_one('div.product__title h1')
    product_name = name_tag.text.strip() if name_tag else None

    # 💰 Corrected Product Price selector
    price_tag = soup.select_one('span.price-item')
    price = price_tag.text.strip().replace('₱', '').replace(',', '').replace(' PHP', '') if price_tag else None

    # 📄 Corrected Product Description selector
    desc_div = soup.select_one('div.product__description')

    description = None
    size_chart = None
    size_chart_data = None

    desc_div = soup.select_one('div.product__description')
    if desc_div:
        description = desc_div.get_text(separator="\n").strip()

        for img in soup.select('img'):
            src = img.get('src', '') or ''
            alt = img.get('alt', '').lower()
            if 'size' in alt or 'chart' in src.lower():
                if src.startswith('//'):
                    size_chart = 'https:' + src
                elif src.startswith('/'):
                    size_chart = 'https://nckatmtc.com' + src
                else:
                    size_chart = src
                break

        if not size_chart:
            img_tag = desc_div.find('img')
            if img_tag:
                src = img_tag.get('src') or ''
                if src.startswith('//'):
                    size_chart = 'https:' + src
                elif src.startswith('/'):
                    size_chart = 'https://nckatmtc.com' + src
                else:
                    size_chart = src

        if size_chart:
            try:
                flat_lines = extract_flat_ocr_lines(size_chart)
                size_chart_data = route_ocr_to_parser(flat_lines)
            except Exception:
                size_chart_data = None



    return {
        "product_url": url,
        "product_images": images,
        "product_name": product_name,
        "price": price,
        "description": description,
        "size_chart_image": size_chart,
        "size_chart_data": size_chart_data,
    }

if __name__ == "__main__":
    url = sys.argv[1]
    result = scrape(url)
    print(result)
