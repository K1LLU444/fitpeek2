import sys
import requests
from bs4 import BeautifulSoup
import json

url = sys.argv[1]
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

# ✅ Works on nckatmtc.com — gets image from <noscript> inside div.product__media
images = []

# Find all img tags that include "Artboard" in their src
images = []

for img in soup.find_all('img'):
    src = img.get('src', '')
    if 'Artboard' in src and 'nckatmtc.com' in src:
        if src.startswith('//'):
            src = 'https:' + src
        images.append(src)
        break  # Grab only the first one


# ✅ Product Name
name_tag = soup.select_one('div.product__title h1')
product_name = name_tag.text.strip() if name_tag else None

# ✅ Price
price_tag = soup.select_one('span.price-item')
price = price_tag.text.strip().replace('₱', '').replace(',', '').replace(' PHP', '') if price_tag else None

# ✅ Description
desc_tag = soup.select_one('div.product__description')
description = desc_tag.text.strip() if desc_tag else None

# ✅ Size Chart
size_chart = None
for img in soup.select('img'):
    src = img.get('src', '')
    alt = img.get('alt', '').lower()
    if 'size' in alt or 'chart' in src.lower():
        size_chart = 'https:' + src if src.startswith('//') else src
        break

# ✅ Final Output
result = {
    "product_url": url,
    "front_image": images[0] if images else None,
    "product_name": product_name,
    "price": price,
    "description": description,
    "size_chart_image": size_chart
}

print(json.dumps(result))
