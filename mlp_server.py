from flask import Flask, request, jsonify
import joblib
import numpy as np
import os

app = Flask(__name__)

# Load your models once at startup
project_root = os.path.dirname(os.path.abspath(__file__))

# Load female and male models
model_female = joblib.load(os.path.join(project_root, "mlp_female_beta_predictor.pkl"))
scaler_female = joblib.load(os.path.join(project_root, "mlp_female_scaler.pkl"))

model_male = joblib.load(os.path.join(project_root, "mlp_male_beta_predictor.pkl"))
scaler_male = joblib.load(os.path.join(project_root, "mlp_male_scaler.pkl"))

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    measurements = data.get('measurements')  # list of [height, weight, chest, waist, hips, inseam]
    sex = data.get('sex', 'female').lower()

    if not measurements or sex not in ['female', 'male']:
        return jsonify({'error': 'Invalid input'}), 400

    features = np.array(measurements).reshape(1, -1)

    if sex == 'female':
        scaled = scaler_female.transform(features)
        betas = model_female.predict(scaled)[0]
    else:
        scaled = scaler_male.transform(features)
        betas = model_male.predict(scaled)[0]

    return jsonify({'betas': betas.tolist()})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)
