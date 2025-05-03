import cv2
import numpy as np
import os
import sys

# --- Read avatar height and shirt path from Laravel ---
if len(sys.argv) < 6:
    print("❌ Missing args: avatar_height, avatar_path, shirt_path, width, length")
    sys.exit(1)

current_avatar_height = float(sys.argv[1])
avatar_path = sys.argv[2].replace('\\', '/')
shirt_path = sys.argv[3].replace('\\', '/')
width_inches = float(sys.argv[4])
length_inches = float(sys.argv[5])

# --- Display inputs ---
shirt_filename = os.path.basename(shirt_path)
print(f"📏 Avatar height input: {current_avatar_height} cm")
print(f"👕 Front shirt image path: {shirt_path}")
print(f"🧢 Shirt filename: {shirt_filename}")
print(f"🧍 Using front avatar: {avatar_path}")

# --- Load front avatar ---
avatar = cv2.imread(avatar_path, cv2.IMREAD_UNCHANGED)
if avatar is None:
    raise ValueError(f"❌ Failed to load front avatar image: {avatar_path}")
print("✅ Front avatar loaded successfully")

# --- Load front shirt image ---
shirt = cv2.imread(shirt_path, cv2.IMREAD_UNCHANGED)
if shirt is None:
    raise ValueError(f"❌ Failed to load front shirt image: {shirt_path}")
print("✅ Front shirt loaded successfully")

# --- Ensure alpha channels ---
if avatar.shape[2] == 3:
    avatar = cv2.cvtColor(avatar, cv2.COLOR_BGR2BGRA)
if shirt.shape[2] == 3:
    shirt = cv2.cvtColor(shirt, cv2.COLOR_BGR2BGRA)

# --- Resize shirt using width/length in inches ---
target_shirt_width = int(width_inches * 10)  # Convert inches to pixels
target_shirt_height = int(length_inches * 10)
resized_shirt = cv2.resize(shirt, (target_shirt_width, target_shirt_height))
print(f"📐 Front shirt resized to: {resized_shirt.shape[1]}x{resized_shirt.shape[0]}")

# --- Scale avatar before overlay ---
scale_avatar = 1.5  # Scale avatar (e.g., 150%)
avatar = cv2.resize(avatar, (0, 0), fx=scale_avatar, fy=scale_avatar, interpolation=cv2.INTER_LINEAR)
avatar_h, avatar_w = avatar.shape[:2]

# --- Shoulder Y-position based on avatar height ---
MIN_HEIGHT_CM = 154.54
MAX_HEIGHT_CM = 197.6
BASE_SHOULDER_Y = 200
MAX_OFFSET_PIXELS = 60
Y_SHIFT = -110

ratio = (MAX_HEIGHT_CM - current_avatar_height) / (MAX_HEIGHT_CM - MIN_HEIGHT_CM)
shoulder_y = int(BASE_SHOULDER_Y + ratio * MAX_OFFSET_PIXELS + Y_SHIFT)
print(f"🧷 Final front shirt Y-position: {shoulder_y}px")

# --- Shirt position ---
x_offset = (avatar_w - resized_shirt.shape[1]) // 2 + 110
y_offset = shoulder_y

# --- Overlay front shirt onto avatar ---
output = avatar.copy()
for y in range(resized_shirt.shape[0]):
    for x in range(resized_shirt.shape[1]):
        if y + y_offset < avatar_h and x + x_offset < avatar_w:
            alpha = resized_shirt[y, x, 3] / 255.0
            if alpha > 0:
                for c in range(3):
                    output[y + y_offset, x + x_offset, c] = (
                        alpha * resized_shirt[y, x, c] + (1 - alpha) * output[y + y_offset, x + x_offset, c]
                    )
                output[y + y_offset, x + x_offset, 3] = 255

# --- Save output ---
output_path = os.path.abspath("public/tryon_result.png")
cv2.imwrite(output_path, output)
print(f"✅ Front overlay saved: {output_path}")
