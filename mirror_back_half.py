import cv2
import sys

def mirror_right_half(input_path, output_path, start_x, end_x):
    # Load image
    img = cv2.imread(input_path, cv2.IMREAD_UNCHANGED)

    # Crop right half (user-defined region)
    right_half = img[:, start_x:end_x]

    # Mirror it
    mirrored = cv2.flip(right_half, 1)

    # Combine mirrored half + original
    combined = cv2.hconcat([mirrored, right_half])

    # Save result
    cv2.imwrite(output_path, combined)

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]

    # Based on your earlier HTML map coords for right side:
    start_x = 227
    end_x = 447

    mirror_right_half(input_path, output_path, start_x, end_x)
