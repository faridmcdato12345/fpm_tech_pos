<template>

    <Head title="Voided Item" />
    <AuthenticatedLayout>
        <BreadCrumb title="Sales" :bread-crumbs="breadCrumbs" />

        <DataTable :data="props.sales.data" :columns="columns" :pagination="props.sales" :route-edit="editRoute"
            :route-delete="deleteRoute" @limit-query="limitQuery" @search-field-query="searchFieldQuery"
            :query-limit="props.queryLimit" :query-name="props.queryName">
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
import { Head, router, usePage } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { onMounted, ref } from 'vue';
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';


const props = defineProps({
    sales: {
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
    { id: 1, name: 'excel', url: route('voided_item.excel') },
    { id: 2, name: 'pdf', url: route('voided_item.pdf') }
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
    { label: 'PRICE', key: 'price' },
    { label: 'EXTENDED', key: 'extended' },
])
const breadCrumbs = ref([
    { label: 'Voided Items', route: route('sale.index'), id: 1 }
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
    router.get(route('voided_item.index', params))
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
    router.get(route('voided_item.index', params))
}

</script>
<style lang="">

</style>