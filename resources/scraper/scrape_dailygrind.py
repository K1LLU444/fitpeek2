import sys
import os
import warnings
import requests
from bs4 import BeautifulSoup

# ✅ Optional: Silence warnings
warnings.filterwarnings("ignore")

# ✅ MAIN Scraper Function
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

    # Find all <img> tags inside product media wrappers
    img_tags = soup.select('div.product-page--grid div.product-media--wrapper img')

    for img_tag in img_tags:
        srcset = img_tag.get('data-srcset')
        if srcset:
            # Split multiple sizes
            sources = srcset.split(',')
            found = False
            for source in sources:
                parts = source.strip().split(' ')
                if len(parts) == 2:
                    url_part, size = parts
                    if '1800w' in size:  # 🧠 Choose 1800px width images
                        if url_part.startswith('//'):
                            src = 'https:' + url_part
                        elif url_part.startswith('/'):
                            src = 'https://dailygrindstore.com' + url_part
                        else:
                            src = url_part
                        if src.strip():  # 🧹 Skip blank src
                            images.append(src)
                        found = True
                        break
            # Fallback if 1800w not found, get last highest
            if not found:
                last_url = sources[-1].strip().split(' ')[0]
                if last_url.startswith('//'):
                    src = 'https:' + last_url
                elif last_url.startswith('/'):
                    src = 'https://dailygrindstore.com' + last_url
                else:
                    src = last_url
                if src.strip():  # 🧹 Skip blank src
                    images.append(src)
        else:
            src = img_tag.get('src') or ''
            if src.strip():  # 🧹 Skip blank src
                if src.startswith('//'):
                    src = 'https:' + src
                elif src.startswith('/'):
                    src = 'https://dailygrindstore.com' + src
                images.append(src)

    # ✅ Final deduplicate and cleaning
    images = list(dict.fromkeys(images))


    # 🛍️ Product Name
    name_tag = soup.select_one('h2.product-page--title')
    product_name = name_tag.text.strip() if name_tag else None

    # 💰 Product Price
    price_tag = soup.select_one('div.product-form--price')
    price = price_tag.text.strip().replace('₱', '').replace(',', '').strip() if price_tag else None

    # 📄 Product Description and Size Chart
    desc_div = soup.select_one('div.product-page--description')
    description = None
    size_chart_data = None

    if desc_div:
        # 📝 Full description text
        description = desc_div.get_text(separator="\n").strip()

        # 📊 Parse size chart table if available
        table = desc_div.find('table')
        if table:
            headers = []
            rows = []
            for tr in table.find_all('tr'):
                cols = [td.get_text(strip=True) for td in tr.find_all('p')]
                if not headers:
                    headers = cols
                else:
                    rows.append(cols)

            size_chart_data = []
            if headers:
                sizes = headers[1:]  # Skip first column (e.g., 'Size', 'Waist')
                for i, size in enumerate(sizes):
                    size_entry = {"size": size}
                    for row in rows:
                        if i+1 < len(row):
                            key = row[0].lower()
                            value = row[i+1]
                            try:
                                size_entry[key] = float(value)
                            except ValueError:
                                size_entry[key] = value
                    size_chart_data.append(size_entry)

    # 🎯 Final return
    return {
        "product_url": url,
        "product_images": images,
        "product_name": product_name,
        "price": price,
        "description": description,
        "size_chart_data": size_chart_data
    }

# ✅ Direct test (for manual debugging)
if __name__ == "__main__":
    import json
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No URL provided"}))
        sys.exit(1)

    url = sys.argv[1]
    result = scrape(url)
    print(json.dumps(result))
