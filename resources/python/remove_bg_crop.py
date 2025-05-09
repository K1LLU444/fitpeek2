import sys
from PIL import Image, ImageChops
from rembg import remove

def crop_empty_space(image):
    bg = Image.new(image.mode, image.size, (0, 0, 0, 0))
    diff = ImageChops.difference(image, bg)
    bbox = diff.getbbox()
    return image.crop(bbox) if bbox else image

def main(input_path, output_path):
    with open(input_path, 'rb') as file:
        input_data = file.read()

    output_data = remove(input_data)
    img = Image.open(io.BytesIO(output_data)).convert("RGBA")
    cropped_img = crop_empty_space(img)
    cropped_img.save(output_path)

if __name__ == "__main__":
    import io
    main(sys.argv[1], sys.argv[2])
