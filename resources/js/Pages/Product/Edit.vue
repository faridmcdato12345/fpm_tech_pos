<template>

    <Head title="Edit Product" />
    <AuthenticatedLayout>
        <BreadCrumb title="Edit Product" :bread-crumbs="breadCrumbs" />
        <!-- <Stepper :steps="stepper" /> -->
        <Transition :duration="550" name="productForm" mode="out-in">
            <DynamicForm :fields="productFormFields" :formError="formError" submitButtonText="Next"
                @submit="handleFormSubmit" v-if="productShowForm" :product-obj="pp" @backToIndex="backToIndex"
                :backButtonIndex="true" />
            <DynamicForm :fields="productDetailFormFields" :formError="formError" submitButtonText="Save"
                @submit="handDetailFormSubmit" :backButton="true" v-else @backTo="backTo"
                :product-obj="productDetail" />
        </Transition>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import DynamicForm from '@/Components/Form.vue'
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
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
        default: null,
        required: true
    },
    productDetail: {
        type: Object,
        default: null,
        required: true
    }
})
const product_detail = ref({})
const pp = ref({})
if (props.product) {
    pp.value = props.product
}
if (props.productDetail) {
    pp.value = { ...props.product, ...props.productDetail }
}
const stepper = ref(1)
const page = usePage()
let productShowForm = ref(true)
const breadCrumbs = ref([
    { label: 'Product', route: route('product.index'), id: 1 },
    { label: 'Edit', route: route('product.edit', props.product.id), id: 1 },
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
    { name: 'value', label: 'Quantity', type: 'number', select: false },
];

const formError = ref({})
const handleFormSubmit = (formData) => {
    const newProductForm = useForm({ product_id: page.props.product.id, ...formData })
    if (newProductForm.product_id) {
        newProductForm.put(route('product.update', page.props.product.id), {
            onSuccess: () => {
                productShowForm.value = !productShowForm.value
                newProductForm.reset(
                    'purchased_price',
                    'selling_price',
                    'reoder_level',
                    'category_id',
                    'unit_id',
                    'product_name',
                    'reorder_level'
                )
                stepper.value = 2
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
};
const handDetailFormSubmit = (formData) => {
    const productDetailForm = useForm({ ...formData })
    productDetailForm.put(route('quantity.update', formData.product_id), {
        onSuccess: () => {
            stepper.value = 3
            router.get(route('product.index'))
            toast.add({
                message: "Success! Product has been edit.",
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
}
const backTo = () => {
    let idParams = {}
    idParams['id'] = page.props.product_id
    stepper.value = 1
    router.get(route('product.edit', idParams))
}
const backToIndex = () => {
    router.get(route('product.index'))
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