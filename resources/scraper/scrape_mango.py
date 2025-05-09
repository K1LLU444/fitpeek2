import requests
from bs4 import BeautifulSoup
import pytesseract
import warnings
import os
os.environ['PADDLEOCR_HOME'] = '/tmp/.paddleocr'
import sys
import json

# Silence noisy output
original_stdout = sys.stdout
original_stderr = sys.stderr
sys.stdout = open(os.devnull, 'w')
sys.stderr = open(os.devnull, 'w')
sys.stdout = original_stdout
sys.stderr = original_stderr

# Setup
warnings.filterwarnings("ignore")
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def extract_size_chart_from_modal(soup):
    table = soup.find("table", class_=lambda c: c and "SizeGuideTable_table" in c)
    if not table:
        return None

    # Get headers: size, bust, waist, hip
    headers = [th.get_text(strip=True).lower() for th in table.find_all("th")]
    size_chart = []

    # Extract each row in the tbody
    for row in table.select("tbody tr"):
        cols = row.find_all("td")
        if len(cols) != len(headers):
            continue  # Skip malformed rows

        values = [col.get_text(strip=True) for col in cols]
        row_dict = dict(zip(headers, values))
        size_chart.append(row_dict)

    return size_chart if size_chart else None

def scrape(url):
    headers = {
        "User-Agent": "Mozilla/5.0"
    }

    try:
        response = requests.get(url, headers=headers, timeout=10)
        if response.status_code != 200:
            return {"error": f"Invalid URL or product page not found (status {response.status_code})"}
    except requests.exceptions.RequestException as e:
        return {"error": f"Failed to connect to URL: {e}"}

    soup = BeautifulSoup(response.content, 'html.parser')

    # Product Images
    images = []
    img_tags = soup.select('div.SlideshowWrapper_imageWrapper__mOY9M img')
    for img in img_tags:
        src = img.get('src')
        if src:
            if src.startswith('//'):
                src = 'https:' + src
            elif src.startswith('/'):
                src = 'https://shop.mango.com' + src
            if src not in images:
                images.append(src)

    # Product Name
    name_tag = soup.find('h1', class_=lambda c: c and 'ProductDetail_title' in c)
    product_name = name_tag.text.strip() if name_tag else None

    # Product Price
    price_tag = soup.find('span', class_=lambda c: c and 'SinglePrice_center' in c)
    price = None
    if price_tag:
        price_text = price_tag.text.strip()
        price = (
            price_text.replace('Php', '')
            .replace('₱', '')
            .replace(',', '')
            .strip()
        )

    # Product Description
    desc_div = soup.find('div', id='truncate-text')
    description = None
    if desc_div:
        paragraphs = desc_div.find_all('p')
        lines = [p.get_text(strip=True) for p in paragraphs if p.get_text(strip=True)]
        description = "\n".join(lines)

    # Size Chart from Modal
    size_chart_data = extract_size_chart_from_modal(soup)

    return {
        "product_url": url,
        "product_images": images,
        "product_name": product_name,
        "price": price,
        "description": description,
        "size_chart_data": size_chart_data
    }

# CLI
if __name__ == "__main__":
    url = sys.argv[1]
    result = scrape(url)
    print(json.dumps(result, indent=2))
