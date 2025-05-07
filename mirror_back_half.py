import cv2
import sys
import numpy as np

def mirror_right_half(input_path, output_path, start_x, end_x):
    # Load image with alpha channel
    img = cv2.imread(input_path, cv2.IMREAD_UNCHANGED)

    if img is None:
        print("❌ Failed to load image.")
        return

    # Crop right half (user-defined region)
    right_half = img[:, start_x:end_x]

    # Mirror it
    mirrored = cv2.flip(right_half, 1)

    # Combine mirrored half + original
    combined = cv2.hconcat([mirrored, right_half])

    # --- Auto-crop transparent edges ---
    # Check if image has alpha channel
    if combined.shape[2] == 4:
        # Split alpha channel
        alpha = combined[:, :, 3]
        coords = cv2.findNonZero(alpha)
        x, y, w, h = cv2.boundingRect(coords)
        cropped = combined[y:y+h, x:x+w]
    else:
        # Fallback: crop based on grayscale intensity
        gray = cv2.cvtColor(combined, cv2.COLOR_BGR2GRAY)
        _, thresh = cv2.threshold(gray, 1, 255, cv2.THRESH_BINARY)
        coords = cv2.findNonZero(thresh)
        x, y, w, h = cv2.boundingRect(coords)
        cropped = combined[y:y+h, x:x+w]

    # Save result
    cv2.imwrite(output_path, cropped)
    print(f"✅ Saved cropped mirrored image to: {output_path}")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]

    # Based on earlier HTML map coords
    start_x = 227
    end_x = 447

    mirror_right_half(input_path, output_path, start_x, end_x)
