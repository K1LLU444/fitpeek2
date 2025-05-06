# generate_dual_avatars.py
import sys, os, json, base64, torch, numpy as np, trimesh
from smplx import SMPLX

def create_avatar(betas, output_path, gender='female', body_pose=None):
    model_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'models', 'smplx')
    model = SMPLX(model_path=model_path, gender=gender, num_betas=10)
    betas_tensor = torch.tensor([betas], dtype=torch.float32)

    output = model(betas=betas_tensor, body_pose=body_pose)
    vertices = output.vertices.detach().cpu().numpy().squeeze()
    faces = model.faces

    lowest_y = vertices[:, 1].min()
    buffer = 0.03
    vertices[:, 1] -= (lowest_y - buffer)
    vertices[:, 0] -= np.mean(vertices[:, 0])
    vertices[:, 2] -= np.mean(vertices[:, 2])

    mesh = trimesh.Trimesh(vertices, faces, process=True)
    mesh.export(output_path)
    print(f"[OK] Exported: {output_path}")

def main():
    encoded_betas = sys.argv[1]
    base_name = sys.argv[2]  # e.g., "avatar_1_1714569200"
    gender = sys.argv[3] if len(sys.argv) > 3 else 'female'

    decoded_betas = base64.b64decode(encoded_betas).decode('utf-8')
    betas = json.loads(decoded_betas)

    # === A-pose: Rotate shoulders ===
    body_pose = torch.zeros([1, 63], dtype=torch.float32)

    left_shoulder  = [-0.58, 0.12, 1.15]   # ⬅️ boosted Z to rotate arm downward AND outward
    right_shoulder = [-0.65, 0.15, 0.35]   # ✅ stable and clean

    # Left Shoulder
    body_pose[0, 16 * 3 + 0] = left_shoulder[0]
    body_pose[0, 16 * 3 + 1] = left_shoulder[1]
    body_pose[0, 16 * 3 + 2] = left_shoulder[2]

    # Right Shoulder
    body_pose[0, 17 * 3 + 0] = right_shoulder[0]
    body_pose[0, 17 * 3 + 1] = right_shoulder[1]
    body_pose[0, 17 * 3 + 2] = right_shoulder[2]

    # Output paths
    base_dir = os.path.dirname(os.path.abspath(__file__))
    avatars_dir = os.path.join(base_dir, 'storage', 'app', 'public', 'avatars')

    # 🔧 Make sure the directory exists
    os.makedirs(avatars_dir, exist_ok=True)

    a_pose_obj = os.path.join(avatars_dir, f"{base_name}_apose.obj")
    t_pose_obj = os.path.join(avatars_dir, f"{base_name}_tpose.obj")

    create_avatar(betas, a_pose_obj, gender, body_pose)
    create_avatar(betas, t_pose_obj, gender)

if __name__ == "__main__":
    main()
