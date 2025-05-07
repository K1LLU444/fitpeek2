import cv2
import numpy as np
import os
import sys

print("Working directory:", os.getcwd())

if len(sys.argv) < 8:
    print("Received sys.argv:", sys.argv)
    print("Missing args: avatar_height, avatar_path, clothing_path, width, length, output_path, product_type")
    sys.exit(1)

# --- Args ---
current_avatar_height = float(sys.argv[1])
avatar_path = sys.argv[2].replace('\\', '/')
clothing_path = sys.argv[3].replace('\\', '/')
width_inches = float(sys.argv[4])
length_raw = sys.argv[5].strip().lower()
output_path = sys.argv[6].replace('\\', '/')
product_type = sys.argv[7].strip().lower()

# --- Log inputs ---
print(f"Avatar height input: {current_avatar_height} cm")
print(f"Clothing image path: {clothing_path}")
print(f"Using avatar: {avatar_path}")
print(f"Product type: {product_type}")

# --- Load avatar ---
avatar = cv2.imread(avatar_path, cv2.IMREAD_UNCHANGED)
if avatar is None:
    raise ValueError(f"Failed to load avatar: {avatar_path}")
if avatar.shape[2] == 3:
    avatar = cv2.cvtColor(avatar, cv2.COLOR_BGR2BGRA)

# --- Load clothing ---
clothing = cv2.imread(clothing_path, cv2.IMREAD_UNCHANGED)
if clothing is None:
    raise ValueError(f"Failed to load clothing: {clothing_path}")
if clothing.shape[2] == 3:
    clothing = cv2.cvtColor(clothing, cv2.COLOR_BGR2BGRA)

# --- Resize avatar ---
scale_avatar = 1.9
avatar = cv2.resize(avatar, (0, 0), fx=scale_avatar, fy=scale_avatar)
avatar_h, avatar_w = avatar.shape[:2]

# --- px/cm ratio ---
pixels_per_cm = avatar_h / current_avatar_height

# --- Determine boost values ---
if product_type == "top":
    width_boost = 1.45
    length_boost = 1.17
elif product_type == "bottom":
    width_boost = 1.05
    length_boost = 1.0
else:
    raise ValueError(f"Unknown product type: {product_type}")

# --- Convert values ---
width_cm = width_inches * 2.54 * width_boost

# Handle length: fallback to 75% of width if "none"
if length_raw == "none":
    print("⚠️ No length provided — using fallback 75% of width")
    length_cm = width_cm * 0.75
else:
    length_inches = float(length_raw)
    length_cm = length_inches * 2.54 * length_boost

target_width = int(width_cm * pixels_per_cm)
target_height = int(length_cm * pixels_per_cm)

# --- Clamp ---
target_width = max(50, min(target_width, avatar_w))
target_height = max(50, min(target_height, avatar_h))

# --- Resize clothing ---
resized_clothing = cv2.resize(clothing, (target_width, target_height))

print(f"Clothing size (px): {target_width} x {target_height}")
print(f"Avatar height (px): {avatar_h}, real height: {current_avatar_height} cm")
print(f"px/cm ratio: {pixels_per_cm:.2f}")

# --- Placement ---
x_offset = (avatar_w - target_width) // 2
shoulder_ratio = 0.17
hip_ratio = 0.45

if product_type == "top":
    y_offset = int(avatar_h * shoulder_ratio)
    print(f"Aligning shirt at shoulder Y = {y_offset}px")
elif product_type == "bottom":
    if "skirt" in clothing_path.lower():
        width_boost = 0.72  # ⬅️ smaller boost to fit the waist/hip area more tightly
        length_boost = 1.1
        print("Skirt detected — using skirt-specific boost values")
    else:
        width_boost = 1.05
        length_boost = 1.0

# --- Composite overlay ---
output = avatar.copy()
for y in range(resized_clothing.shape[0]):
    for x in range(resized_clothing.shape[1]):
        if 0 <= y + y_offset < avatar_h and 0 <= x + x_offset < avatar_w:
            alpha = resized_clothing[y, x, 3] / 255.0
            if alpha > 0:
                for c in range(3):
                    output[y + y_offset, x + x_offset, c] = (
                        alpha * resized_clothing[y, x, c] +
                        (1 - alpha) * output[y + y_offset, x + x_offset, c]
                    )
                output[y + y_offset, x + x_offset, 3] = 255

# --- Save ---
print(f"Saving to: {output_path}")
success = cv2.imwrite(output_path, output)
if not success:
    print("Failed to save output image.")
