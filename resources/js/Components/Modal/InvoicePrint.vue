<template>
    <Modal :show="show" @close="handleClose">
        <template #header></template>
        <template #content>
            <div>
                <p>Would you like to generate INVOICE?</p>
            </div>
        </template>
        <template #footer>
            <div class="flex items-end space-x-2">
                <PrimaryButton @click.prevent="generatePdf">Yes</PrimaryButton>
                <DangerButton @click.prevent="$emit('no-generate')">No</DangerButton>
            </div>
        </template>
    </Modal>
</template>

<script setup>

import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { useForm } from "@inertiajs/vue3";

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
    }
})
const emit = defineEmits(['update:show'])
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
    }
}
</script>

<style scoped></style>
