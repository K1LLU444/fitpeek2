
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
length_inches = sys.argv[5]
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

# --- Boost factors ---
if product_type == "top":
    width_boost = 1.45
    length_boost = 1.17
elif product_type == "bottom":
    width_boost = 0.9
    length_boost = 1.15
else:
    width_boost = 1.0
    length_boost = 1.0

# --- Convert to cm with fallback if length is missing ---
width_cm = width_inches * 2.54 * width_boost

if length_inches == "none" or float(length_inches) == 0.0:
    # Fallback: make length ~75% of width (reasonable default for shorts/skirts)
    length_cm = width_inches * 0.75 * 2.54 * length_boost
    print(f"⚠️ No length provided — using fallback: {length_cm:.2f} cm (75% of width)")
else:
    length_cm = float(length_inches) * 2.54 * length_boost

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
shoulder_ratio = 0.15
hip_ratio = 0.45

if product_type == "top":
    y_offset = int(avatar_h * shoulder_ratio)
    print(f"Aligning shirt at shoulder Y = {y_offset}px")
elif product_type == "bottom":
    y_offset = int(avatar_h * hip_ratio)
    print(f"Aligning pants/skirt at hips Y = {y_offset}px")
else:
    raise ValueError(f"Unknown product type: {product_type}. Must be 'top' or 'bottom'.")

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
