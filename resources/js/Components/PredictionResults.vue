<template>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Prediction Results</h2>

        <!-- Line Chart -->
        <div v-if="chartData" class="mb-6">
            <line-chart :data="chartData" class="w-full" />
        </div>

        <!-- Results Table -->
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Average Sales</th>
                    <th class="border p-2">Lowest Sale</th>
                    <th class="border p-2">Highest Sale</th>
                    <th class="border p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(row, index) in predictions" :key="index">
                    <tr :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'">
                        <td class="border p-2 text-center">{{ formatDate(row.ds) }}</td>
                        <td class="border p-2 text-right">{{ row.yhat.toFixed(2) }} units</td>
                        <td class="border p-2 text-right">{{ row.yhat_lower.toFixed(2) }} units</td>
                        <td class="border p-2 text-right">{{ row.yhat_upper.toFixed(2) }} units</td>
                        <td class="border p-2 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 hover:cursor-pointer"
                                @click.prevent="toggleRow(index)" v-if="showEye">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6" v-else>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>

                        </td>
                    </tr>
                    <transition name="expand">
                        <tr>
                            <td colspan="5">
                                <template v-if="expandedRows.includes(index)">
                                    <div class="p-6">
                                        <h1 class="mb-4 font-bold">Prediction Details:</h1>
                                        <table class="w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border p-2">Product Name</th>
                                                    <th class="border p-2">Predicted Sold Quantities</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in row.items" :key="index"
                                                    :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'">
                                                    <td class="px-2">{{ item.item_name }}</td>
                                                    <td class="text-center">{{ item.predicted_units }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </template>
                            </td>
                        </tr>
                    </transition>

                </template>

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
// Track expanded rows
const expandedRows = ref([]);
const itemDetails = ref({})
const showEye = ref(true)
const predictionDetails = ref(false)
// Reactive predictions data
const predictions = ref([]);
const toggleRow = (id) => {
    if (expandedRows.value.includes(id)) {
        expandedRows.value = expandedRows.value.filter((rowId) => rowId !== id);
    } else {
        expandedRows.value.push(id);
    }
};
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

// Function to show the details of each prediction
const showDetails = (item) => {
    showEye.value = false
    predictionDetails.value = true
    itemDetails.value = item
    console.log("itemDetails: ", itemDetails.value)
}
// Watch for changes in predictionResult and update
watch(
    () => props.predictionResult,
    (newResult) => {
        const formattedPrediction = newResult.map((result) => ({
            ds: new Date(result.ds).toISOString().split("T")[0],
            items: result.items,
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
<style scoped>
.expand-enter-active,
.expand-leave-active {
    transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
}

.expand-enter-to,
.expand-leave-from {
    max-height: 100px;
    opacity: 1;
}
</style>
