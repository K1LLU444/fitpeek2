import sys
import joblib
import numpy as np
import json
import os

if len(sys.argv) != 3:
    print("Usage: python mlp_predict.py \"165,65,98,89,96,70\" female")
    sys.exit(1)

input_str = sys.argv[1]
gender = sys.argv[2].lower()

project_root = os.path.dirname(os.path.abspath(__file__))

if gender == 'female':
    model = joblib.load(os.path.join(project_root, "mlp_female_beta_predictor.pkl"))
    scaler = joblib.load(os.path.join(project_root, "mlp_female_scaler.pkl"))
elif gender == 'male':
    model = joblib.load(os.path.join(project_root, "mlp_male_beta_predictor.pkl"))
    scaler = joblib.load(os.path.join(project_root, "mlp_male_scaler.pkl"))
else:
    print("Invalid gender")
    sys.exit(1)

features = np.array([float(x) for x in input_str.split(',')]).reshape(1, -1)
scaled = scaler.transform(features)
betas = model.predict(scaled)[0]

print(json.dumps(betas.tolist()))
