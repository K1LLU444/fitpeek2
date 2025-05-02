import cv2
import numpy as np
import os
import sys

# --- Read avatar height and shirt path from Laravel ---
if len(sys.argv) < 3:
    print("❌ Missing arguments: avatar height and shirt path are required.")
    sys.exit(1)

try:
    current_avatar_height = float(sys.argv[1])
    shirt_path = sys.argv[2].replace('\\', '/')  # sanitize Windows path
except ValueError:
    print("❌ Invalid height or shirt path format.")
    sys.exit(1)

print(f"📏 Avatar height input: {current_avatar_height} cm")
print(f"👕 Shirt image path: {shirt_path}")

# --- Folder paths ---
avatar_folder = 'C:/xampp/htdocs/fitpeek2/storage/app/public/avatars/'
output_path = 'C:/xampp/htdocs/fitpeek2/public/tryon_result.png'

# --- Find latest front-avatar ---
avatar_files = sorted(
    [f for f in os.listdir(avatar_folder) if f.startswith('front') and f.endswith('.png')],
    key=lambda x: os.path.getmtime(os.path.join(avatar_folder, x)),
    reverse=True
)
if not avatar_files:
    raise FileNotFoundError("❌ No avatar file found in avatars folder.")
avatar_path = os.path.join(avatar_folder, avatar_files[0])
avatar = cv2.imread(avatar_path, cv2.IMREAD_UNCHANGED)
if avatar is None:
    raise ValueError(f"❌ Failed to load avatar image: {avatar_path}")
print(f"✅ Loaded avatar: {avatar_path}")

# --- Load shirt image ---
shirt = cv2.imread(shirt_path, cv2.IMREAD_UNCHANGED)
if shirt is None:
    raise ValueError(f"❌ Failed to load shirt image: {shirt_path}")
print(f"✅ Loaded shirt: {shirt_path}")

# --- Ensure alpha channels ---
if avatar.shape[2] == 3:
    avatar = cv2.cvtColor(avatar, cv2.COLOR_BGR2BGRA)
if shirt.shape[2] == 3:
    shirt = cv2.cvtColor(shirt, cv2.COLOR_BGR2BGRA)

# --- Resize shirt to match avatar width at shoulder level ---
avatar_h, avatar_w = avatar.shape[:2]
target_shirt_width = int(avatar_w * 0.30)
scale_factor = target_shirt_width / shirt.shape[1]
new_shirt_height = int(shirt.shape[0] * scale_factor)
resized_shirt = cv2.resize(shirt, (target_shirt_width, new_shirt_height))

# --- Shoulder placement based on height ---
MIN_HEIGHT_CM = 154.54
MAX_HEIGHT_CM = 197.6
BASE_SHOULDER_Y = 200
MAX_OFFSET_PIXELS = 60

ratio = (MAX_HEIGHT_CM - current_avatar_height) / (MAX_HEIGHT_CM - MIN_HEIGHT_CM)
shoulder_y = int(BASE_SHOULDER_Y + ratio * MAX_OFFSET_PIXELS)

# --- Manual vertical tweak ---
Y_SHIFT = -150
shoulder_y += Y_SHIFT
print(f"🧷 Adjusted shoulder Y-position: {shoulder_y}px")

# --- Position shirt ---
x_offset = (avatar_w - resized_shirt.shape[1]) // 2
y_offset = shoulder_y

# --- Composite shirt over avatar ---
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
cv2.imwrite(output_path, output)
print(f"✅ Output saved to: {output_path}")
