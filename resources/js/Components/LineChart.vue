<template>
    <div>
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { Chart, registerables } from "chart.js";

// Register required components for Chart.js
Chart.register(...registerables);

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({}), // Optional: Chart options for customization
    },
});

const chartCanvas = ref(null);
let chartInstance = null;

onMounted(() => {
    createChart();
});

watch(
    () => props.data,
    () => {
        if (chartInstance) {
            chartInstance.destroy(); // Destroy old chart before updating
        }
        createChart();
    }
);

const createChart = () => {
    if (!chartCanvas.value) return;

    chartInstance = new Chart(chartCanvas.value, {
        type: "line",
        data: props.data,
        options: props.options,
    });
};
</script>

<style scoped>
div {
    position: relative;
    height: 400px;
    /* Adjust height as needed */
    width: 100%;
}
</style>
