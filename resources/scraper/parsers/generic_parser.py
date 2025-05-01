import re
from ocr_parser import clean_number, known_sizes, number_like

def parse_generic_lines(flat_lines):
    parsed = []
    i = 0

    while i < len(flat_lines):
        token = flat_lines[i].upper()
        if token in known_sizes:
            size = token
            # Expect the next 3 tokens to be: width, length, sleeve
            if i + 3 < len(flat_lines):
                try:
                    width = float(clean_number(flat_lines[i + 1]))
                    length = float(clean_number(flat_lines[i + 2]))
                    sleeve = float(clean_number(flat_lines[i + 3]))

                    parsed.append({
                        "size": size,
                        "width": width,
                        "length": length,
                        "sleeve_length": sleeve
                    })

                    i += 4
                    continue
                except Exception as e:
                    print(f"⚠️ [Generic Parse Error] {flat_lines[i:i+4]} → {e}")
        i += 1

    return parsed
