import re
from ocr_parser import clean_number, known_sizes, number_like

def is_waist_range(token):
    return '-' in token or '–' in token or '—' in token

def parse_waist(token):
    token = token.replace("–", "-").replace("—", "-")
    if '-' in token:
        min_val, max_val = token.split('-')
        return float(clean_number(min_val)), float(clean_number(max_val))
    val = float(clean_number(token))
    return val, val

def parse_pants_group(group):
    size = None
    waist = None
    length = None

    for token in group:
        upper = token.upper()
        if upper in known_sizes:
            size = upper
        elif is_waist_range(token):
            waist = token
        elif number_like.match(token):
            length = token

    if size and waist and length:
        try:
            waist_min, waist_max = parse_waist(waist)
            return {
                "size": size,
                "waistline_min": round(waist_min, 1),
                "waistline_max": round(waist_max, 1),
                "length": round(float(clean_number(length)), 1)
            }
        except Exception as e:
            print(f"⚠️ [Group Parse Error] {group} → {e}")
    return None


def parse_pants_lines(flat_lines):
    parsed = []

    # Find the start index where the first valid size appears
    start = next((i for i, token in enumerate(flat_lines) if token.upper() in known_sizes), None)
    if start is None:
        return []

    # Parse every 3-token group starting from the first size token
    i = start
    while i + 2 < len(flat_lines):
        group = flat_lines[i:i+3]
        parsed_row = parse_pants_group(group)
        if parsed_row:
            parsed.append(parsed_row)
            i += 3
        else:
            i += 1  # Try next shift if group didn't parse

    return parsed
