<template>
    <div v-for="list in props.lists" :key="list.id"
        class="max-w-full p-6  border border-teal-400 dark:bg-gray-800 dark:border-teal-400 shadow-inner shadow-teal-400">
        <div class="flex justify-between items-center">
            <div class="w-full">
                <div class="flex justify-center items-center">
                    <img :src="`/assets/icons/svg/${list.icon}.svg`" width="100" height="150" />
                </div>

            </div>
            <div class="text-right text-nowrap">
                <a href="#">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight  text-white">{{ list.label }}
                    </h5>
                </a>
                <p class="mb-3 font-normal text-gray-400">{{ list.description }}</p>
                <p class="mb-3 font-bold text-teal-400 text-4xl">{{ list.value }}</p>
                <Link :href="list.link"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-teal-700 rounded-lg hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-teal-400 dark:hover:bg-teal-700 dark:focus:ring-blue-800 hover:cursor-pointer">
                Show more
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
                </Link>
            </div>

        </div>


    </div>
</template>

<script setup>

import { Link } from '@inertiajs/vue3';

const props = defineProps({
    lists: {
        type: Array,
    }
})

</script>

<style lang="scss" scoped></style>
