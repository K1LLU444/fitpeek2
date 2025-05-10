# mirror_back_lower.py
import cv2
import numpy as np
import sys
import os

def mirror_right_half(input_path, output_path, start_x=231, end_x=448):
    img = cv2.imread(input_path, cv2.IMREAD_UNCHANGED)

    if img is None:
        print("❌ Failed to load image.")
        return

    right_half = img[:, start_x:end_x]
    mirrored = cv2.flip(right_half, 1)
    combined = cv2.hconcat([mirrored, right_half])

    if combined.shape[2] == 4:
        alpha = combined[:, :, 3]
        coords = cv2.findNonZero(alpha)
        x, y, w, h = cv2.boundingRect(coords)
        cropped = combined[y:y+h, x:x+w]
    else:
        gray = cv2.cvtColor(combined, cv2.COLOR_BGR2GRAY)
        _, thresh = cv2.threshold(gray, 1, 255, cv2.THRESH_BINARY)
        coords = cv2.findNonZero(thresh)
        x, y, w, h = cv2.boundingRect(coords)
        cropped = combined[y:y+h, x:x+w]

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    success = cv2.imwrite(output_path, cropped)
    print(f"✅ Saved back lower mirrored to: {output_path} (success: {success})")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    mirror_right_half(input_path, output_path)
