from parsers.pants_parser import parse_pants_lines
from parsers.vest_parser import parse_vest_lines
from parsers.jersey_parser_4col import parse_jersey_lines_4col
from parsers.jersey_parser_3col import parse_jersey_lines_3col
from parsers.generic_parser import parse_generic_lines


def route_ocr_to_parser(flat_lines):
    text = " ".join(flat_lines).lower()

    if "pants" in text:
        print("🧠 Routing to: Pants Parser")
        return parse_pants_lines(flat_lines)

    elif "vest" in text:
        print("🧠 Routing to: Vest Parser")
        return parse_vest_lines(flat_lines)

    elif "jersey" in text:
        print("🧠 Routing to: Jersey Parser")
        if any("sleeve" in token.lower() for token in flat_lines):
            print("🔍 Detected 4-column jersey table")
            return parse_jersey_lines_4col(flat_lines)
        else:
            print("🔍 Detected 3-column jersey table")
            return parse_jersey_lines_3col(flat_lines)
    

    else:
        print("🧠 Routing to: Generic Parser")
        return parse_generic_lines(flat_lines)
