<template>
    <div>
        <input type="text" v-model="searchQuery" @change="performSearch" placeholder="Search product name"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-black dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
        <ul v-if="results.length" class="dark:bg-white mt-2 rounded-md h-1/4 max-h-48 overflow-y-auto  ">
            <li v-for="result in results" :key="result.id" @click.prevent="selectResult(result)"
                class="hover:bg-slate-400 hover:cursor-pointer border-b text-left px-4 py-2">
                <div class="text-xl font-bold dark:text-teal-400">
                    {{ result.product_name }}
                </div>
                <div>
                    {{ result.category?.name }} &#8226; {{ result.brand?.name }} &#8226; {{
                        result.unit?.name
                    }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import useSearch from '@/Composable/useSearch';
import { onMounted, ref } from 'vue';
const emit = defineEmits(['productResult'])
const { searchQuery, results, performSearch } = useSearch()
const selectedResult = ref(null)
const selectResult = (result) => {
    results.value = []
    selectedResult.value = result
    emit('productResult', result)
}
</script>

<style lang="scss" scoped></style>
