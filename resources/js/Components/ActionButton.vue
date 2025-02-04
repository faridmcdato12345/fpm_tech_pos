<template>
    <div class="relative border-2 pt-6 border-teal-400 text-sm">
        <div class="absolute -top-3 bg-black dark:bg-gray-900 p-1 left-2 text-teal-400">
            <p>Hotkeys</p>
        </div>
        <div class="p-2">
            <div v-for="list in lists" :key="list.name" class="mb-2">
                <PrimaryButton @click="clicked(list.modal)" class="w-full">
                    <div class="text-xs">
                        <div>{{ list.label }}</div>
                        <div class="text-gray-700">({{ list.hotkey }})</div>
                    </div>
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from './PrimaryButton.vue';


const props = defineProps({
    lists: Array
})
const emit = defineEmits([
    'changeQuantity',
    'payment',
    'changePrice',
    'itemDiscount',
    'discount',
    'recall',
    'holdTransaction',
    'retrieveTransaction',
    'registerAmount'
])
const clicked = (e) => {
    emit('buttonClicked', e)
}
</script>

<style lang="scss" scoped></style>
