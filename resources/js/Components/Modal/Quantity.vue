<template>
    <Modal :show="show" @close="handleClose">
        <template #header>
            <div class="flex justify-center">
                <p class="text-4xl font-extrabold text-white"></p>
            </div>
        </template>
        <template #content>
            <!-- <div>
                <label for="quantity" class="mb-4">
                    <p class="text-2xl font-black text-center mb-4">Quantity</p>
                </label>
                <input type="number" class="w-full quantity-modal text-8xl text-center text-black"
                    v-model="productQuantity" placeholder="0" ref="quantityInput">
            </div> -->
            <div class="quantity-keypad p-4 text-center">
                <h2 class="text-lg font-bold mb-4 uppercase">Quantity</h2>

                <!-- Display Quantity -->
                <div class="flex justify-center items-center mb-6">
                    <button class="px-4 py-2 text-xl font-bold bg-gray-900 rounded" @click="decrementQuantity">
                        -
                    </button>
                    <!-- <span class="text-3xl font-bold mx-4">{{ quantity }}</span> -->
                    <input type="number" class="w-full quantity-modal text-3xl font-bold mx-4 text-center text-black"
                        v-model="quantity" placeholder="0" ref="quantityInput" @keyup.enter="addQuantity">
                    <button class="px-4 py-2 text-xl font-bold bg-gray-900 rounded" @click="incrementQuantity">
                        +
                    </button>
                </div>

                <!-- Numeric Keypad -->
                <div class="grid grid-cols-3 gap-4">
                    <button v-for="key in keys" :key="key.label"
                        :class="['px-4 py-2 text-xl font-bold', key.special ? 'bg-gray-800' : 'bg-gray-900']"
                        @click="handleKey(key.label)">
                        {{ key.label }}
                    </button>
                </div>

                <!-- Actions -->
                <!-- <div class="flex justify-between mt-6">
                    <button class="px-6 py-2 text-lg font-bold bg-gray-400 text-white rounded" @click="cancel">
                        Cancel
                    </button>
                    <button class="px-6 py-2 text-lg font-bold bg-red-500 text-white rounded" @click="update">
                        Update
                    </button>
                </div> -->
            </div>
        </template>
        <template #footer>
            <div class="flex justify-between">
                <PrimaryButton class="text-4xl" @click.prevent="addQuantity">ADD
                </PrimaryButton>
            </div>
        </template>
    </Modal>

</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { ref, watch, nextTick } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    closeable: {
        type: Boolean,
        default: true,
    },
})
const productQuantity = ref('')
const quantity = ref(0);
const quantityInput = ref(null);
const emit = defineEmits(['update:show', 'addQuantity'])
const handleClose = () => {
    if (props.closeable) {
        emit("update:show", false)
        quantity.value = ''
    }
}

const addQuantity = () => {
    emit('addQuantity', quantity.value)
    quantity.value = ''
}
// Watch `show` prop to set focus after DOM updates
watch(
    () => props.show,
    async (newVal) => {
        if (newVal) {
            await nextTick(); // Wait for DOM updates
            if (quantityInput.value) {
                quantityInput.value.focus(); // Set focus on the input
            }
        }
    }
);

const keys = [
    { label: 1 }, { label: 2 }, { label: 3 },
    { label: 4 }, { label: 5 }, { label: 6 },
    { label: 7 }, { label: 8 }, { label: 9 },
    { label: '.' }, { label: 0 }, { label: '\u232B', special: true } // Special for backspace
];

const incrementQuantity = () => {
    quantity.value++;
};

const decrementQuantity = () => {
    if (quantity.value > 0) {
        quantity.value--;
    }
};

const handleKey = (key) => {
    if (key === '\u232B') {
        // Backspace
        quantity.value = quantity.value.toString().slice(0, -1) || '0';
    } else if (key === '.') {
        // Decimal point
        if (!quantity.value.toString().includes('.')) {
            quantity.value = `${quantity.value}.`;
        }
    } else {
        // Numbers
        if (quantity.value === 0 || quantity.value === '0') {
            quantity.value = key;
        } else {
            quantity.value = `${quantity.value}${key}`;
        }
    }
    quantity.value = parseFloat(quantity.value) || 0;
};

const cancel = () => {
    console.log('Cancel pressed');
};

const update = () => {
    console.log('Updated quantity:', quantity.value);
};
</script>

<style scoped>
.quantity-keypad {
    max-width: 400px;
    margin: auto;
}
</style>
