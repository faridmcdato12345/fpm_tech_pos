<template>
    <div class="w-full bottom-2 fixed">
        <div class="w-full">
            <div class="dark:bg-teal-400 w-[99%] mx-auto p-4 rounded-xl">
                <div v-if="lastScanItemName" class="flex justify-between">
                    <div class="text-6xl font-black">
                        {{ lastScanItemName }}
                    </div>
                    <div v-if="lastScanItemPrice > 0" class="text-6xl font-black">
                        &#8369; {{ lastScanItemPrice.toFixed(2) }}
                    </div>
                </div>
                <div v-else class="flex justify-center">
                    <div class="text-6xl font-black text-center animate-pulse">
                        NO ITEM SCAN YET
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    lastScanItemName: {
        type: String,
        default: '',
    },
    lastScanItemPrice: {
        type: Number,
        default: 0,
    },
});
</script>

<style scoped>
/* Add any custom styles for LastScannedItem here */
</style>
