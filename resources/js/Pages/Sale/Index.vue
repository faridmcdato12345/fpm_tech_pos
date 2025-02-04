<template>

    <Head title="Sales" />
    <AuthenticatedLayout>
        <BreadCrumb title="Sales" :bread-crumbs="breadCrumbs">
            <PrimaryButton @click.prevent="addSale = true">
                <div class="flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p>Add Sale</p>
                </div>
            </PrimaryButton>
        </BreadCrumb>

        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.sales.data" :columns="columns" :pagination="props.sales" :route-edit="editRoute"
                :route-delete="deleteRoute" @limit-query="limitQuery" @search-field-query="searchFieldQuery"
                :query-limit="props.queryLimit" :query-name="props.queryName" :action="true" :is-sale="true"
                @sale-detail="showSaleDetails" @edit-sale="editSale" @download-sale="downloadSale"
                @delete-sale="deleteSale">
                <template #export>
                    <ExportButton :links="exportLink" :fromTo="true" />
                </template>
            </DataTable>
        </div>
        <AddSale :show="addSale" @update:show="addSale = $event" :customers="customers" :address-lists="addressList" />
        <Modal :show="modalSeeDetail" @close="modalSeeDetail = false">
            <template #header>
                <h1 class="text-lg font-bold">Sale Detail: {{ saleDetailObj[0]?.invoice_number }}</h1>
            </template>
            <template #content>
                <div>
                    <h1 class="text-md font-bold text-left">Sale Summary</h1>
                </div>
                <div>
                    <div class="relative overflow-x-auto border border-teal-400">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Invoice Number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Qty
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Brand
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Unit
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Selling Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Extended
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                    v-for="(sale, index) in saleDetailObj" :key="index">
                                    <th scope="row"
                                        class="uppercase px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ sale.product_stocks?.product?.product_name }}
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        {{ sale.invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ sale.quantity }}
                                    </td>
                                    <td class="px-6 py-4">{{ sale.product_stocks?.product?.brands?.name }}</td>
                                    <td class="px-6 py-4">{{ sale.product_stocks?.product?.categories?.name }}</td>
                                    <td class="px-6 py-4">{{ sale.product_stocks?.product?.units?.name }}</td>
                                    <td class="px-6 py-4 text-right">{{ sale.product_stocks?.selling_price }}</td>
                                    <td class="px-6 py-4 text-right">{{ sale.product_stocks?.selling_price *
                                        sale.quantity }}</td>
                                    <td class="px-6 py-4">{{ sale.remarks }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <div class="flex justify-between w-1/2 bg-white border border-teal-400 mt-1 text-gray-900">
                            <div class="w-full">
                                <div class="total-order w-full m-auto">
                                    <table
                                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <tbody>
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                                    <h4>Discount</h4>
                                                </th>
                                                <td class="px-6 py-4 text-right">
                                                    <h5>{{ filteredResult ? filteredResult[0].discount : '0.00' }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                                    <h4>Grand Total</h4>
                                                </th>
                                                <td class="px-6 py-4 text-right font-medium text-gray-900">
                                                    <h5>{{ filteredResult ? filteredResult[0].grand_total : '0.00' }}
                                                    </h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Modal>
        <Modal :show="modalEditSale" @close="modalEditSale = false">
            <template #header>
                <h1>Edit Sale</h1>
            </template>
            <template #content>
                <div>
                    <p>this is modal edit sale</p>
                </div>
            </template>
        </Modal>
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
import Modal from "@/Components/Modal.vue";
import { filter } from "minimatch";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddSale from "@/Components/AddSale.vue";

const modalSeeDetail = ref(false)
const modalEditSale = ref(false)
const modalDownloadSale = ref(false)
const modalDeleteSale = ref(false)
const saleDetailObj = ref([])
const filteredResult = ref()
const addSale = ref(false)

const props = defineProps({
    sales: {
        type: Object,
        required: true
    },
    customers: {
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
    },
    addressList: {
        type: Array,
        default: () => []
    }
})
const page = usePage()
const exportLink = [
    { id: 1, name: 'excel', url: route('sales.excel') },
    { id: 2, name: 'pdf', url: route('sales.pdf') }
]
onMounted(() => {
    if (page.props.toast) {
        toast.add({
            message: page.props.toast.message,
            status: page.props.toast.status
        })
    }
    // const response = props.sales.data
    // const result = response.filter(sale => sale.id === id)
    // filteredResult.value = result
    // console.log(filteredResult.value)
})

const showModal = ref(false)
const columns = ref([
    { label: 'INVOICE NUMBER', key: 'invoice_number' },
    { label: 'GRAND TOTAL', key: 'grand_total' },
    { label: 'STATUS', key: 'remarks' },
    { label: 'DATE', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'List of all sales', route: route('sale.index'), id: 1 }
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
    router.get(route('sale.index', params))
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
    router.get(route('sale.index', params))
}
console.log(props.sales)
const showSaleDetails = (id) => {
    modalSeeDetail.value = true
    const response = props.sales.data
    const result = response.filter(sale => sale.id === id)
    filteredResult.value = result
    console.log("result: ", result)
    if (result.length > 0) {
        result.forEach(response => {
            saleDetailObj.value = response.products
        })
    }
    console.log("saleDetailObj: ", saleDetailObj.value)
}

</script>
<style lang="">

</style>
