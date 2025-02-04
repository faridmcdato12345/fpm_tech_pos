<template lang="">
    <Head title="Company Details" />
    <AuthenticatedLayout >
        <BreadCrumb title="Company Details" :bread-crumbs="breadCrumbs">
            <Link :href="route(createRoute)">
                <PrimaryButton @click="showModal = true">Create</PrimaryButton>
            </Link>
        </BreadCrumb>
        <DataTable
            :data="props.companies"
            :columns="columns"
            :route-edit="editRoute"
            :route-delete="deleteRoute"
            @limit-query="limitQuery"
            @search-field-query="searchFieldQuery"
            @delete-product="deleteProduct"
            @edit-product="editProduct"
            :query-limit="props.queryLimit"
            :route-create="createRoute"
            :query-name="props.queryName"
            :action="true"
        />
        <Toast />
</AuthenticatedLayout>
</template>
<script setup>
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
    companies: {
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

onMounted(() => {
    console.log(props.companies)
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
    { label: 'LOGO', key: 'company_logo' },
    { label: 'NAME', key: 'name' },
    { label: 'ADDRESS', key: 'address' },
    { label: 'EMAIL', key: 'email' },
    { label: 'MOBILE NUMBER', key: 'mobile_number' },
])
const breadCrumbs = ref([
    { label: 'Company', route: route('company.index'), id: 1 }
])
const editRoute = ref('company.edit')
const deleteRoute = ref('company.destroy')
const createRoute = ref('company.create')

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
    router.get(route('company.index', params))
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
    router.get(route('company.index', params))
}
const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete the company details?')) {
        router.delete(route('company.destroy', id), {
            onSuccess: () => {
                toast.add({
                    message: "Company details has been deleted.",
                    status: true
                })
            }
        })
    }

}
const editProduct = (id) => {
    router.get(route('company.edit', id))
}
</script>
<style lang="">

</style>