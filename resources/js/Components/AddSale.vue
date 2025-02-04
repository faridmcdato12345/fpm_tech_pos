<template>
    <Modal :show="show" @close="handleClose">
        <template #header>
            Add Sale
        </template>
        <template #content>
            <div class="card-body">
                <form action="">
                    <div class="row space-y-4">
                        <div class="flex flex-col">
                            <div class="w-full">
                                <div class="input-blocks w-full text-left space-y-2">
                                    <label>Customer Name</label>
                                    <div class="w-full flex items-center space-x-2">
                                        <div class="w-full">
                                            <Select2 :options="customers" class="text-gray-900"
                                                @selected-customer="handleSelectedCustomerId" :reset-key="resetKey" />
                                        </div>
                                        <div class="w-20">
                                            <div class="add-icon bg-teal-400 py-2 px-4 hover:cursor-pointer"
                                                @click.prevent="showCustomerForm = true">
                                                <p>Create</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="">
                                <div class="input-blocks ">
                                    <label>Date</label>

                                    <div class="relative max-w-sm">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-actions" datepicker datepicker-buttons
                                            datepicker-autoselect-today type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>

                                </div>
                            </div> -->
                        </div>

                        <div class="col-lg-12 col-sm-6 col-12 text-left">
                            <div class="input-blocks space-y-2">
                                <InputLabel>Product Name</InputLabel>
                                <ProductSearch @data-result="saleResult" @complete-result="getCompleteResult"
                                    :reset-key="resetKey" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 ms-auto text-left mt-10">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Grand Total</h4>
                                        <h5>{{ grandTotal }}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </template>
        <template #footer>
            <div class="flex items-end space-x-2">
                <PrimaryButton @click.prevent="showInvoiceModal = true">Add</PrimaryButton>
                <DangerButton @click.prevent="handleClose">Cancel</DangerButton>
            </div>
        </template>
    </Modal>
    <InvoicePrint :show="showInvoiceModal" @update:show="showInvoiceModal = $event" :resultdata="completeResult"
        @no-generate="saveToDb" :customer-id="selectedCustomerId" />
    <CustomerForm :show="showCustomerForm" :address-lists="addressLists" @update:show="showCustomerForm = $event" />
</template>

<script setup>
import Select2 from "./Select2.vue";
import Modal from "@/Components/Modal.vue";
import SearchField from "@/Components/SearchField.vue";
import { ref, watch } from "vue";
import ProductSearch from "@/Components/ProductSearch.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { convertToCurrent } from "@/util.js";
import { useForm } from "@inertiajs/vue3";
import toast from "@/Composable/toast.js";
import InvoicePrint from "@/Components/Modal/InvoicePrint.vue";
import CustomerForm from "@/Components/Modal/CustomerForm.vue";

const props = defineProps({
    customers: {
        type: Object
    },
    show: {
        type: Boolean,
        default: false
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    addressLists: {
        type: Array,
        default: () => []
    }
})
const resetKey = ref(0);
const selectedCustomerId = ref(0)
const searchQuery = ref('');
const searchResults = ref([]);
const grandTotal = ref(0)
const formData = ref([])
const theResult = ref([])
const showInvoiceModal = ref(false)
const showCustomerForm = ref(false)
const completeResult = ref([])
const emit = defineEmits(['update:show'])
const handleClose = () => {
    if (props.closeable) {
        emit("update:show", false)
        reset()
    }
}
const handleSelectedCustomerId = (id) => {
    selectedCustomerId.value = id
}
const saleResult = (data) => {
    formData.value = data
    const x = data.map(d => d.quantity * d.selling_price)
    grandTotal.value = convertToCurrent(x.reduce((acc, num) => acc + num, 0))
}
const reset = () => {
    grandTotal.value = 0
    formData.value = []
}
const saveToDb = () => {
    const form = useForm({
        fields: formData.value
    })
    form.post(route('sale.store'), {
        onSuccess: () => {
            toast.add({
                message: "Success! Sales saved.",
                status: true
            })
        },
        onError: (error) => {
        }
    })
    showInvoiceModal.value = false
    emit("update:show", false)
}
const getCompleteResult = (results) => {
    completeResult.value = results
    console.log("complete:", completeResult.value)
}
// Watch `show` prop to reset form data when modal is opened
watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            formData.value = []
            resetKey.value += 1;
        }
    }
);
</script>

<style scoped></style>
