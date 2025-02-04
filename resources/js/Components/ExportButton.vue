<template>
    <div class="relative">
        <button @click.prevent="clickToggle" id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
            class="text-white bg-teal-400 hover:bg-teal-500 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-teal-400 dark:hover:bg-teal-700 dark:focus:ring-teal-800"
            type="button">Export <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>
        <div v-if="dropDown">
            <div v-if="fromTo"
                class="z-10 w-max py-2 px-2 absolute mt-2 bg-white divide-gray-100 left-0 right-0 rounded-lg shadow dark:bg-teal-400">
                <div class="flex space-x-4 items-center justify-center">
                    <div class="space-x-2 items-center justify-center">
                        <label for="from">Date Range: </label>
                        <Datepicker @update-date="getDatePick" />
                    </div>
                </div>
                <div class="space-y-2 mt-4">
                    <div v-for="link in links" :key="link.id" class="w-full dark:bg-teal-700 rounded-md ">
                        <a :href="link.url + `?from=${fromDate}&to=${toDate}`"
                            class="text-center block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            {{ link.name.toUpperCase() }}</a>
                    </div>
                </div>
            </div>
            <!-- Dropdown menu -->
            <div v-else>
                <div id="dropdown"
                    class="z-10 absolute mt-2 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-teal-400">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        <li v-for="link in links" :key="link.id">
                            <a :href="link.url"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                {{ link.name.toUpperCase() }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--- date picker-->

    </div>
</template>

<script setup>
import { ref } from 'vue'
import Datepicker from './Datepicker.vue';
import { format } from 'date-fns';
const dropDown = ref(false)
const fromDate = ref('')
const toDate = ref()


const clickToggle = () => {
    dropDown.value = !dropDown.value
}
const getDatePick = (date) => {
    fromDate.value = format(new Date(date[0]), 'yyyy-MM-dd')
    toDate.value = format(new Date(date[1]), 'yyyy-MM-dd')
}

const props = defineProps({
    links: {
        type: Array,
        default: () => { }
    },
    fromTo: {
        type: Boolean,
        default: false
    }
})


</script>

<style lang="scss" scoped></style>