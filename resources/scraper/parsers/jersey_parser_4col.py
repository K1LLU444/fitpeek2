import re
from ocr_parser import clean_number, known_sizes, number_like

def parse_jersey_row(tokens):
    try:
        size = tokens[0].upper()
        width = float(clean_number(tokens[1]))
        length = float(clean_number(tokens[2]))
        sleeve_length = float(clean_number(tokens[3]))
        return {
            "size": size,
            "width": round(width, 1),
            "length": round(length, 1),
            "sleeve_length": round(sleeve_length, 1)
        }
    except Exception as e:
        print(f"⚠️ [Jersey Parse Error] {tokens} → {e}")
        return None

def parse_jersey_lines_4col(flat_lines):
    print("🧠 Using: Jersey Parser")
    parsed = []
    i = 0
    while i < len(flat_lines) - 3:
        token = flat_lines[i].upper()
        if token in known_sizes:
            group = flat_lines[i:i+4]
            if len(group) == 4:
                parsed_row = parse_jersey_row(group)
                if parsed_row:
                    parsed.append(parsed_row)
            i += 4
        else:
            i += 1
    return parsed
