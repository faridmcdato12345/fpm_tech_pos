<template>

    <Head title="Users" />
    <AuthenticatedLayout>
        <BreadCrumb title="Users" :bread-crumbs="breadCrumbs" />
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.users" :columns="columns" :pagination="props.users" :route-edit="editRoute"
                :route-delete="deleteRoute" @limit-query="limitQuery" @search-field-query="searchFieldQuery"
                :query-limit="props.queryLimit" :query-name="props.queryName" :action="true">
                <template #export>
                    <ExportButton :links="exportLink" />
                </template>
            </DataTable>
        </div>
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
    users: {
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
    { id: 1, name: 'excel', url: route('users.excel') },
    { id: 2, name: 'pdf', url: route('users.pdf') }
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
    { label: 'ID', key: 'id' },
    { label: 'FULL NAME', key: 'fullname' },
    { label: 'USERNAME', key: 'username' },
    { label: 'CREATED AT', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'Users', route: route('users.index'), id: 1 }
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
    router.get(route('users.index', params))
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
    router.get(route('users.index', params))
}

</script>
<style lang="">

</style>