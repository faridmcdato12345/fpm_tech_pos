<template>
    <div class="dark:bg-slate-300 p-4 rounded-md">
        <div class="dark:bg-white w-full border-separate border-spacing-4 py-4 px-4">
            <table class="mb-4 w-full">
                <thead class="font-bold text-lg border-b text-center">
                    <th>Poduct</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Unit</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ props.product.product_name }}</td>
                        <td>{{ props.product.sku }}</td>
                        <td class="flex justify-center">
                            <InputCounter v-model="newQuant" />
                        </td>
                        <td>{{ props.product.brand.name }}</td>
                        <td>{{ props.product.category.name }}</td>
                        <td>{{ props.product.unit.name }}</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <div class="text-left mt-4" v-if="props.moreOption">
                <div class="flex justify-between space-x-4">
                    <div class="w-full">
                        <label for="" class="text-white font-medium dark:text-gray-900">Purchased
                            Price</label>
                        <input v-model="purchasedPrice"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 text-black dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />

                    </div>
                    <div class="w-full">
                        <label for="" class="text-white font-medium dark:text-gray-900">Selling
                            Price</label>
                        <input v-model="sellingPrice"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 text-black dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />

                    </div>
                </div>
                <div>
                    <label for="" class="text-white font-medium dark:text-gray-900">Expiration
                        Date</label>
                    <input type="date" v-model="expirationDate"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 text-black dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                </div>


            </div>
        </div>

    </div>
</template>

<script setup>
import InputCounter from "@/Components/InputCounter.vue";
import { computed } from "vue";
import { ref, watch } from "vue"


const props = defineProps({
    product: Object,
    quantity: {
        type: Number,
        default: 0
    },
    moreOption: {
        type: Boolean,
        default: true
    }
})
const quantity = ref(0)
const expirationDate = ref("")
const sellingPrice = ref(0);
const purchasedPrice = ref(0);
const emit = defineEmits(['quantity', 'expiration-date', 'product-details'])
watch(() => quantity.value, (newValue) => {
    emit('quantity', newValue)
})

if (props.moreOption) {
    // Watch for changes in sellingPrice, purchasedPrice, and expirationDate
    watch([sellingPrice, purchasedPrice, expirationDate], ([newSellingPrice, newPurchasedPrice, newExpirationDate]) => {
        emit("product-details", {
            "new_selling_price": newSellingPrice,
            "new_purchase_price": newPurchasedPrice,
            "new_expiration_date": newExpirationDate
        });
    });
}


const newQuant = computed({
    get() {
        return props.quantity
    },

    set(val) {
        quantity.value = 0
        emit('quantity', val)
    }
})
</script>

<style lang="scss" scoped></style>
