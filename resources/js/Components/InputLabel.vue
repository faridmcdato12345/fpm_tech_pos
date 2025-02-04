<script setup>
const props = defineProps({
    value: {
        type: String,
    },
    textSize: {
        type: String,
        default: 'text-sm'
    }
});
</script>

<template>
    <label class="block font-medium text-white dark:text-gray-300" :class="props.textSize">
        <span v-if="value">{{ props.value }}</span>
        <span v-else>
            <slot />
        </span>
    </label>
</template>
