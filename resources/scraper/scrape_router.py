import sys
import json
import scrape_nckatmtc
import scrape_dailygrind
import scrape_mango  

# 🚀 Get the URL passed as argument
url = sys.argv[1]

# 🚀 Decide which scraper to use based on domain
if "nckatmtc.com" in url:
    result = scrape_nckatmtc.scrape(url)

elif "dailygrindstore.com" in url:
    result = scrape_dailygrind.scrape(url)

elif "shop.mango.com" in url:  
    result = scrape_mango.scrape(url)

else:
    # 🚨 If unsupported site
    result = {"error": "Unsupported site"}
    print(json.dumps(result))
    sys.exit(1)

# 🚀 Return JSON back to Laravel
print(json.dumps(result))
