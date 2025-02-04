<template>
    <div class="calculator max-w-sm mx-auto p-4 border rounded shadow-md">
        <h2 class="text-center text-lg font-bold mb-4">Vue Calculator</h2>
        <div class="flex items-center justify-between mb-4">
            <input
                v-model.number="num1"
                type="number"
                placeholder="Enter first number"
                class="w-1/2 p-2 border rounded mr-2"
            />
            <select v-model="operator" class="p-2 border rounded">
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">×</option>
                <option value="/">÷</option>
            </select>
            <input
                v-model.number="num2"
                type="number"
                placeholder="Enter second number"
                class="w-1/2 p-2 border rounded ml-2"
            />
        </div>
        <button
            @click="calculate"
            class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
        >
            Calculate
        </button>
        <p v-if="result !== null" class="mt-4 text-center text-lg">
            Result: <span class="font-bold">{{ result }}</span>
        </p>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const num1 = ref(0);
const num2 = ref(0);
const operator = ref('+');
const result = ref(null);

const calculate = () => {
    switch (operator.value) {
        case '+':
            result.value = num1.value + num2.value;
            break;
        case '-':
            result.value = num1.value - num2.value;
            break;
        case '*':
            result.value = num1.value * num2.value;
            break;
        case '/':
            result.value =
                num2.value !== 0 ? num1.value / num2.value : 'Cannot divide by zero';
            break;
        default:
            result.value = 'Invalid operator';
    }
};
</script>

<style scoped>
.calculator {
    font-family: Arial, sans-serif;
}
</style>
