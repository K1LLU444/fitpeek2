import cv2
import numpy as np
import sys
import os

def mirror_half_to_full(img_path, output_path, x_start=1, x_end=221):
    print(f"[DEBUG] Input path: {img_path}")
    print(f"[DEBUG] Output path: {output_path}")

    img = cv2.imread(img_path, cv2.IMREAD_UNCHANGED)
    if img is None:
        print("[❌] Failed to read input image.")
        return

    h = img.shape[0]
    region = img[:, x_start:x_end]
    mirrored = cv2.flip(region, 1)
    full = np.hstack((region, mirrored))

    # --- Auto-crop to remove excess transparent or empty space ---
    if full.shape[2] == 4:
        # Has alpha channel (transparency)
        alpha = full[:, :, 3]
        coords = cv2.findNonZero(alpha)
        if coords is not None:
            x, y, w, h = cv2.boundingRect(coords)
            full_cropped = full[y:y+h, x:x+w]
        else:
            full_cropped = full  # fallback
    else:
        # No alpha channel — crop based on visible pixels
        gray = cv2.cvtColor(full, cv2.COLOR_BGR2GRAY)
        _, thresh = cv2.threshold(gray, 1, 255, cv2.THRESH_BINARY)
        coords = cv2.findNonZero(thresh)
        if coords is not None:
            x, y, w, h = cv2.boundingRect(coords)
            full_cropped = full[y:y+h, x:x+w]
        else:
            full_cropped = full  # fallback

    # Ensure output directory exists
    out_dir = os.path.dirname(output_path)
    if not os.path.exists(out_dir):
        os.makedirs(out_dir, exist_ok=True)

    success = cv2.imwrite(output_path, full_cropped)
    print(f"[✅] Write success: {success} — Saved to: {output_path}")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    mirror_half_to_full(input_path, output_path)
