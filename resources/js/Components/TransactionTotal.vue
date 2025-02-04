<template>
    <div
        class="absolute border-teal-400 border-2 pt-8 rounded-xl w-full h-2/6 min-h-2/6 max-h-2/6 bottom-0 text-teal-400">
        <div class="relative">
            <div class="absolute dark:bg-gray-900 -top-14 left-2 p-2">
                <p>Transaction Total</p>
            </div>
            <div class="p-4 border-b-2">
                <div class="flex justify-between">
                    <p class="text-xl font-black">Sub Total:</p>
                    <p class="text-xl font-black">&#8369; {{ items.length === 0 ? 0 : subTotal.toFixed(2) }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-xl font-black">Transaction Discount:</p>
                    <p class="text-xl font-black">&#8369; {{ items.length === 0 ? 0 : props.discounts }}</p>
                </div>
            </div>
            <div class="p-2 flex justify-between">
                <p class="text-2xl font-black">TOTAL:</p>
                <p class="text-2xl font-black">&#8369; {{ items.length === 0 ? 0 : grandtotal.toFixed(2) }}</p>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, watch } from "vue"

const props = defineProps({
    items: {
        type: Array,
        reqruired: true,
        default: () => []
    },
    discounts: Number,
    reset: {
        type: Boolean,
        default: false
    }
})
console.log(props.items)
const subTotal = ref(0)
const grandtotal = ref(0)
const localItems = ref([...props.items])

// watch([props.items, () => props.discounts, () => props.reset],
//     ([newItems, newDiscount, newReset]) => {
//         if (newItems && newDiscount) {
//             console.log(newItems)
//             console.log(newDiscount)
//             localItems.value = [...newItems]
//             subTotal.value = localItems.value.reduce((amount, amountPerItem) => {
//                 return amount + (amountPerItem.selling_price * amountPerItem.quantity)
//             }, 0)
//             let multAmount = subTotal.value * newDiscount
//             grandtotal.value = subTotal.value - multAmount
//         } else {
//             console.log("newItem: ", newItems)
//             localItems.value = [...newItems]
//             console.log("localItems.value: ", localItems.value)
//             subTotal.value = localItems.value.reduce((amount, amountPerItem) => {
//                 return amount + (amountPerItem.selling_price * amountPerItem.quantity)
//             }, 0)
//             grandtotal.value = subTotal.value
//         }
//     }
// )
watch(
    [() => props.items, () => props.discounts],
    ([newItems, newDiscounts], [oldItems, oldDiscounts]) => {
        console.log('Items changed: ', newItems)
        console.log('newDiscounts: ', newDiscounts)
        localItems.value = [...newItems]
        console.log("localItems.value: ", localItems.value[0])
        console.log("localItems.value1: ", localItems.value[1])
        subTotal.value = localItems.value.reduce((amount, amountPerItem) => {
            return amount + (amountPerItem.selling_price * amountPerItem.quantity)
        }, 0)
        if (localItems.value[1] != 0) {
            let multAmount = subTotal.value * newDiscounts
            grandtotal.value = subTotal.value - multAmount
        } else {
            grandtotal.value = subTotal.value
        }

    }, { deep: true }
)
</script>

<style lang="scss" scoped></style>