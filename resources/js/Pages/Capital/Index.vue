<template>

    <Head title="Capital" />
    <AuthenticatedLayout>
        <BreadCrumb title="Capital" :bread-crumbs="breadCrumbs">
        </BreadCrumb>
        <DataTable :data="props.capitals.data" :columns="columns" :pagination="props.capitals" @limit-query="limitQuery"
            @search-field-query="searchFieldQuery" :query-limit="props.queryLimit" :query-name="props.queryName">
            <template #export>
                <ExportButton :links="exportLink" :fromTo="false" />
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
    capitals: {
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
    { id: 1, name: 'excel', url: route('capital.excel') },
    { id: 2, name: 'pdf', url: route('capital.pdf') }
]
const page = usePage()
onMounted(() => {
    console.log(props.capitals)
    if (page.props.toast) {
        toast.add({
            message: page.props.toast.message,
            status: page.props.toast.status
        })
    }
})

const showModal = ref(false)
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'PRODUCT NAME', key: 'product_name' },
    { label: 'CATEGORY', key: 'category' },
    { label: 'UNIT', key: 'unit' },
    { label: 'PURCHASED PRICE', key: 'purchased_price' },
    { label: 'EXTENDED PRICE', key: 'extended' },
])
const breadCrumbs = ref([
    { label: 'Capital', route: route('capital.index'), id: 1 }
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
    router.get(route('capital.index', params))
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
    router.get(route('capital.index', params))
}
</script>
<style lang="">

</style>