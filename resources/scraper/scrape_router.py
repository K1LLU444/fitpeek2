import sys
import json
import scrape_nckatmtc
import scrape_hso

# 🚀 Get the URL passed as argument
url = sys.argv[1]

# 🚀 Decide based on domain
if "nckatmtc.com" in url:
    result = scrape_nckatmtc.scrape(url)

elif "hso.com.ph" in url:
    result = scrape_hso.scrape(url)

else:
    print(json.dumps({"error": "Unsupported site"}))
    sys.exit(1)

# 🚀 Return JSON to Laravel
print(json.dumps(result))
