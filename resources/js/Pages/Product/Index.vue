<template>

    <Head title="Products" />
    <AuthenticatedLayout>
        <BreadCrumb title="Products" :bread-crumbs="breadCrumbs">
            <Link :href="route(createRoute)">
            <PrimaryButton @click="showModal = true">Create</PrimaryButton>
            </Link>
        </BreadCrumb>
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.products.data" :columns="columns" :pagination="props.products.meta"
                :route-edit="editRoute" :route-delete="deleteRoute" @limit-query="limitQuery"
                @search-field-query="searchFieldQuery" @delete-product="deleteProduct" @edit-product="editProduct"
                :query-limit="props.queryLimit" :route-create="createRoute" :query-name="props.queryName"
                :action="true" />
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { ref } from 'vue';
import toast from '@/Composable/toast'
import PrimaryButton from '@/Components/PrimaryButton.vue';

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

const showModal = ref(false)
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'PRODUCT NAME', key: 'product_name' },
    { label: 'BRAND', key: 'brand.name' },
    { label: 'CATEGORY', key: 'category.name' },
    { label: 'UNIT', key: 'unit.name' },
    { label: 'REORDER LEVEL', key: 'reorder_level' },
    { label: 'CREATED AT', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'List of all products', route: route('product.index'), id: 1 }
])
const editRoute = ref('product.edit')
const deleteRoute = ref('product.destroy')
const createRoute = ref('product.create')

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
    router.get(route('product.index', params))
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
    router.get(route('product.index', params))
}
const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('product.destroy', id), {
            onSuccess: () => {
                toast.add({
                    message: "Product has been deleted.",
                    status: true
                })
            }
        })
    }

}
const editProduct = (id) => {
    console.log(id)
    console.log(router.get(route('product.edit', id)))
    router.get(route('product.edit', id))
}
</script>
<style lang="">

</style>