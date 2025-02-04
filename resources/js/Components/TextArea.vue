<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});
const props = defineProps({
    modelValue: String,
    rows: {
        type: Number,
        default: 3
    },
    cols: {
        type: Number,
        default: 30
    }
})
const emit = defineEmits(['update:modelValue'])

const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}

</script>

<template>
    <textarea
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm"
        :value="modelValue" @input="updateValue" :rows="rows" :cols="cols"></textarea>
</template>
