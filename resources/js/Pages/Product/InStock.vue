<template>

    <Head title="In Stock Products" />
    <AuthenticatedLayout>
        <BreadCrumb title="In Stock Products" :bread-crumbs="breadCrumbs">
        </BreadCrumb>
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.products.data" :columns="columns" :pagination="props.products.meta"
                @limit-query="limitQuery" @search-field-query="searchFieldQuery" :query-limit="props.queryLimit"
                :query-name="props.queryName">
                <template #export>
                    <ExportButton :links="exportLink" />
                </template>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import ExportButton from '@/Components/ExportButton.vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { ref } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    },
    queryLimit: {
        type: Number,
        required: true,
        default: 10
    },
    queryName: {
        type: String,
        required: true,
        default: null
    }
})
const exportLink = [
    { id: 1, name: 'excel', url: route('in_stock.excel') },
    { id: 2, name: 'pdf', url: route('in_stock.pdf') }
]
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'PRODUCT NAME', key: 'product_name' },
    { label: 'CATEGORY', key: 'category' },
    { label: 'UNIT', key: 'unit' },
    { label: 'QUANTITY', key: 'quantity' },
])
const breadCrumbs = ref([
    { label: 'Product', route: route('product.index'), id: 1 }
])
const limitQuery = (name, e) => {
    let qParams = {}
    let nParams = {}
    let params = {}
    if (props.queryName) {
        nParams['name'] = props.queryName
        qParams[name] = e

        params = { ...qParams, ...nParams }
    } else if (!props.queryName) {
        qParams[name] = e
        params = qParams
    } else {
        delete qParams[name]
        delete nParams['name']
    }
    router.get(route('in_stock.index', params))
}
const searchFieldQuery = (name, e) => {
    let qParams = {}
    let nParams = {}
    let params = {}
    if (props.queryLimit) {
        nParams[name] = e
        qParams['query'] = props.queryLimit

        params = { ...nParams, ...qParams }
    } else if (!props.queryLimit) {
        nParams[name] = e
        params = nParams
    } else {
        delete qParams[name]
        delete nParams['name']
    }
    router.get(route('in_stock.index', params))
}
</script>
<style lang="">

</style>