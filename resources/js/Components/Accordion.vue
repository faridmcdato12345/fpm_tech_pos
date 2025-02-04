<template>
    <div id="accordion-open" data-accordion="open">
        <form enctype="multipart/form-data" @submit.prevent="handleSubmit">
            <h2 id=" accordion-open-heading-1">
                <button type="button" @click.prevent="showProductInfo"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-1 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-open-body-1" aria-expanded="true"
                    aria-controls="accordion-open-body-1">
                    <span class="flex items-center">Product Information</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div v-if="productInfo" id="accordion-open-body-1" aria-labelledby="accordion-open-heading-1"
                class="p-5 dark:border-gray-700 border">
                <div class="mt-4 w-full">
                    <div class="w-full pr-4">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-gray-500 font-medium dark:text-gray-400">Product Name</label>
                                <input v-model="formData.product_name" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                                <div v-if="formError[product_name]" class="text-red-500 mt-1">{{
                                    formError[product_name]
                                    }}</div>
                            </div>
                            <div>
                                <label class="text-gray-500 font-medium dark:text-gray-400">SKU</label>
                                <div class="relative">
                                    <input v-model="formData.sku" type="text"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                                    <PrimaryButton class="absolute top-1 right-1">Generate SKU</PrimaryButton>

                                </div>
                                <div v-if="formError[sku]" class="text-red-500 mt-1">{{ formError[sku] }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <div class="relative">
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Category</label>
                                <div class="flex space-x-2">
                                    <select v-model="formData.category_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                                        <option value="">-- Select category--</option>
                                        <option v-for="category in props.categories" :key="category.id"
                                            :value="category.id">{{ category.name }}
                                        </option>
                                    </select>
                                    <PrimaryButton @click.prevent="showModalCategory">Create</PrimaryButton>
                                </div>

                                <div v-if="formError[category_id]" class="text-red-500 mt-1">{{
                                    formError[category_id] }}</div>
                            </div>
                            <div>
                                <label class="text-gray-500 font-medium dark:text-gray-400">Unit</label>
                                <div class="flex space-x-2">
                                    <select name="" id="" v-model="formData.unit_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                                        <option value="">-- Select unit --</option>
                                        <option v-for="unit in props.units" :key="unit.id" :value="unit.id">{{ unit.name
                                            }}
                                        </option>
                                    </select>
                                    <PrimaryButton @click.prevent="modalUnit = true">Create</PrimaryButton>
                                </div>

                                <div v-if="formError[unit_id]" class="text-red-500 mt-1">{{ formError[unit_id] }}
                                </div>
                            </div>
                            <div>
                                <label class="text-gray-500 font-medium dark:text-gray-400">Brand</label>
                                <div class="flex space-x-2">
                                    <select name="" id="" v-model="formData.brand_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                                        <option value="">-- Select brand --</option>
                                        <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">{{
                                            brand.name
                                            }}
                                        </option>
                                    </select>
                                    <PrimaryButton @click.prevent="modalBrand = true">Create</PrimaryButton>
                                </div>

                                <div v-if="formError[brand_id]" class="text-red-500 mt-1">{{ formError[brand_id] }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col mt-4">
                            <label for="" class="text-gray-500 font-medium dark:text-gray-400">Barcode</label>
                            <input v-model="formData.barcode" type="text"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                        </div>
                        <div class="flex flex-col mt-4">
                            <label for="" class="text-gray-500 font-medium dark:text-gray-400">Description</label>
                            <textarea cols="50" rows="10" v-model="formData.description"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm"></textarea>
                            <p class="text-gray-500 dark:text-gray-400">Maximum 60 Characters</p>
                        </div>
                    </div>
                </div>
            </div>
            <h2 id="accordion-open-heading-2">
                <button type="button" @click.prevent="showPricingStock"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-1 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-open-body-2" aria-expanded="false"
                    aria-controls="accordion-open-body-2">
                    <span class="flex items-center">Pricing & Stocks</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div v-if="pricingStock" id="accordion-open-body-2" aria-labelledby="accordion-open-heading-2"
                class="p-5 dark:border-gray-700 border">

                <div class="mt-4 w-full">
                    <div class="w-full pr-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Quantity</label>
                                <input v-model="formData.quantity"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                                <div v-if="formError[quantity]" class="text-red-500 mt-1">{{ formError[quantity]
                                    }}</div>
                            </div>
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Purchased
                                    Price</label>
                                <input v-model="formData.purchased_price"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                                <div v-if="formError[purchased_price]" class="text-red-500 mt-1">{{
                                    formError[purchased_price]
                                    }}</div>
                            </div>
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Selling
                                    Price</label>
                                <input v-model="formData.selling_price"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                                <div v-if="formError[selling_price]" class="text-red-500 mt-1">
                                    {{ formError[selling_price] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 id="accordion-open-heading-3">
                <button type="button" @click.prevent="showCustomField"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-open-body-3" aria-expanded="false"
                    aria-controls="accordion-open-body-3">
                    <span class="flex items-center">Custom Fields</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div v-if="customField" id="accordion-open-body-3" aria-labelledby="accordion-open-heading-3"
                class="p-5 dark:border-gray-700 border">

                <div class="mt-4 w-full">
                    <div class="w-full pr-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Re-order
                                    Level</label>
                                <input v-model="formData.reorder_level"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                            </div>
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Expiration
                                    Date</label>
                                <input v-model="formData.expiration_date" type="date"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                            </div>
                            <div>
                                <label for="" class="text-gray-500 font-medium dark:text-gray-400">Need
                                    Prescription</label>
                                <select v-model="formData.is_prescription"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                                    <option disabled value="">-- Select --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-end mt-4">
                <PrimaryButton type="submit">Save Product</PrimaryButton>
                <DangerButton @click.prevent="$emit('cancel')">Cancel</DangerButton>
            </div>
        </form>
        <Modal :show="modalCategory" @close="cancelCategory">
            <template #header>
                <h5 class="text-white font-black text-lg">Category</h5>
            </template>
            <template #content>
                <InputLabel for="name" value="Name" class="text-left" />
                <input id="status" type="text" v-model="theFormData.name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                <InputLabel for="status" value="Status" class="text-left mt-4" />
                <select name="" id="" v-model="theFormData.status"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                    <option value="">Select status...</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="submitCategory">Create Category</PrimaryButton>
                <DeleteButton @click.prevent="cancelCategory" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <Modal :show="modalUnit" @close="cancelUnit">
            <template #header>
                <h5 class="text-white font-black text-lg">Unit</h5>
            </template>
            <template #content>
                <InputLabel for="name" value="Name" class="text-left" />
                <input id="status" type="text" v-model="theFormData.name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                <InputLabel for="status" value="Status" class="text-left mt-4" />
                <select name="" id="" v-model="theFormData.status"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                    <option value="">Select status...</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="submitUnit">Create Unit</PrimaryButton>
                <DeleteButton @click.prevent="cancelUnit" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <Modal :show="modalBrand" @close="cancelBrand">
            <template #header>
                <h5 class="text-white font-black text-lg">Create Brand</h5>
            </template>
            <template #content>
                <InputLabel for="name" value="Name" class="text-left" />
                <input id="status" type="text" v-model="theFormData.name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                <InputLabel for="status" value="Status" class="text-left mt-4" />
                <select name="" id="" v-model="theFormData.status"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                    <option value="">Select status...</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="submitBrand">Create Brand</PrimaryButton>
                <DeleteButton @click.prevent="cancelBrand" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
    </div>
    <Toast />
</template>

<script setup>
import InputLabel from './InputLabel.vue';
import DeleteButton from './DeleteButton.vue';
import Modal from './Modal.vue';
import Toast from './Toast.vue';
import toast from '@/Composable/toast';
import DangerButton from './DangerButton.vue';
import PrimaryButton from './PrimaryButton.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const productInfo = ref(true)
const pricingStock = ref(false)
const customField = ref(false)
const modalCategory = ref(false)
const modalUnit = ref(false)
const modalBrand = ref(false)


const cancelCategory = () => {
    modalCategory.value = false
}
const cancelBrand = () => {
    modalBrand.value = false
}
const cancelUnit = () => {
    modalUnit.value = false
}
const showModalCategory = () => {
    modalCategory.value = true
}
const showModalUnit = () => {
    modalUnit.value = true
}
const showModalBrand = () => {
    modalBrand.value = true
}
const theFormData = useForm({
    name: '',
    status: ''
})
const submitCategory = () => {
    theFormData.post(route('category.store'), {
        preserveState: true,
        onSuccess: () => {
            toast.add({
                message: "Success! Category has been added",
                status: true
            })
            modalCategory.value = false
            theFormData.reset()
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
const submitUnit = () => {
    theFormData.post(route('unit.store'), {
        preserveState: true,
        onSuccess: () => {
            toast.add({
                message: "Success! Unit has been added",
                status: true
            })
            modalUnit.value = false
            theFormData.reset()
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
const submitBrand = () => {
    theFormData.post(route('brand.store'), {
        preserveState: true,
        onSuccess: () => {
            toast.add({
                message: "Success! Brand has been added",
                status: true
            })
            modalBrand.value = false
            theFormData.reset()
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
const props = defineProps({
    categories: Object,
    units: Object,
    brands: Object
})
const formData = useForm({
    product_name: '',
    sku: '',
    category_id: '',
    brand_id: '',
    unit_id: '',
    description: '',
    quantity: '',
    purchased_price: '',
    selling_price: '',
    reorder_level: '',
    expiration_date: '',
    is_prescription: '',
    barcode: ''
})
const formError = ref({})
const emit = defineEmits(['submit'])
const handleSubmit = (event) => {
    emit('submit', formData, event)
    formData.reset()
}
const showCustomField = () => {
    customField.value = !customField.value
}
const showPricingStock = () => {
    pricingStock.value = !pricingStock.value
}
const showProductInfo = () => {
    productInfo.value = !productInfo.value
}
</script>

<style lang="scss" scoped></style>