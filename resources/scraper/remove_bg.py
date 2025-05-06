import sys
from rembg import remove
from PIL import Image

input_path = sys.argv[1]
output_path = sys.argv[2]

# Load image and remove background
input_image = Image.open(input_path).convert("RGBA")
output_image = remove(input_image)

# Automatically crop to bounding box of non-transparent pixels
bbox = output_image.getbbox()
if bbox:
    output_image = output_image.crop(bbox)

# Save cropped image
output_image.save(output_path)