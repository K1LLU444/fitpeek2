import re
from ocr_parser import clean_number, known_sizes, number_like

def parse_vest_row(row_text):
    try:
        tokens = row_text.strip().split()
        size = next((t for t in tokens if t.upper() in known_sizes), None)
        numbers = [t for t in tokens if number_like.match(t)]
        if size and len(numbers) >= 2:
            return {
                "size": size.upper(),
                "length": round(float(clean_number(numbers[0])), 1),
                "width": round(float(clean_number(numbers[1])), 1)
            }
        return None
    except Exception as e:
        print(f"⚠️ [Vest Parse Error] {e}")
        return None

def parse_vest_lines(flat_lines):
    print("🧠 Using: Vest Parser")
    parsed = []
    i = 0
    while i + 2 < len(flat_lines):
        a, b, c = flat_lines[i], flat_lines[i+1], flat_lines[i+2]
        tokens = [a, b, c]
        size = next((t for t in tokens if t.upper() in known_sizes), None)
        numbers = [t for t in tokens if number_like.match(t)]
        if size and len(numbers) == 2:
            try:
                parsed.append({
                    "size": size.upper(),
                    "width": round(float(clean_number(numbers[0])), 1),
                    "length": round(float(clean_number(numbers[1])), 1)
                })
                i += 3  # jump to next row-like chunk
                continue
            except Exception as e:
                print(f"⚠️ [Vest Parse Error] {e}")
        i += 1  # fallback step forward

    return parsed
