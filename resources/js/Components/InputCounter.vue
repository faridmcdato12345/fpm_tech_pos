<template>
    <div class="items-center border border-gray-300 rounded-full p-2 bg-gray-900 w-6/12">
        <div class="flex justify-center items-center">
            <svg @click.prevent="decrement" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="w-full hover:cursor-pointer hover:text-teal-400">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <input type="text" name="" id="" :value="count" @input="updateCount($event.target.value)"
                class="text-center border-none rounded-full w-full focus:outline-none focus:ring-0 bg-gray-900 p-0">
            <svg @click.prevent="increment" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="w-full hover:cursor-pointer hover:text-teal-400">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
        </div>
    </div>
</template>

<script setup>
import TextInput from './TextInput.vue';
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: Number
})

const emit = defineEmits(['update:modelValue'])
const count = ref(props.modelValue)

const increment = () => {
    count.value++
    emit('update:modelValue', count.value)
}

const decrement = () => {
    if (count.value > 0) {
        count.value--
        emit('update:modelValue', count.value)
    }
}
const updateCount = (value) => {
    count.value = parseInt(value) || 0
    emit('update:modelValue', count.value)
}
watch(() => props.modelValue, (newValue) => {
    count.value = newValue
})
</script>
