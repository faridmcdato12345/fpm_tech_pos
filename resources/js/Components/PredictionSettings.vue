<template>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Prediction Settings</h2>
        <form @submit.prevent="runPrediction">
            <!-- Frequency Selector -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Frequency</label>
                <select v-model="frequency" class="block w-full border rounded px-3 py-2 focus:ring focus:outline-none">
                    <option value="D">Daily</option>
                    <option value="W">Weekly</option>
                    <option value="M">Monthly</option>
                </select>
            </div>

            <!-- Periods Input -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Periods to Predict</label>
                <input type="number" v-model="periods" min="1"
                    class="block w-full border rounded px-3 py-2 focus:ring focus:outline-none"
                    placeholder="e.g., 12" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Run Prediction
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';

const frequency = ref("M"); // Default frequency: Monthly
const periods = ref(12); // Default periods: 12
const predictionResults = ref([])

const emit = defineEmits(['predictionResults'])

const runPrediction = async () => {

    try {
        // Send data to the Laravel backend
        const response = await axios.post("/ai/run-prediction", {
            frequency: frequency.value,
            periods: periods.value,
        });

        // Handle success (You can update the parent component with the predictions)
        console.log("Prediction Results:", response.data);
        emit('predictionResults', response.data.predictions)
        toast.add({
            message: "Prediction completed successfully!",
            status: true
        })
    } catch (error) {
        console.log(error)
        console.error("Prediction Error:", error);
        toast.add({
            message: "Failed to run prediction. Please try again.",
            status: false
        })
    }
};
</script>
