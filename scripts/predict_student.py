import sys
import json
import os
import joblib
import pandas as pd
from catboost import CatBoostClassifier

def predict():
    base_path = os.path.dirname(os.path.abspath(__file__))
    try:
        input_data = json.loads(sys.stdin.read())
        answers = input_data.get('answers', [])

        model = CatBoostClassifier()
        model.load_model(os.path.join(base_path, 'student_performance_model.cbm'))
        label_encoder = joblib.load(os.path.join(base_path, 'label_encoder.pkl'))

        expected_features = model.feature_names_
        full_input = ["0"] * len(expected_features)
        
        for i, val in enumerate(answers):
            if i < len(expected_features):
                full_input[i] = str(val)
                
        df_input = pd.DataFrame([full_input], columns=expected_features)
        prediction_raw = model.predict(df_input)
        
        pred_index = int(prediction_raw.flatten()[0])
        result_text = label_encoder.inverse_transform([pred_index])[0]

        print(json.dumps({'status': 'success', 'prediction': str(result_text)}))
    except Exception as e:
        print(json.dumps({'status': 'error', 'message': str(e)}))

if __name__ == "__main__":
    predict()