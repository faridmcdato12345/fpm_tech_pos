<script setup>
import { ref } from 'vue';

// State
const steps = ['Order', 'Delivery', 'Billing'];
const currentStep = ref(1);
const products = [
    { id: 1, name: 'Filament t-shirt' },
    { id: 2, name: 'Hoodie' },
];
const selectedProduct = ref(products[0].id);
const quantity = ref(1);
const specialNotes = ref('');
const orderItems = ref([]);

// Methods
const addToOrder = () => {
    if (!selectedProduct.value || quantity.value <= 0) return;

    const product = products.find((p) => p.id === selectedProduct.value);
    orderItems.value.push({
        product: product.name,
        quantity: quantity.value,
    });

    // Reset form
    selectedProduct.value = products[0].id;
    quantity.value = 1;
};

const removeOrderItem = (index) => {
    orderItems.value.splice(index, 1);
};

const goToNextStep = () => {
    if (currentStep.value < steps.length) {
        currentStep.value++;
    }
};
</script>

<template>
    <div class="p-8 bg-gray-800 text-white min-h-screen">
        <!-- Steps -->
        <div class="flex items-center space-x-8 mb-6">
            <template v-for="(step, index) in steps" :key="index">
                <div class="flex items-center space-x-2">
                    <div :class="[
                        'flex items-center justify-center w-10 h-10 rounded-full border-2',
                        currentStep === index + 1 ? 'border-yellow-400 text-yellow-400' : 'border-gray-600 text-gray-400',
                    ]">
                        {{ index + 1 }}
                    </div>
                    <div :class="currentStep === index + 1 ? 'text-yellow-400' : 'text-gray-400'">
                        <p class="font-bold">{{ step }}</p>
                        <p class="text-sm">{{ index === 0 ? 'Review your basket' : index === 1 ? 'Send us your address'
                            : 'Select a payment method' }}</p>
                    </div>
                </div>
                <div v-if="index < steps.length - 1" class="flex-1 border-t-2"
                    :class="currentStep > index + 1 ? 'border-yellow-400' : 'border-gray-600'"></div>
            </template>
        </div>

        <!-- Order Form -->
        <div class="bg-gray-700 p-6 rounded-md">
            <div class="grid grid-cols-3 gap-4 items-center mb-6">
                <div>
                    <label for="product" class="block text-sm">Product</label>
                    <select id="product" v-model="selectedProduct"
                        class="w-full mt-1 p-2 bg-gray-800 border border-gray-600 rounded">
                        <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="quantity" class="block text-sm">Quantity</label>
                    <input id="quantity" type="number" v-model="quantity" min="1"
                        class="w-full mt-1 p-2 bg-gray-800 border border-gray-600 rounded" />
                </div>
                <div class="flex items-center justify-center">
                    <button @click="addToOrder" class="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-600">
                        Add to order
                    </button>
                </div>
            </div>

            <!-- Order Items -->
            <div class="space-y-4">
                <div v-for="(item, index) in orderItems" :key="index"
                    class="flex justify-between items-center bg-gray-800 p-4 rounded border border-gray-600">
                    <div>
                        <p class="font-bold">{{ item.product }}</p>
                        <p class="text-sm text-gray-400">Quantity: {{ item.quantity }}</p>
                    </div>
                    <button @click="removeOrderItem(index)" class="text-red-500 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Special Notes -->
            <div class="mt-6">
                <label for="special-notes" class="block text-sm">Special order notes</label>
                <textarea id="special-notes" v-model="specialNotes"
                    class="w-full mt-1 p-2 bg-gray-800 border border-gray-600 rounded" rows="3"></textarea>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-end mt-6">
            <button @click="goToNextStep" class="bg-yellow-500 text-black px-6 py-2 rounded hover:bg-yellow-600">
                Next
            </button>
        </div>
    </div>
</template>
