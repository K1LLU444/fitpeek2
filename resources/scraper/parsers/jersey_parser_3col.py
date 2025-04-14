import re
from ocr_parser import known_sizes, clean_number, number_like_3col

def parse_jersey_lines_3col(flat_lines):
    print("🔍 Grouping by 3 after 'LENGTH'...")

    # Find index of "LENGTH"
    start_idx = 0
    for i, token in enumerate(flat_lines):
        if token.strip().upper() == "LENGTH":
            start_idx = i + 1
            break

    # Group every 3 tokens after LENGTH
    groups = []
    i = start_idx
    while i + 2 < len(flat_lines):
        group = flat_lines[i:i+3]
        groups.append(group)
        i += 3
    for g in groups:
        print(g)

    # Parse each group
        parsed = []
    for group in groups:
        try:
            size = next((t for t in group if t.upper() in known_sizes), None)

            numbers = []
            for t in group:
                if t.upper() not in known_sizes:
                    try:
                        numbers.append(float(clean_number(t)))
                    except:
                        continue

            if size and len(numbers) == 2:
                width, length = sorted(numbers)
                parsed.append({
                    "size": size.upper(),
                    "width": round(width, 1),
                    "length": round(length, 1)
                })

        except Exception as e:
            print(f"⚠️ Parse error on group {group} → {e}")
            continue

    print("\n✅ Final Parsed Size Data:")
    for p in parsed:
        print(p)

    return parsed
