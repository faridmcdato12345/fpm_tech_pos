import sys
import pandas as pd
from prophet import Prophet
import json

def main():
    # Read command-line arguments
    input_json_path = sys.argv[1]  # Path to input JSON file
    periods = int(sys.argv[2])    # Number of future periods to predict
    frequency = sys.argv[3]       # Prediction frequency (D/W/M)

    # Load data from JSON file
    with open(input_json_path, 'r') as f:
        data = json.load(f)
    df = pd.DataFrame(data)

    # Train the Prophet model
    model = Prophet()
    model.fit(df)

    # Make future predictions
    future = model.make_future_dataframe(periods=periods, freq=frequency)
    forecast = model.predict(future)

    # Save predictions to JSON
    output_path = sys.argv[4]
    forecast[['ds', 'yhat', 'yhat_lower', 'yhat_upper']].to_json(output_path, orient='records')
    print(f"Predictions saved to {output_path}")

if __name__ == "__main__":
    main()
