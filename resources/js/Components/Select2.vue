<template>
    <div class="relative w-full">
        <div @click="toggleDropdown" class="border rounded px-4 py-2 cursor-pointer bg-white">
            <span v-if="selectedOptions.length">
                {{ isMultiple ? selectedOptions.join(', ') : selectedOptions }}
            </span>
            <span v-else class="text-gray-400">Select an option</span>
            <span class="float-right">▼</span>
        </div>
        <div v-if="isOpen" class="absolute mt-1 w-full bg-white border rounded shadow-lg z-10 max-h-60 overflow-y-auto">
            <input type="text" v-model="search" class="w-full px-4 py-2 border-b focus:outline-none"
                placeholder="Search..." />
            <ul>
                <li v-for="option in filteredOptions" :key="option.value" @click="selectOption(option)"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-left">
                    <p class="uppercase text-lg font-bold">{{ option.company }}</p>
                    <p class="capitalize text-md">{{ option.name }}</p>
                </li>
                <li v-if="!filteredOptions.length" class="px-4 py-2 text-gray-400">
                    No options found
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

// Props
const props = defineProps({
    options: {
        type: Array,
        required: true,
        default: () => []
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    resetKey: {
        type: Number,
        default: 0,
    },
});
console.log(props.options)
// State
const isOpen = ref(false);
const search = ref('');
const selectedOptions = ref([]);

const emit = defineEmits(['selectedCustomer'])

// Computed properties
const filteredOptions = computed(() => {
    return props.options.filter(option =>
        option.company.toLowerCase().includes(search.value.toLowerCase())
    );
});

// Methods
function toggleDropdown() {
    isOpen.value = !isOpen.value;
}

function selectOption(option) {
    if (props.multiple) {
        if (!selectedOptions.value.includes(option.company)) {
            selectedOptions.value.push(option.company);
        } else {
            selectedOptions.value = selectedOptions.value.filter(
                item => item !== option.company
            );
        }
    } else {
        selectedOptions.value = option.company;
        isOpen.value = false;
    }
    emit('selectedCustomer', option.id)
    search.value = '';
}

document.addEventListener('click', event => {
    const target = event.target;
    if (!target.closest('.relative')) {
        isOpen.value = false;
    }
});
const resetState = () => {
    selectedOptions.value = []
};

watch(
    () => props.resetKey,
    () => {
        resetState();
    }
);
</script>

<style>
/* Add TailwindCSS or your custom styles for better design */
</style>
