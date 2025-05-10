# mirror_half_lower.py
import cv2
import numpy as np
import sys
import os

def mirror_half_to_full(img_path, output_path, x_start=0, x_end=217):
    print(f"[DEBUG] Input path: {img_path}")
    print(f"[DEBUG] Output path: {output_path}")

    img = cv2.imread(img_path, cv2.IMREAD_UNCHANGED)
    if img is None:
        print("❌ Failed to read input image.")
        return

    h = img.shape[0]
    region = img[:, x_start:x_end]
    mirrored = cv2.flip(region, 1)
    full = np.hstack((region, mirrored))

    # Auto-crop
    if full.shape[2] == 4:
        alpha = full[:, :, 3]
        coords = cv2.findNonZero(alpha)
        if coords is not None:
            x, y, w, h = cv2.boundingRect(coords)
            full_cropped = full[y:y+h, x:x+w]
        else:
            full_cropped = full
    else:
        gray = cv2.cvtColor(full, cv2.COLOR_BGR2GRAY)
        _, thresh = cv2.threshold(gray, 1, 255, cv2.THRESH_BINARY)
        coords = cv2.findNonZero(thresh)
        if coords is not None:
            x, y, w, h = cv2.boundingRect(coords)
            full_cropped = full[y:y+h, x:x+w]
        else:
            full_cropped = full

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    success = cv2.imwrite(output_path, full_cropped)
    print(f"✅ Saved front lower mirrored to: {output_path} (success: {success})")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    mirror_half_to_full(input_path, output_path)
