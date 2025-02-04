<template>

    <Head title="Edit Unit" />
    <AuthenticatedLayout>
        <BreadCrumb title="Edit Unit" :bread-crumbs="breadCrumbs" />
        <!-- <Stepper :steps="stepper" /> -->
        <Transition :duration="550" name="productForm" mode="out-in">
            <DynamicForm :fields="formFields" :formError="formError" submitButtonText="Update"
                @submit="handleFormSubmit" @backToIndex="backToIndex" :backButtonIndex="true"
                :product-obj="props.unit" />
        </Transition>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import DynamicForm from '@/Components/Form.vue'
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, } from '@inertiajs/vue3';
import { ref } from 'vue';
import Toast from '@/Components/Toast.vue';
import toast from '@/Composable/toast'

const props = defineProps({
    unit: {
        type: Object,
        default: null,
        required: true
    }
})
const breadCrumbs = ref([
    { label: 'Unit', route: route('unit.index'), id: 1 },
    { label: 'Edit', route: route('unit.edit', props.unit.id), id: 2 },
])
const formFields = [
    { name: 'name', label: 'Name', type: 'text', select: false },
];

const formError = ref({})
const handleFormSubmit = (formData) => {
    const newProductForm = useForm({ ...formData })
    console.log(newProductForm)
    newProductForm.put(route('unit.update', props.unit.id), {
        preserveState: false,
        onSuccess: () => {
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
const backToIndex = () => {
    router.get(route('unit.index'))
}
</script>
<style></style>