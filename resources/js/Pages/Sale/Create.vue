<template>

    <Head title="Create Unit" />
    <AuthenticatedLayout>
        <BreadCrumb title="Create Unit" :bread-crumbs="breadCrumbs" />
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
    { label: 'Unit', route: route('unit.index'), id: 1 },
    { label: 'Create Unit', route: route('unit.create'), id: 2 }
])
const formFields = [
    { name: 'name', label: 'Name', type: 'text', select: false },

];

const formError = ref({})
const handleFormSubmit = (formData) => {
    const newProductForm = useForm({ ...formData })
    newProductForm.post(route('unit.store'), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                message: "Success! Unit has been created",
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
    router.get(route('unit.create', idParams))
}
</script>
<style></style>