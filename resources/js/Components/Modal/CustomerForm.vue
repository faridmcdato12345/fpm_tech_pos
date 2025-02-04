<template>
    <Modal :show="show" @close="handleClose">
        <template #header>
            <p>Create Customer</p>
        </template>
        <template #content>
            <div class="mb-5">
                <label for="name" class="text-white text-left block mb-2 text-sm font-medium  dark:text-white">Full
                    name</label>
                <input type="text" id="name" v-model="formData.name"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="Juan dela Cruz" required />
            </div>
            <div class="mb-5">
                <label for="name" class="text-white text-left block mb-2 text-sm font-medium  dark:text-white">Company
                    Name
                </label>
                <input type="text" id="name" v-model="formData.company"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="ABC Incorporated" required />
            </div>
            <div class="mb-5">
                <label for="password" class="text-white text-left block mb-2 text-sm font-medium  dark:text-white">
                    Address</label>
                <Address @updateSelections="handleSelections" :address-lists="addressLists" :reset-key="resetKey" />
            </div>
            <div class="mb-5">
                <label for="email" class="text-left block mb-2 text-sm font-medium text-white dark:text-white">
                    Email Address</label>
                <input type="email" id="email" v-model="formData.email"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="sample@sample.com" />
            </div>
        </template>
        <template #footer>
            <div class="flex items-end space-x-2">
                <PrimaryButton @click.prevent="createCustomer">Create</PrimaryButton>
                <DangerButton @click.prevent="handleClose">Cancel</DangerButton>
            </div>
        </template>
    </Modal>
</template>

<script setup>

import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { useForm } from "@inertiajs/vue3";
import Address from "../Address.vue";
import { watch, ref } from 'vue'
import Toast from '@/Components/Toast.vue';
import toast from '@/Composable/toast'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    resultdata: {
        type: Array,
        default: () => []
    },
    customerId: {
        type: Number,
        required: true
    },
    addressLists: {
        type: Array,
        default: () => []
    }
})
const formData = useForm({
    name: '',
    email: '',
    region: '',
    province: '',
    barangay: '',
    company: '',
    municipality: ''
})
const handleSelections = (data) => {
    formData.region = data.region
    formData.province = data.province
    formData.barangay = data.barangay
    formData.municipality = data.municipality
}

const emit = defineEmits(['update:show'])
const resetKey = ref(0);
const generatePdf = async () => {
    try {
        const finalResult = {
            data: [...props.resultdata],
            customer_id: props.customerId
        }
        const response = await axios.post(route('generate.invoice'), finalResult, {
            responseType: 'blob' // Ensure response is treated as a Blob
        });

        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
        const pdfWindow = window.open();
        pdfWindow.location.href = url;
    } catch (e) {
        console.error('Error generating PDF:', e);
    }
};
const handleClose = () => {
    if (props.closeable) {
        emit("update:show", false)
        formData.reset()
    }
}

const createCustomer = () => {
    console.log(formData)
    try {
        formData.post(route('customer.store'), {
            onSuccess: () => {
                handleClose()
                toast.add({
                    message: "Success! Customer has been saved.",
                    status: true
                })
                formData.reset(
                    'name',
                    'email',
                    'region',
                    'province',
                    'barangay',
                    'municipality',
                    'company'
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
    } catch (e) {

    }
}
// Watch `show` prop to reset form data when modal is opened
watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            formData.reset(); // Reset all form fields when modal is shown
            resetKey.value += 1;
        }
    }
);
</script>

<style scoped></style>
