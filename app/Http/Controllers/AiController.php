<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiController extends Controller
{
    public function index()
    {
        return inertia('Ai/Index');
    }

    public function runPrediction(Request $request)
    {
        $validated = $request->validate([
            'frequency' => 'required|string|in:D,W,M',
            'periods' => 'required|integer|min:1',
        ]);
        $frequency = $validated['frequency'];
        $periods = $validated['periods'];
        // Query the database for historical sales data
        $salesData = DB::table('sales')
            ->select('created_at as ds', 'quantity as y') // Rename fields for Prophet compatibility
            ->where('remarks',config('remarks.complete'))
            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->get();
        //dd($salesData);

        // Save the queried data to a temporary JSON file
        $tempJsonPath = storage_path('app/temp_sales_data.json');
        file_put_contents($tempJsonPath, $salesData->toJson());

         $predictionsPath = storage_path('app/predictions.json'); // Output JSON file
        //
        // Run the Python script with the given parameters
        $scriptPath = base_path('app/ai_scripts/python/predict.py');
        $pythonPath = env('PYTHON_PATH', 'python');
        $command = escapeshellcmd("$pythonPath")
            . ' ' . escapeshellarg($scriptPath)
            . ' ' . escapeshellarg($tempJsonPath)
            . ' ' . escapeshellarg($periods)
            . ' ' . escapeshellarg($frequency)
            . ' ' . escapeshellarg($predictionsPath);

        $output = shell_exec($command . " 2>&1");
        $output2 = shell_exec("python --version 2>&1");
        // Check for prediction results

        if (file_exists($predictionsPath)) {
            $predictions = json_decode(file_get_contents($predictionsPath), true);
            return response()->json(['predictions' => $predictions]);
        }

        return response()->json(['error' => 'Prediction failed.'], 500);
    }
}
