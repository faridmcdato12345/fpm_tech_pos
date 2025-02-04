<template>
    <div class="relative overflow-x-auto h-screen">
        <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
            <div>
                <div :class="[$slots.export ? 'flex' : '']">
                    <div>
                        <label class="text-white">Limit to: </label>
                        <select @change="$emit('limitQuery', 'query', $event.target.value)" v-model="props.queryLimit"
                            class="rounded">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div v-if="$slots.export" class="card-header ml-4">
                        <slot name="export" />
                    </div>
                </div>
            </div>
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="text" id="table-search"
                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search for product name" @blur="$emit('searchFieldQuery', 'name', $event.target.value)"
                    @keyup.enter="$emit('searchFieldQuery', 'name', $event.target.value)" v-model="props.queryName">
            </div>
        </div>
        <div class="overflow-x">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-wrap" v-for="column in columns" :key="column.key">
                            {{ column.label }}
                        </th>
                        <th v-if="action" scope="col" class="px-6 py-3">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredData" :key="item.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td v-for="column in columns" :key="column.key" class="px-6 py-4">
                            <img :src="item.company_logo" v-if="column.key === 'company_logo'" width=100>
                            <p v-else>{{ getNestedValue(item, column.key) }}</p>
                        </td>
                        <td v-if="action" class="px-6 py-4 flex space-x-4">
                            <div class="relative">
                                <button @click="toggleDropdown(item.id)"
                                    class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                    </svg>
                                </button>
                                <div v-if="isSale">
                                    <div v-if="dropdownOpen === item.id"
                                         class="absolute right-0 z-10 bg-white rounded-md shadow-lg dark:bg-gray-700 w-max">
                                        <ul>
                                            <li class="flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>

                                                <button @click="$emit('saleDetail', item.id)"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    Sale Detail
                                                </button>
                                            </li>
                                            <li class="flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>

                                                <button @click="$emit('downloadSale', item.id)"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    Download Pdf
                                                </button>
                                            </li>
                                            <li class="flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                                <button @click="$emit('deleteSale', item.id)"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    Delete Sale
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div v-else>

                                    <div v-if="dropdownOpen === item.id"
                                         class="absolute right-0 z-10 bg-white rounded-md shadow-lg dark:bg-gray-700 w-max">
                                        <ul>
                                            <li>
                                                <button @click="$emit('editProduct', item.id)"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button @click="$emit('deleteProduct', item.id)"
                                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <Pagination :links="props.pagination.links" v-if="props.pagination" @page-changed="fetchPageData" />
    </div>
</template>
<script setup>
import EditButton from './EditButton.vue'
import DeleteButton from './DeleteButton.vue'
import { getNestedValue } from '@/util';
import Pagination from '@/Components/Pagination.vue'
import { computed, onMounted, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    action: {
        type: Boolean,
        default: false
    },
    columns: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        default: null
    },
    routeEdit: {
        type: String,
        required: true
    },
    routeDelete: {
        type: String,
        required: true
    },
    routeCreate: {
        type: String,
        required: true
    },
    queryLimit: {
        type: Number,
        required: true
    },
    queryName: {
        type: String,
        required: true
    },
    logo: {
        type: Boolean,
        default: false
    },
    isSale: {
        type: Boolean,
        default: false
    }
})
const searchQuery = ref('')
const dropdownOpen = ref(null);
const filteredData = computed(() => {
    if (!searchQuery.value) return props.data;

    return props.data.filter(item => {
        return props.columns.some(column => {
            const value = getNestedValue(item, column.key);
            return String(value).toLowerCase().includes(searchQuery.value.toLowerCase());
        });
    })
})

const fetchPageData = (page) => {
    emit('page-changed', page)
}
const toggleDropdown = (id) => {
    dropdownOpen.value = dropdownOpen.value === id ? null : id;
};

</script>
<style lang="">

</style>
