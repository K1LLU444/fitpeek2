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

    # Ensure output directory exists
    out_dir = os.path.dirname(output_path)
    if not os.path.exists(out_dir):
        os.makedirs(out_dir, exist_ok=True)

    success = cv2.imwrite(output_path, full)
    print(f"[✅] Write success: {success} — Saved to: {output_path}")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    mirror_half_to_full(input_path, output_path)
