<template>

    <Head title="New Product" />
    <AuthenticatedLayout>
        <BreadCrumb title="New Product" :bread-crumbs="breadCrumbs" />
        <div class="p-12">
            <Transition :duration="550" name="productForm" mode="out-in">
                <Accordion @cancel="goBackToIndex" :categories="props.categories" :units="props.units"
                    :brands="props.brands" @submit="handleFormSubmit" />
            </Transition>
            <!-- <Transition :duration="550" name="productForm" mode="out-in">
                <DynamicForm :fields="productFormFields" :formError="formError" submitButtonText="Next"
                    @submit="handleFormSubmit" v-if="productShowForm" :product-obj="pp" />
                <DynamicForm :fields="productDetailFormFields" :formError="formError" submitButtonText="Save"
                    @submit="handDetailFormSubmit" :backButton="true" v-else @backTo="backTo" />
            </Transition> -->
        </div>


    </AuthenticatedLayout>
</template>
<script setup>
import DynamicForm from '@/Components/Form.vue'
import BreadCrumb from '@/Components/Breadcrumb.vue'
import Accordion from '@/Components/Accordion.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Toast from '@/Components/Toast.vue';
import toast from '@/Composable/toast'

const props = defineProps({
    categories: {
        type: Object,
        required: true
    },
    units: {
        type: Object,
        required: true
    },
    product: {
        type: Object,
        default: null
    },
    brands: {
        type: Object
    }
})
const pp = ref({})
if (props.product) {
    pp.value = props.product
}
const goBackToIndex = () => {
    router.get(route('product.index'))
}
const stepper = ref(1)
const page = usePage()
let productShowForm = ref(true)
const breadCrumbs = ref([
    { label: 'Product', route: route('product.index'), id: 1 },
    { label: 'Create Product', route: route('product.create'), id: 2 }
])
const productFormFields = [
    { name: 'product_name', label: 'Product Name', type: 'text', select: false },
    { name: 'purchased_price', label: 'Purchased Price', type: 'text', select: false },
    { name: 'reorder_level', label: 'Reorder Level', type: 'text', select: false },
    { name: 'selling_price', label: 'Selling Price', type: 'text', select: false },
    { name: 'category_id', label: 'Category', options: props.categories, select: true },
    { name: 'unit_id', label: 'Unit', options: props.units, select: true, },

];
const productDetailFormFields = [
    { name: 'barcode', label: 'Barcode', type: 'text', select: false },
    { name: 'quantity', label: 'Quantity', type: 'text', select: false },
];

const formError = ref({})
const handleFormSubmit = (formData) => {
    const newProductForm = useForm({ ...formData })
    newProductForm.post(route('product.store'), {
        onSuccess: () => {
            toast.add({
                message: "Success! Product has been created.",
                status: true
            })
        },
        onError: (errors) => {
            formError.value = errors
            Object.values(errors).forEach(error => {
                toast.add({
                    message: "Failed! " + error,
                    status: false
                })
            })
        }
    })

};
const handDetailFormSubmit = (formData) => {
    const productDetailForm = useForm({ product_id: page.props.product_id, ...formData })
    productDetailForm.post(route('product_detail.store'), {
        onSuccess: () => {
            router.get(route('product.create'))
            productShowForm.value = !productShowForm.value
            toast.add({
                message: "Success! Product has been created.",
                status: true
            })
            productDetailForm.reset(
                'quantity',
                'barcode'
            )
        },
        onError: (errors) => {
            formError.value = errors
            Object.values(errors).forEach(error => {
                toast.add({
                    message: "Failed! " + error,
                    status: false
                })
            })
        }

    })
}
const backTo = () => {
    let idParams = {}
    idParams['id'] = page.props.product_id
    stepper.value = 1
    router.get(route('product.create', idParams))
}
</script>
<style>
.productForm-enter-active,
.productForm-leave-active {
    transition: all 0.3s ease-in-out;
}

.productForm-leave-active {
    transition-delay: 0.25s;
}

.productForm-enter-from,
.productForm-leave-to {
    transform: translateX(-30px);
    opacity: 0;
}
</style>