<template>

    <Head title="Adjust Stock" />
    <AuthenticatedLayout>
        <BreadCrumb title="Adjust Stock" :bread-crumbs="breadCrumbs">
            <PrimaryButton @click.prevent="showModalAddNew = true">Adjust</PrimaryButton>
        </BreadCrumb>
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.products.data" :columns="columns" :pagination="props.products.meta"
                :route-edit="editRoute" :route-delete="deleteRoute" @limit-query="limitQuery"
                @search-field-query="searchFieldQuery" @delete-product="deleteProduct" @edit-product="editProduct"
                :query-limit="props.queryLimit" :route-create="createRoute" :query-name="props.queryName"
                />
        </div>
        <!---modal adjust-->
        <Modal :show="showModalAddNew" @close="modalClose">
            <template #header>
                <h5 class="text-white font-black text-lg font">Adjust Stock</h5>
            </template>
            <template #content>
                <SearchFieldAuto @product-result="showProduct" :erase-data="true" />
                <ItemDetail :product="productDetails" v-if="!isObjectEmpty" @quantity="getQuantity" :quantity="quantity"
                    :more-option="false" />
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="adjustStock">Save</PrimaryButton>
                <DeleteButton @click.prevent="modalClose" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal edit -->
        <Modal :show="showModalEdit" @close="modalClose">
            <template #header>
                <h5 class="text-white font-black text-lg font">Edit Adjusted Stock</h5>
            </template>
            <template #content>
                <InputLabel for="name" value="Product" class="text-left" />
                <ItemDetail :product="productDetails" v-if="!isObjectEmpty" @quantity="getQuantity"
                    :quantity="quantity" />
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="updateStock">Update</PrimaryButton>
                <DeleteButton @click.prevent="modalClose" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import ItemDetail from "@/Components/ItemDetail.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import SearchFieldAuto from "@/Components/SearchFieldAuto.vue"
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { computed, onMounted, ref } from 'vue';
import toast from '@/Composable/toast'
import Toast from "@/Components/Toast.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from "axios";
import { watch } from "vue";

const quantity = ref(0)
const showModalEdit = ref(false)
const getQuantity = (d) => {
    quantity.value = d
}
const stockId = ref('')
const adjustStock = () => {
    const newFormData = useForm({
        product_id: productDetails.value.id,
        to_quantity: quantity.value,
        from_quantity: productDetails.value.total_quantity
    })
    newFormData.post(route('adjust_stock.store'), {
        onSuccess: () => {
            toast.add({
                message: "Success! Stock quantity has been adjusted",
                status: true
            })
            router.get(route('adjust_stock.index'))
        }, onError: (errors) => {
            toast.add({
                message: "Failed! " + errors,
                status: false
            })
        }

    })
    console.log("productDetails.value addstock: ", productDetails.value.id)
}
const editProduct = async (id) => {
    showModalEdit.value = true
    stockId.value = id
    const result = await axios.get(route('adjust_stock.show', id)).then((response) => {
        console.log(response.data)
        productDetails.value = response.data.product
        quantity.value = response.data.to_quantity
    })

}
const updateStock = () => {
    const newFormData = useForm({
        id: productDetails.value.id,
        to_quantity: quantity.value,
    })
    newFormData.patch(route('adjust_stock.update', stockId.value), {
        onSuccess: () => {
            toast.add({
                message: "Success! Update successfully",
                status: true
            })
            router.get(route('adjust_stock.index'))
        }, onError: (errors) => {
            toast.add({
                message: "Failed! " + errors,
                status: false
            })
        }
    })
}
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
const eraseData = ref(false)
const productDetails = ref({})
const showModalAddNew = ref(false)
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'PRODUCT NAME', key: 'product.product_name' },
    { label: 'BRAND', key: 'product.brand.name' },
    { label: 'CATEGORY', key: 'product.category.name' },
    { label: 'UNIT', key: 'product.unit.name' },
    { label: 'PREVIOUS QUANTITY', key: 'from_quantity' },
    { label: 'LATEST QUANTITY', key: 'to_quantity' },
    { label: 'ADJUSTED BY', key: 'adjustedBy.fullname' },
    { label: 'ADJUSMENT DATE', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'List of all adjusted products', route: route('adjust_stock.index'), id: 1 }
])

const modalClose = () => {
    showModalAddNew.value = false
    showModalEdit.value = false
    productDetails.value = []
}
const showProduct = (result) => {
    productDetails.value = result
    quantity.value = result.total_quantity
}

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
    if (confirm('Are you sure you want to delete this stock adjustment detail?')) {
        router.delete(route('adjust_stock.destroy', id), {
            onSuccess: () => {
                toast.add({
                    message: "Stock adjustment detail has been deleted.",
                    status: true
                })
            }
        })
    }
}

const isObjectEmpty = computed(() => Object.keys(productDetails.value).length === 0)

</script>
<style lang="">

</style>
