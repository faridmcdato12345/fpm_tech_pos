<template>
    <SearchField v-model:searchQuery="searchQuery" @update:result="setResults" @close-modal="closeModal" />
    <div class="h-96 max-h-96 overflow-y-auto border-teal-400 border-2" id="scrollable-div">
        <table
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 h-[calc(100%-1rem)] max-h-full ">
            <thead class="text-nowrap text-xs text-white uppercase bg-black dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-wrap">BARCODE</th>
                    <th scope="col" class="px-6 py-3 text-wrap">PRODUCT NAME</th>
                    <th scope="col" class="px-6 py-3 text-wrap">QUANTITY</th>
                    <th scope="col" class="px-6 py-3 text-wrap">PRICE</th>
                    <th scope="col" class="px-6 py-3 text-wrap">EXTENDED</th>
                    <th scope="col" class="px-6 py-3 text-wrap"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in formData" :key="item.id"
                    @keydown.enter.prevent.stop="keyPressEnterChangeRow" @click.prevent="tableResultClick(index)"
                    :class="selectedRow === index ? 'dark:bg-gray-600' : 'dark:bg-gray-800'"
                    class="text-nowrap bg-white border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer">
                    <td class="px-6 py-4">{{ item.barcode }}</td>
                    <td class="px-6 py-4">{{ item.name }}</td>
                    <td class="px-6 py-4">{{ item.quantity }}</td>
                    <td class="px-6 py-4">&#8369; {{ (item.selling_price).toFixed(2) }}</td>
                    <td class="px-6 py-4">&#8369; {{ (item.selling_price * item.quantity).toFixed(2) }}
                    </td>
                    <td class="px-6 py-4 text-center ">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 hover:text-red-600"
                                @click.prevent="minusSelected(index)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 hover:text-teal-400"
                                @click.prevent="addSelected(index)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 hover:text-red-900"
                                @click.prevent="deleteSelected(index)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <Quantity :show="showModalQuantity" @update:show="showModalQuantity = $event" @add-quantity="addQuantity" />
</template>

<script setup>
import SearchField from "@/Components/SearchField.vue";
import { watch, ref } from "vue";
import Quantity from "@/Components/Modal/Quantity.vue";
import { getSellingPrice } from "@/util.js";

const searchQuery = ref('');
const searchResults = ref([]);
const showModalQuantity = ref(false)
const newDataResult = ref([])
const formData = ref([])


const props = defineProps({
    resetKey: {
        type: Number,
        default: 0,
    },
})
const emit = defineEmits(['dataResult', 'completeResult'])
const setResults = (newResults) => {
    showModalQuantity.value = true
    // productName.value = newResults.product_name
    newDataResult.value = newResults
    console.log("newDataResult: ", newDataResult.value)
};
const addQuantity = (quantity) => {
    if (formData.value.length > 0) {
        let result = formData.value.find(form => form.id === newDataResult.value.id)
        if (result !== undefined) {
            result.quantity += quantity
        } else {
            console.log(result)
            formData.value.unshift({
                id: newDataResult.value.id,
                name: newDataResult.value.product_name,
                quantity: quantity,
                selling_price: getSellingPrice(newDataResult.value.product_stocks),
            });
        }
    } else {
        formData.value.push({
            id: newDataResult.value.id,
            name: newDataResult.value.product_name,
            quantity: quantity,
            selling_price: getSellingPrice(newDataResult.value.product_stocks),
        });
    }
    console.log("formdata quantity: ", formData.value)
    emit('dataResult', formData.value)
    emit('completeResult', formData.value)
    showModalQuantity.value = !showModalQuantity.value
}
const resetState = () => {
    formData.value = []
};

watch(
    () => props.resetKey,
    () => {
        resetState();
    }
);

const minusSelected = (index) => {
    if (formData.value[index].quantity > 1) {
        formData.value[index].quantity -= 1;
    } else {
        deleteSelected(index)
    }
    emit('dataResult', formData.value);
    emit('completeResult', formData.value);
};

const addSelected = (index) => {
    formData.value[index].quantity += 1;
    emit('dataResult', formData.value);
    emit('completeResult', formData.value);
};

const deleteSelected = (index) => {
    formData.value.splice(index, 1);
    emit('dataResult', formData.value);
    emit('completeResult', formData.value);
};

</script>

<style scoped></style>
