<template>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Prediction Results</h2>

        <!-- Line Chart -->
        <div v-if="chartData" class="mb-6">
            <line-chart :data="chartData" />
        </div>

        <!-- Results Table -->
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Predicted Sales</th>
                    <th class="border p-2">Lower Bound</th>
                    <th class="border p-2">Upper Bound</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in predictions" :key="index"
                    :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'">
                    <td class="border p-2 text-center">{{ formatDate(row.ds) }}</td>
                    <td class="border p-2 text-right">{{ row.yhat.toFixed(2) }} units</td>
                    <td class="border p-2 text-right">{{ row.yhat_lower.toFixed(2) }} units</td>
                    <td class="border p-2 text-right">{{ row.yhat_upper.toFixed(2) }} units</td>
                </tr>
            </tbody>
        </table>

        <!-- No Results Message -->
        <div v-if="!predictions.length" class="text-gray-500 text-center mt-4">
            No predictions to display. Please run a prediction first.
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import LineChart from "./LineChart.vue"; // A chart component, e.g., Chart.js wrapper


const props = defineProps({
    predictionResult: {
        type: Array,
        default: () => [],
        required: true
    }
})
// Reactive predictions data
const predictions = ref([]);

// Reactive chart data for visualization
const chartData = ref(null);

// Function to update results (called after prediction completion)
const updateResults = (newPredictions) => {
    predictions.value = newPredictions;

    // Update chart data
    chartData.value = {
        labels: newPredictions.map((p) => p.ds), // Dates
        datasets: [
            {
                label: "Predicted Values",
                data: newPredictions.map((p) => p.yhat), // Predicted values
                fill: false,
                borderColor: "blue",
                tension: 0.4,
            },
        ],
    };
};
// Watch for changes in predictionResult and update
watch(
    () => props.predictionResult,
    (newResult) => {
        const formattedPrediction = newResult.map((result) => ({
            ds: new Date(result.ds).toISOString().split("T")[0],
            yhat: Math.round(result.yhat),
            yhat_lower: Math.round(result.yhat_lower),
            yhat_upper: Math.round(result.yhat_upper),
        }));
        updateResults(formattedPrediction);
    },
    { immediate: true } // Trigger immediately on component mount
);
// Helper function to format dates
const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>
