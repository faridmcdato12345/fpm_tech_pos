import os
import sys
import pandas as pd
from prophet import Prophet
import json

os.environ["PROPHET_DISABLE_PLOTLY"] = "True"

def main():
    # Read command-line arguments
    input_json_path = sys.argv[1]
    periods = int(sys.argv[2])
    frequency = sys.argv[3]

    # Load sales data
    with open(input_json_path, 'r') as f:
        data = json.load(f)

    df = pd.DataFrame(data)

    # Ensure correct datetime format
    df['ds'] = pd.to_datetime(df['ds'], errors='coerce')

    # Remove invalid dates
    df = df.dropna(subset=['ds'])

    # Find last sale date
    max_date = df['ds'].max()

    # Aggregate total sales (for overall prediction)
    total_sales_df = df.groupby('ds')['y'].sum().reset_index()

    # Train Prophet for total sales
    total_model = Prophet()
    total_model.fit(total_sales_df)

    # Generate future dates
    future_dates = pd.date_range(start=max_date + pd.DateOffset(1), periods=periods, freq=frequency)
    future_df = pd.DataFrame({'ds': future_dates})

    # Predict total sales
    total_forecast = total_model.predict(future_df)

    # Prepare product-wise predictions
    predictions = []

    # Calculate product distribution from historical sales
    product_distribution = df.groupby('product_id')['y'].sum().reset_index()
    product_distribution['percentage'] = product_distribution['y'] / product_distribution['y'].sum()

    # Get unique product details
    product_details = df[['product_id', 'item_name']].drop_duplicates()

    for i, row in total_forecast.iterrows():
        total_units_predicted = round(row["yhat"])
        yhat_lower = round(row["yhat_lower"])
        yhat_upper = round(row["yhat_upper"])

        # Prepare items list
        items_list = []

        # Distribute predicted total sales among products
        for _, product_row in product_distribution.iterrows():
            product_id = product_row["product_id"]
            pct = product_row["percentage"]
            predicted_units = round(total_units_predicted * pct)

            # Get product details
            product_info = product_details[product_details['product_id'] == product_id].iloc[0]

            items_list.append({
                "product_id": int(product_id),
                "item_name": product_info["item_name"],
                "predicted_units": predicted_units
            })

         # Append the final structured prediction
        predictions.append({
            "ds": row["ds"].strftime("%Y-%m-%d"),
            "yhat": total_units_predicted,
            "yhat_lower": yhat_lower,
            "yhat_upper": yhat_upper,
            "items": items_list
        })

    # Save predictions to JSON
    output_path = sys.argv[4]
    with open(output_path, 'w') as f:
        json.dump(predictions, f, indent=4)

    print(f"Predictions saved to {output_path}")

if __name__ == "__main__":
    main()
