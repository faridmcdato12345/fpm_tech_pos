<template>

    <Head title="Store Details" />
    <AuthenticatedLayout>
        <BreadCrumb title="Store Details" :bread-crumbs="breadCrumbs" />
        <Transition :duration="550" name="productForm" mode="out-in">
            <DynamicForm :fields="formFields" :formError="formError" submitButtonText="Save" @submit="handleFormSubmit"
                v-if="productShowForm" :product-obj="pp" />
            <DynamicForm :fields="productDetailFormFields" :formError="formError" submitButtonText="Save"
                @submit="handDetailFormSubmit" :backButton="true" v-else @backTo="backTo" />
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
    units: {
        type: Object,
        required: true
    },
    product: {
        type: Object,
        default: null
    }
})
const pp = ref({})
if (props.product) {
    pp.value = props.product
}
const stepper = ref(1)
const page = usePage()
let productShowForm = ref(true)
const breadCrumbs = ref([
    { label: 'Store', route: route('company.index'), id: 1 },
    { label: 'Details', route: route('company.create'), id: 2 }
])
const formFields = [
    { name: 'name', label: 'Store Name', type: 'text', select: false },
    { name: 'address', label: 'Store Address', type: 'text', select: false },
    { name: 'email', label: 'Email Address', type: 'text', select: false },
    { name: 'mobile_number', label: 'Mobile Number', type: 'text', select: false },
    { name: 'company_logo', label: 'Store Logo', type: 'file', select: false },
];

const formError = ref({})
const storeLogo = ref('')
const handleFormSubmit = (formData, event) => {
    const file = event.target[4].files[0]
    storeLogo.value = URL.createObjectURL(file)
    const newProductForm = useForm({ ...formData, company_logo: file })
    console.log(newProductForm)
    newProductForm.post(route('company.store'), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                message: "Success! Store details has been created",
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

const backTo = () => {
    let idParams = {}
    idParams['id'] = page.props.product_id
    stepper.value = 1
    router.get(route('company.create', idParams))
}
</script>
<style></style>