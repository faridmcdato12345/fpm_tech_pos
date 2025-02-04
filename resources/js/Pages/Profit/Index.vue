<template>

    <Head title="Profits" />
    <AuthenticatedLayout>
        <BreadCrumb title="Profits" :bread-crumbs="breadCrumbs" />

        <DataTable :data="props.profits.data" :columns="columns" :pagination="props.profits" @limit-query="limitQuery"
            @search-field-query="searchFieldQuery" :query-limit="props.queryLimit" :query-name="props.queryName">
            <template #export>
                <ExportButton :links="exportLink" :fromTo="true" />
            </template>
        </DataTable>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import ExportButton from '@/Components/ExportButton.vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import DataTable from '@/Components/DataTable.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps({
    profits: {
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
const page = usePage()
const exportLink = [
    { id: 1, name: 'excel', url: route('profit.excel') },
    { id: 2, name: 'pdf', url: route('profit.pdf') }
]
onMounted(() => {
    if (page.props.toast) {
        toast.add({
            message: page.props.toast.message,
            status: page.props.toast.status
        })
    }
})

const showModal = ref(false)
const columns = ref([
    { label: 'INVOICE NUMBER', key: 'invoice_number' },
    { label: 'PRODUCT NAME', key: 'product_name' },
    { label: 'QUANTITY', key: 'quantity' },
    { label: 'SELLING PRICE', key: 'selling_price' },
    { label: 'PURCHASED PRICE', key: 'purchased_price' },
    { label: 'SUB PROFIT', key: 'profit' },
])
const breadCrumbs = ref([
    { label: 'Total Profits', route: route('profit.index'), id: 1 }
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
    router.get(route('profit.index', params))
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
    router.get(route('profit.index', params))
}

</script>
<style lang="">

</style>