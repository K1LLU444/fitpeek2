import sys
import os
import json
import base64
import torch
import numpy as np
import trimesh
from smplx import SMPLX

def create_avatar(betas, output_file, gender='female'):
    try:
        # Define path to SMPL-X model
        base_dir = os.path.dirname(os.path.abspath(__file__))
        model_path = os.path.join(base_dir, 'models', 'smplx')
        print("[INFO] Using SMPL-X model path:", model_path)

        # Load SMPL-X model
        model = SMPLX(model_path=model_path, gender=gender, num_betas=10)
        betas_tensor = torch.tensor([betas], dtype=torch.float32)

        # Generate avatar vertices
        output = model(betas=betas_tensor)
        vertices = output.vertices.detach().cpu().numpy().squeeze()
        faces = model.faces

        # Grounding logic
        lowest_y = vertices[:, 1].min()
        buffer = 0.03
        vertices[:, 1] -= (lowest_y - buffer)

        # Centering logic
        vertices[:, 0] -= np.mean(vertices[:, 0])
        vertices[:, 2] -= np.mean(vertices[:, 2])

        # Final mesh
        mesh = trimesh.Trimesh(vertices, faces, process=True)
        mesh.export(output_file)

        print(f"[SUCCESS] Avatar exported to {output_file}")

    except Exception as e:
        print(f"[ERROR] Error during avatar generation: {str(e)}")

# 🔄 CLI mode
if __name__ == '__main__':
    encoded_betas = sys.argv[1]
    output_file = sys.argv[2]
    gender = sys.argv[3] if len(sys.argv) > 3 else 'female'

    decoded_betas = base64.b64decode(encoded_betas).decode('utf-8')
    betas = json.loads(decoded_betas)

    create_avatar(betas, output_file, gender)
