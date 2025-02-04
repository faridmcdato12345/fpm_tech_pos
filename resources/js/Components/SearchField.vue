<template>
    <div>
        <input type="text" v-model="searchQuery" @input="handleInput" @keydown.up.prevent="moveUp"
            @keydown.enter.prevent="selectItem" @keydown.down.prevent="moveDown" placeholder="Please type product name and select"
            autocomplete="off" autofocus
            class="w-full search-field block p-2 ps-10 text-xl text-gray-900 border-2 border-teal-400 bg-gray-50 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-teal-400 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500">
        <div v-if="results.length" id="default-modal" tabindex="-1" aria-hidden="true"
            class=" flex modal-n overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-vh min-h-vh">
            <div class="">
                <div class="relative p-4 w-full h-40">
                    <!-- Modal content -->
                    <div class="relative bg-black  border border-teal-400 shadow dark:bg-gray-800 ">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b dark:border-gray-600 border-teal-400">
                            <h3 class="text-xl font-semibold text-gray-900 text-white">
                                Products
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900  text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="default-modal" @click.prevent="closeModal"
                                @keydown.prevent.esc="closeModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only asdf">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <table class="w-full" ref="modalTable">
                                <thead class="text-sm text-black uppercase bg-gray-100 dark:bg-gray-700 dark:text-white border border-teal-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-wrap">PRODUCT NAME</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">BRAND</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">CATEGORY</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">UNIT</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">PRICE</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">NEED PRESCRIPTION</th>
                                        <th scope="col" class="px-6 py-3 text-wrap">QUANTITY</th>

                                    </tr>
                                </thead>
                                <tbody class="overflow-y-auto">
                                    <tr v-for="(result, index) in results" :key="result.id"
                                        :class="selectedIndex === index ? 'dark:bg-gray-600' : 'dark:bg-gray-800'"
                                        @click.prevent="selectItemByClick(index)"
                                        class="text-nowrap bg-white border-b dark:text-white text-gray-900 dark:border-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 hover:cursor-pointer hover:text-white">
                                        <td class="px-6 py-4 text-center">{{ result.product_name }}</td>
                                        <td class="px-6 py-4 text-center">{{ result.brands?.name }}</td>
                                        <td class="px-6 py-4 text-center">{{ result.categories?.name }}</td>
                                        <td class="px-6 py-4 text-center">{{ result.units?.name }}</td>
                                        <td class="px-6 py-4 text-center">&#8369; {{
                                            result.product_stocks[0]?.selling_price }}</td>
                                        <td class="px-6 py-4 text-center">{{ result.is_prescription ? 'Yes' : 'No'}}</td>
                                        <td class="px-6 py-4 text-center">
                                            {{ result.total_quantity ?? 'Out of stock' }}
                                        </td>


                                    </tr>
                                    <tr v-if="results.length === 0"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">No results found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios';

const searchQuery = ref('')
const results = ref([]);
const selectedIndex = ref(-1)

const props = defineProps({
    inputFocus: {
        type: Boolean,
        default: true,
    }
})

const emit = defineEmits(['update:seaerchQuery', 'results', 'select', 'update:result', 'closeModal'])

const handleInput = () => {
    emit('update:seaerchQuery', searchQuery.value)
}
watch(searchQuery, async (newQuery) => {
    if (newQuery) {
        const response = await axios.get('/search', {
            params: { query: newQuery }
        });
        const d = response.data
        results.value = response.data;
        console.log(results.value)
        selectedIndex.value = -1;
        emit('results', results.value);
    } else {
        results.value = [];
        selectedIndex.value = -1;
        emit('results', results.value);
    }
});
const moveUp = () => {
    if (selectedIndex.value > 0) {
        selectedIndex.value -= 1;
    } else {

        ;
    }
};

const moveDown = () => {
    if (selectedIndex.value < results.value.length - 1) {
        selectedIndex.value += 1;
    } else {
        selectedIndex.value = 0;
    }
};
const selectItemByClick = (id) => {
    selectResult(results.value[id]);
}
const selectItem = () => {
    if (selectedIndex.value >= 0) {
        selectResult(results.value[selectedIndex.value]);
    }
};
const closeModal = () => {
    searchQuery.value = '';
    results.value = []
    emit('closeModal', false)
}
const selectResult = (result) => {
    console.log(result)
    searchQuery.value = '';
    results.value = [];
    selectedIndex.value = -1;
    emit('update:result', result);
};
</script>

<style scoped>
.search-input {
    width: 100%;
    padding: 0.5em;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.dropdown {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
}

.dropdown ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.dropdown li {
    padding: 0.5em;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.dropdown li.selected {
    background-color: #f0f0f0;
}

.modal-n {
    background-color: rgb(31 41 55 / 0.9%)
}
</style>
