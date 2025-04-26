from paddleocr import PaddleOCR
from PIL import Image
from io import BytesIO
import requests
import numpy as np
import re
import os
import cv2

ocr_model = None

known_sizes = {"XXS", "XS", "S", "M", "L", "XL", "XXL", "2XL", "3XL", "4XL"}
number_like = re.compile(r'[0-9]+(?:\.[0-9]+)?(?:\s*[-–—]\s*[0-9]+(?:\.[0-9]+)?)?')
number_like_3col = re.compile(r'^\d{1,3}([.,]\d+)?["”]?$')


def init_paddleocr():
    global ocr_model
    if ocr_model is None:
        ocr_model = PaddleOCR(use_angle_cls=True, lang='en', show_log=False, use_space_char=True)

def download_image(image_url):
    response = requests.get(image_url)
    image = Image.open(BytesIO(response.content)).convert("RGB")
    return np.array(image)

def clean_number(val):
    val = val.replace('O', '0').replace('Q', '9').replace('Z', '2')
    val = val.replace('S', '5').replace('I', '1').replace('l', '1')
    val = val.replace(')', '1').replace('/', '7').replace('–', '-').replace('—', '-')
    val = re.sub(r'[^\d\.-]', '', val)
    return val

def extract_flat_ocr_lines(image_url):
    """
    Returns flat OCR lines from the image using PaddleOCR
    """
    init_paddleocr()
    image = download_image(image_url)
    save_path = "public/debug/ocr_debug.png"
    os.makedirs(os.path.dirname(save_path), exist_ok=True)

    result = ocr_model.ocr(image, cls=True)
    flat_lines = [line[1][0].strip() for line in result[0] if line[1][0].strip()]

    # Save annotated debug image
    image_cv = cv2.cvtColor(image, cv2.COLOR_RGB2BGR)
    for line in result[0]:
        box = np.array(line[0]).astype(int)
        text = line[1][0]
        cv2.polylines(image_cv, [box], isClosed=True, color=(0, 255, 0), thickness=2)
        x, y = box[0]
        cv2.putText(image_cv, text, (x, y - 5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (36, 255, 12), 1)

    cv2.imwrite(save_path, image_cv)

    return flat_lines
