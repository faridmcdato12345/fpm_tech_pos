<template>

    <Head title="Point of Sale" />
    <PointOfSaleLayout>
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between space-x-2">
                <ActionButton @button-clicked="actionButtonClick" :lists="actionButtonList" class="mb-8 mt-8" />
                <div class="mt-8 w-full">
                    <div class="mb-4">
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative" :ref="!showModal ? 'trapRef' : ''">
                            <div
                                class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <SearchField v-model:searchQuery="searchQuery" @update:result="setResults"
                                @close-modal="closeModal" />
                        </div>
                    </div>
                    <!----table result-->
                    <div class="h-96 max-h-96 overflow-y-auto border-teal-400 border-2"
                        id="scrollable-div">
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 h-[calc(100%-1rem)] max-h-full ">
                            <thead
                                class="text-nowrap text-xs text-white uppercase bg-black dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-wrap">BARCODE</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">PRODUCT NAME</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">QUANTITY</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">PRICE</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">EXTENDED</th>
                                    <th scope="col" class="px-6 py-3 text-wrap"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in formData" :key="item.id"
                                    @keydown.enter.prevent.stop="keyPressEnterChangeRow"
                                    @click.prevent="tableResultClick(index)"
                                    :class="selectedRow === index ? 'dark:bg-gray-600' : 'dark:bg-gray-800'"
                                    class="text-nowrap bg-white border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer">
                                    <td class="px-6 py-4">{{ item.barcode }}</td>
                                    <td class="px-6 py-4">{{ item.name }}</td>
                                    <td class="px-6 py-4">{{ item.quantity }}</td>
                                    <td class="px-6 py-4">&#8369; {{ (item.selling_price).toFixed(2) }}</td>
                                    <td class="px-6 py-4">&#8369; {{ (item.selling_price * item.quantity).toFixed(2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span>minus</span><span>plus</span><span>delete</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="relative ml-4">
                    <AnalogClock />
                    <!---- transaction div-->
                    <div class="w-full bottom-2">
                        <!---Transaction total-->
                        <TransactionTotal :items="formData" :discounts="discountValue" :reset="resetTrans" />
                    </div>
                </div>
            </div>
        </div>
        <!---last scanned item-->
        <div class="w-full bottom-2 mt-4">
            <div class="w-full">
                <div class="dark:bg-teal-400 w-[99%] mx-auto p-4 rounded-xl">
                    <div v-if="lastScanItemName" class="flex justify-between ">
                        <div class="text-6xl font-black">
                            {{ lastScanItemName }}
                        </div>
                        <div v-if="lastScanItemPrice > 0" class="text-6xl font-black">
                            &#8369; {{ lastScanItemPrice.toFixed(2) }}
                        </div>
                    </div>
                    <div v-else class="flex justify-center">
                        <div class="text-6xl font-black text-center animate-pulse">
                            NO ITEM SCAN YET
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div v-if="showModalQuantity"
            class="dark:bg-black/80 flex modal-n overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0  max-h-full"
            ref="trapRef">
            <div class="bg-black text-left w-full max-w-2xl flex flex-col border border-teal-400">
                <div class="border-b-2">
                    <div class="p-4 flex flex-col">
                        <div>
                            <span class="close" @click="showModalQuantity = false">&times;</span>
                        </div>
                        <div class="flex justify-center">
                            <p class="text-4xl font-extrabold text-white">{{ productName }}</p>
                        </div>
                    </div>

                </div>
                <div class="border-b-2">
                    <div class="p-4 flex justify-center">
                        <div>
                            <label for="quantity" class="mb-4">
                                <p class="text-2xl font-black text-center mb-4">Quantity</p>
                            </label>
                            <input type="number" class="w-full quantity-modal text-8xl text-center"
                                v-model="productQuantity" @keydown.enter.prevent="addProduct">
                        </div>
                    </div>
                </div>
                <div class="flex justify-center items-center p-4">
                    <PrimaryButton @click.prevent="addProduct" class="text-4xl">ADD</PrimaryButton>
                </div>
            </div>
        </div>
        <!---modal print-->
        <Modal :show="modalPrint" @close="modalPrint = false">
            <template #header>PRINT</template>
            <template #content>Would you like to print a receipt?</template>
            <template #footer>
                <PrimaryButton @click.prevent="saveToDb(true)">Yes</PrimaryButton>
                <DeleteButton @click.prevent="saveToDb(false)" class="ml-2">No</DeleteButton>
            </template>
        </Modal>
        <!-- moda pin auth-->
        <Modal :show="modalPinAuthorization" @close="modalPinAuthorization = false">
            <template #header>Authorization</template>
            <template #content>
                <label for="" class="text-4xl font-black text-center">ENTER PIN</label>
                <div>
                    <TextInput v-model="pinAuth" class="text-4xl font-black text-center mb-4" type="password" />

                </div>

            </template>
            <template #footer>
                <PrimaryButton @click.prevent="authorization">Ok</PrimaryButton>
                <DeleteButton @click.prevent="modalPinAuthorization = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal change quantity-->
        <Modal :show="modalChangeQuantity" @close="modalChangeQuantity = false">
            <template #header>CHANGE QUANTITY</template>
            <template #content>
                <div class="flex flex-col">
                    <label class="text-4xl mb-4 font-bold">Quantity</label>
                    <TextInput v-model="changeQuantity" type="number" class="text-4xl text-center font-bold"
                        autofocus />
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="saveQuantityChange">Save</PrimaryButton>
                <DeleteButton @click.prevent="modalChangeQuantity = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal change price-->
        <Modal :show="modalChangePrice" @close="modalChangePrice = false">
            <template #header>CHANGE PRICE</template>
            <template #content>
                <div class="flex flex-col">
                    <label class="text-4xl font-bold mb-4">Price</label>
                    <TextInput v-model="changePrice" type="number" class="text-4xl font-bold text-center" />
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="savePriceChange">Save</PrimaryButton>
                <DeleteButton @click.prevent="modalChangePrice = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal on hold-->
        <Modal :show="modalOnHold" @close="modalOnHold = false">
            <template #header>HOLD TRANSACTION</template>
            <template #content>Would you like to hold this transaction?</template>
            <template #footer>
                <PrimaryButton @click.prevent="holdTransaction">Yes</PrimaryButton>
                <DeleteButton @click.prevent="modalOnHold = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal unhold-->
        <Modal :show="modalUnHold" @close="modalUnHold = false">
            <template #header>UNHOLD TRANSACTION</template>
            <template #content>Would you like to retrieve the previous transaction?</template>
            <template #footer>
                <PrimaryButton @click.prevent="unHoldTransaction">Yes</PrimaryButton>
                <DeleteButton @click.prevent="modalUnHold = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal payment-->
        <Modal :show="modalPayment" @close="modalPayment = false">
            <template #header>Payment</template>
            <template #content>
                <div>
                    <p class="text-4xl font-black">Please Enter Cash Tendered</p>
                </div>
                <div>
                    <label for="" class="text-4xl font-extrabold">Amount Due</label>
                    <p class="text-4xl font-extrabold mt-7 mb-7 dark:text-teal-400">&#8369; {{ amountDue.toFixed(2) }}
                    </p>
                </div>
                <div>
                    <label for="" class="text-4xl font-extrabold">CASH TENDERED</label>
                    <TextInput v-model="tender" class="text-4xl font-extrabold text-center mt-7" />
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="goToSaveModal" v-if="showOk">Ok</PrimaryButton>
                <DeleteButton @click.prevent="modalPayment = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>

        <!---modal all item discount-->
        <Modal :show="modalAllItemDiscount" @close="modalAllItemDiscount = false">
            <template #header>DISCOUNTS</template>
            <template #content>
                <div>
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 h-[calc(100%-1rem)] max-h-full ">
                        <thead
                            class="text-nowrap text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-wrap">DESCRIPTION</th>
                                <th scope="col" class="px-6 py-3 text-wrap">VALUE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="discount in discountList" :key="discount.id"
                                @click.prevent="selectDiscount(discount.value)"
                                class="dark:bg-gray-600 text-nowrap bg-white border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 cursor-pointer">
                                <td class="px-6 py-4">{{ discount.description }}</td>
                                <td class="px-6 py-4">{{ discount.value }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </Modal>
        <!---modal start amount-->
        <Modal :show="modalStartAmount" @close="modalStartAmount = false" :closeable="false">
            <template #header>Register Start Amount</template>
            <template #content>
                <div class="text-left">Please Enter Initial Cash Fund</div>
                <div class="dark:bg-gray-700 p-4 rounded-md">
                    <div class="text-left text-4xl font-bold">Cash Breakdown</div>
                    <div class="grid grid-cols-5 gap-4 mt-4">
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 1,000.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="one_thousands" autofocus />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 500.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="five_hundreds" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 200.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="two_hundreds" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 100.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="one_hundreds" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 50.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="fifties" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 20.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="twenties" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 10.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="tens" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 5.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="fives" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 1.00</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="ones" />
                        </div>
                        <div>
                            <InputLabel class="text-nowrap text-white">&#8369; 0.25</InputLabel>
                            <TextInput id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                                v-model="cents" />
                        </div>

                    </div>
                    <div class="mt-4">
                        <InputLabel>TOTAL AMOUNT</InputLabel>
                        <TextInput readonly id="username" ref="usernameInput" type="number" class="mt-1 block w-full"
                            :value="total" />
                    </div>
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="saveInitialCashAmount">Save</PrimaryButton>
            </template>
        </Modal>

        <!---modal recall item-->
        <Modal :show="modalRecallItem" @close="modalRecallItem = false">
            <template #header>Recall Transaction</template>
            <template #content>
                <div>
                    <TextInput v-model="invoice" class="text-4xl font-bold" />
                    <PrimaryButton @click.prevent="getTransaction" class="ml-4">SHOW</PrimaryButton>
                </div>
                <div>
                    <div class="p-4 md:p-5 space-y-4" v-if="transactionList.length > 0">
                        <PrimaryButton @click.prevent="reprintReceipt">Reprint Receipt</PrimaryButton>
                        <PrimaryButton @click.prevent="voidInvoice" class="ml-4">Void Invoice</PrimaryButton>
                        <table class=" w-full rounded-xl " ref=" modalTable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-wrap">ID</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">PRODUCT NAME</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">QUANTITY</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">PRICE</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">EXTENDED</th>
                                    <th scope="col" class="px-6 py-3 text-wrap">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="overflow-y-scroll">
                                <tr v-for="result in transactionList" :key="result.id"
                                    class="text-nowrap bg-white hover:bg-gray-50 dark:hover:bg-gray-600 hover:cursor-pointer">
                                    <td class="px-6 py-4 text-center">{{ result.id }}</td>
                                    <td class="px-6 py-4 text-center">{{ result.products.product_name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ result.quantity }}</td>
                                    <td class="px-6 py-4 text-center">&#8369; {{
                                        result.products.product_stocks?.selling_price
                                    }}</td>
                                    <td class="px-6 py-4 text-center">&#8369; {{
                                        result.products.product_stocks?.selling_price * result.quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative">
                                            <button @click="toggleDropdown(result.id)"
                                                class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                            <div v-if="dropdownOpen === result.id"
                                                class="absolute right-0 z-10 bg-white rounded-md shadow-lg dark:bg-gray-700">
                                                <ul>
                                                    <li>
                                                        <button @click.prevent="modalRemarks(result.id)"
                                                            class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-teal-400">
                                                            Void
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button @click.prevent="returnPerItem(result.id)"
                                                            class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-teal-400">
                                                            Return
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else-if="noTrans">
                        <div class="dark:bg-red-700 rounded-md p-4">
                            Transaction with the given invoice number was not found.
                        </div>

                    </div>
                </div>
            </template>
        </Modal>
        <!---modal return-->
        <Modal :show="showModalReturn" @close="showModalReturn = false">
            <template #header>Return Item</template>
            <template #content>
                <div>
                    <p class="text-2xl font-medium mb-4">Input quantities of return items</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-6xl font-black mb-4">Quantities</label>
                    <TextInput v-model="returnItemQuantity" class="text-center text-6xl" />
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="returnItem">Save</PrimaryButton>
                <DeleteButton @click.prevent="showModalReturn = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal remarks-->
        <Modal :show="showModalRemarks" @close="showModalRemarks = false">
            <template #header>Remarks</template>
            <template #content>
                <div>
                    <p class="text-4xl font-black">Choose a remarks</p>
                </div>
                <div>
                    <select name="" id="" v-model="remarks">
                        <option value="">-- Select option here --</option>
                        <option :value="remark" v-for="(remark, key) in props.remarks" :key="key">{{ remark }}
                        </option>
                    </select>
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="voidPerItem">Save</PrimaryButton>
                <DeleteButton @click.prevent="showModalRemarks = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!--- modal payment change-->
        <Modal :show="modalPaymentChange" @close="modalPaymentChange = false">
            <template #header>PAYMENT CHANGE</template>
            <template #content>
                <label for="" class="text-4xl font-black text-center">CHANGE</label>
                <div>
                    <TextInput :value="`&#8369; ${paymentChange.toFixed(2)}`" readonly
                        class="text-4xl font-black text-center" />
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="tenderChange">DONE</PrimaryButton>
            </template>
        </Modal>
        <!--- modal pin-->

        <Toast />
    </PointOfSaleLayout>
</template>
<script setup>
import AnalogClock from '@/Components/AnalogClock.vue'
import { Head } from '@inertiajs/vue3'
import Textarea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SearchField from '@/Components/SearchField.vue';
import PointOfSaleLayout from '@/Layouts/PointOfSaleLayout.vue';
import { ref, computed, onMounted } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import useFocusTrap from '@/Composable/useFocusTrap';
import { watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import { useForm, router } from '@inertiajs/vue3';
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';
import axios from 'axios';
import ActionButton from '@/Components/ActionButton.vue';
import TransactionTotal from '@/Components/TransactionTotal.vue';
import usePinAuthorization from '@/Composable/usePinAuthorization'
import useGetDiscount from '@/Composable/useGetDiscount'
import fOne, {getSellingPrice} from "@/util.js";

const resetTrans = ref(false)
const lastScanItemName = ref('')
const lastScanItemPrice = ref(0)
const remarkInitial = ref('')
const searchQuery = ref('');
const discountList = ref([])
const modalPrint = ref(false)
const modalChangeQuantity = ref(false)
const modalChangePrice = ref(false)
const modalOnHold = ref(false)
const modalUnHold = ref(false)
const modalPayment = ref(false)
const modalAllItemDiscount = ref(false)
const modalStartAmount = ref(false)
const modalRecallItem = ref(false)
const productId = ref('')
const amountDue = ref(0)
const discountApplied = ref(false)
const tender = ref('')
const showOk = ref(false)
const disableButton = ref('')
const invoice = ref('')
const transactionList = ref([])
const salesId = ref(0)
const remarks = ref('')
const pinAuth = ref('')


const results = ref([]);
const showModal = ref(false);
const showModalQuantity = ref(false);
const searchResults = ref([]);
const productName = ref('');
const productQuantity = ref('');
const selectedRow = ref(0);
const newDataResult = ref([])
const changeQuantity = ref('')
const changePrice = ref('')
const discountValue = ref(0)
const showModalRemarks = ref(false)
const showModalReturn = ref(false)
const modalPinAuthorization = ref(false)
const authError = ref(false)


const { trapRef } = useFocusTrap();
let formData = ref([]);
const one_thousands = ref('')
const five_hundreds = ref('')
const two_hundreds = ref('')
const one_hundreds = ref('')
const fifties = ref('')
const twenties = ref('')
const tens = ref('')
const fives = ref('')
const ones = ref('')
const cents = ref('')
const noTrans = ref(false)
const returnItemQuantity = ref(0)
const modalPaymentChange = ref(false)
const paymentChange = ref(0)
const modalToBeOpen = ref('')


const props = defineProps({
    remarks: {
        type: Array,
        required: true,
    }
})
console.log(props.remarks)
const actionButtonList = ref([
    { name: 'change-quantity', label: 'Change Quantity', modal: 'modalChangeQuantity', hotkey: 'ALT+2' },
    { name: 'change-price', label: 'Change price', modal: 'modalChangePrice', hotkey: 'ALT+3' },
    { name: 'hold', label: 'Hold Transaction', modal: 'modalOnHold', hotkey: 'ALT+4' },
    { name: 'retrieve', label: 'Retrieve Transaction', modal: 'modalUnHold', hotkey: 'ATL+5' },
    { name: 'payment', label: 'Payment', modal: 'modalPayment', hotkey: 'ALT+6' },
    { name: 'discount', label: 'Discount', modal: 'modalAllItemDiscount', hotkey: 'ALT+7' },
    { name: 'register-amount', label: 'Register Amount', modal: 'modalStartAmount', hotkey: 'ALT+9' },
    { name: 'recall', label: 'Recall', modal: 'modalRecallItem', hotkey: 'ALT+0' },

])

const modalToOpen = () => {
    if (modalToBeOpen.value === 'discount') {
        getDiscount()
    } else if (modalToBeOpen.value === 'change_price') {
        modalChangePrice.value = true
    } else if (modalToBeOpen.value === 'start_amount') {
        modalStartAmount.value = true
    }
}
const authorization = async () => {
    console.log("pin.value: ", pinAuth.value)
    const { authPin, getPin } = usePinAuthorization(pinAuth.value)
    await getPin()
    console.log("response pin: ", authPin)
    console.log("response pin value: ", authPin.value.length)
    if (authPin.value.length > 0) {
        modalToOpen()
        modalPinAuthorization.value = false
    } else {
        toast.add({
            message: 'Incorrect PIN',
            status: false
        })
        pinAuth.value = ''
    }
}
const getDiscount = async () => {
    const { discount, getDiscount } = useGetDiscount()
    await getDiscount()
    if (discount.value.length > 0) {
        discountList.value = discount.value
        modalAllItemDiscount.value = true
    } else {
        toast.add({
            message: 'No discount has been created.',
            status: false
        })
    }
}
const actionButtonClick = (e) => {
    if (formData.value.length > 0) {
        productId.value = formData.value[selectedRow.value].id
        if (e === 'modalChangeQuantity') {
            modalChangeQuantity.value = true
        } else if (e === 'modalChangePrice') {
            modalToBeOpen.value = 'change_price'
            modalPinAuthorization.value = true
        } else if (e === 'modalPayment') {
            if (!discountApplied.value) {
                amountDue.value = formData.value.reduce((amount, amountPerItem) => {
                    return amount + (amountPerItem.selling_price * amountPerItem.quantity)
                }, 0)
            } else {
                amountDue.value = formData.value.reduce((amount, amountPerItem) => {
                    return amount + (amountPerItem.selling_price * amountPerItem.quantity)
                }, 0)
                let multAmount = (amountDue.value * discountValue.value)
                let discountedAmount = amountDue.value - multAmount
                amountDue.value = discountedAmount
            }
            tender.value = ''
            modalPayment.value = true
        } else if (e === 'modalAllItemDiscount') {
            pinAuth.value = ''
            modalToBeOpen.value = 'discount'
            modalPinAuthorization.value = true
        } else if (e === 'modalOnHold') {
            modalOnHold.value = true
        }
    }
    if (e === 'modalStartAmount') {
        modalToBeOpen.value = 'start_amount'
        modalPinAuthorization.value = true
    } else if (e === 'modalRecallItem') {
        modalRecallItem.value = true
    } else if (e === 'modalUnHold') {
        modalUnHold.value = true
    }

}
const modalRemarks = (e) => {
    salesId.value = e
    showModalRemarks.value = true
    remarkInitial.value = 'void_one'
}

const voidPerItem = async () => {
    if (remarkInitial.value === 'void_one') {
        const response = await axios.patch(`/void_item/${salesId.value}`, { remarks: remarks.value })
        if (response.data) {
            let result = transactionList.value.find(list => list.id === salesId.value)
            if (result !== undefined) {
                transactionList.value.splice(result, 1)
            }
        }
        showModalRemarks.value = false
        toast.add({
            message: "Success! Voiding item complete.",
            status: true
        })
    } else if (remarkInitial.value === 'return_one') {
        console.log("return")
        const response = await axios.patch(`/return_item/${salesId.value}`, {
            quantity: returnItemQuantity.value,
            remarks: remarks.value
        })
        if (response.data) {
            let result = transactionList.value.find(list => list.id === salesId.value)
            if (result !== undefined) {
                result.quantity -= returnItemQuantity.value
                if (result.quantity === 0) {
                    transactionList.value.splice(result, 1)
                }
            }
        }
        showModalRemarks.value = false
        showModalReturn.value = false
        toast.add({
            message: "Success! Returning item complete.",
            status: true
        })
    } else if (remarkInitial.value === 'void_invoice') {
        const response = await axios.patch('/void_invoice', {
            invoice_number: invoice.value,
            remarks: remarks.value
        })
        if (response.data) {
            transactionList.value = []
        }
        showModalRemarks.value = false
        toast.add({
            message: "Success! Voiding the transaction.",
            status: true
        })
    }


}
const returnPerItem = (e) => {
    salesId.value = e
    showModalReturn.value = true
}
const returnItem = async () => {
    remarkInitial.value = 'return_one'
    showModalRemarks.value = true
}
const reprintReceipt = () => {
    console.log("reprint receipt")
}
const voidInvoice = () => {
    showModalRemarks.value = true
    remarkInitial.value = 'void_invoice'
}
const saveQuantityChange = () => {
    if (formData.value.length) {
        let result = formData.value.find(form => form.id === productId.value)
        if (result !== undefined) {
            result.quantity = changeQuantity.value
        }
    }
    changeQuantity.value = ''
    modalChangeQuantity.value = false
    toast.add({
        message: 'Success! Item quantity has changed',
        status: true
    })
}
const savePriceChange = () => {
    if (formData.value.length) {
        let result = formData.value.find(form => form.id === productId.value)
        if (result !== undefined) {
            result.selling_price = changePrice.value
        }
    }
    changePrice.value = ''
    modalChangePrice.value = false
    toast.add({
        message: 'Success! Item price has changed.',
        status: true
    })
}
const selectDiscount = (discount) => {
    amountDue.value = formData.value.reduce((amount, amountPerItem) => {
        return amount + (amountPerItem.selling_price * amountPerItem.quantity)
    }, 0)
    discountValue.value = discount
    let multAmount = (amountDue.value * discount)
    let discountedAmount = amountDue.value - multAmount
    amountDue.value = discountedAmount
    modalAllItemDiscount.value = false
    discountApplied.value = true
}
const holdTransaction = () => {
    if (formData.value.length) {
        if (sessionStorage.getItem("hold_trans") === null) {
            sessionStorage.setItem("hold_trans", JSON.stringify(formData.value))
            formData.value = []
            resetTrans.value = true
            toast.add({
                message: 'Success! Transaction is being on hold.',
                status: true
            })
        } else {
            toast.add({
                message: 'Fail! Finish the previous on hold transaction first.',
                status: false
            })
        }
        modalOnHold.value = false
    }

}
const unHoldTransaction = () => {
    if (!formData.value.length) {
        if (sessionStorage.getItem("hold_trans") === null) {
            toast.add({
                message: 'Fail! No transaction is on hold.',
                status: false
            })
        } else {
            const holdTrans = sessionStorage.getItem('hold_trans')
            formData.value = JSON.parse(holdTrans)
            sessionStorage.removeItem('hold_trans')
            toast.add({
                message: 'Success! On hold transaction has been retrieved.',
                status: true
            })
        }
    }
    modalUnHold.value = false
}
const goToSaveModal = () => {
    modalPayment.value = false
    modalPrint.value = true
    // tender.value = ''
    showOk.value = false
    discountValue.value = 0
}
const tenderChange = () => {
    paymentChange.value = 0
    modalPaymentChange.value = false
}
const getTransaction = async () => {
    const response = await axios.get('/get_transaction', {
        params: { query: invoice.value }
    });
    transactionList.value = response.data
    if (response.data.length === 0) {
        noTrans.value = true
    }
}
const saveToDb = (e) => {
    const form = useForm({ fields: formData.value })
    form.post(route('sale.store'), {
        onSuccess: () => {
            toast.add({
                message: "Success! Transaction complete.",
                status: true
            })
            paymentChange.value = tender.value - amountDue.value
            modalPaymentChange.value = true
        },
        onError: (error) => {
        }
    })
    modalPrint.value = false
    formData.value = []
}
const saveInitialCashAmount = () => {
    sessionStorage.setItem("initial-cash-register", total.value);
    modalStartAmount.value = false
    if (sessionStorage.getItem("initial-cash-register") !== null) {
        toast.add({
            message: 'Success! Initial cash fund has been saved.',
            status: true
        })
    }
}
const setResults = (newResults) => {
    showModalQuantity.value = true
    productName.value = newResults.product_name
    newDataResult.value = newResults
    console.log("newData: ", newDataResult.value)
};
const filteredProducts = computed(() => {
    if (searchQuery.value) {
        return results.value;
    }
});
const highlightRow = (index) => {
    selectedRow.value = 0;
};

const keyPressEnterChangeRow = (event) => {
    event.stopPropagation();
};
const tableResultClick = (event) => {
    console.log(event);
    selectedRow.value = event
    console.log("selectedRow.value[event]: ", selectedRow.value)
}

const closeModal = (modal) => {
    searchResults.value = [];
    transactionList.value = []
    document.querySelector('.search-field').focus()
};
const addProduct = () => {
    console.log(newDataResult)
    resetTrans.value = false
    if (formData.value.length > 0) {
        let result = formData.value.find(form => form.id === newDataResult.value.id)

        if (result !== undefined) {
            result.quantity += productQuantity.value
        } else {
            console.log(result)
            formData.value.unshift({
                id: newDataResult.value.id,
                name: newDataResult.value.product_name,
                quantity: productQuantity.value,
                selling_price: getSellingPrice(newDataResult.value.product_stocks),
            });
        }
    } else {
        formData.value.push({
            id: newDataResult.value.id,
            name: newDataResult.value.product_name,
            quantity: productQuantity.value,
            selling_price: getSellingPrice(newDataResult.value.product_stocks),
        });
    }
    lastScanItemName.value = formData.value[0].name
    lastScanItemPrice.value = formData.value[0].selling_price
    productQuantity.value = ''
    showModalQuantity.value = false;
    selectedRow.value = 0;
    console.log(formData)
}

onMounted(() => {
    if (sessionStorage.getItem("initial-cash-register") === null) {
        modalStartAmount.value = true
    }
    window.addEventListener('keyup', (event) => {
        if (event.altKey && event.key == '5') {
            if (sessionStorage.getItem("hold_trans") != null) {
                modalUnHold.value = true
            } else {
                toast.add({
                    message: "No transaction on hold",
                    status: false
                })
            }
        } else if (event.altKey && event.key == '0') {
            modalRecallItem.value = true
        } else if (event.altKey && event.key == '9') {
            modalToBeOpen.value = 'start_amount'
            modalPinAuthorization.value = true
        }
    })
})

watch(formData.value, (newForm) => {
    if (newForm && !showModalQuantity.value && !showModal.value) {
        window.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowUp' && selectedRow.value > 0) {
                event.stopImmediatePropagation()
                selectedRow.value--
                console.log("arrowup: ", event)
            } else if (event.key === 'ArrowDown' && selectedRow.value < formData.value.length - 1) {
                event.stopImmediatePropagation()
                selectedRow.value++
                const scrollableDiv = document.getElementById('scrollable-div')
                scrollableDiv.scrollTop -= 10
                console.log("arrowdown: ", event)
            } else if (event.altKey && event.key === '1') {
                modalPrint.value = true
            } else if (event.altKey && event.key === '2') {
                productId.value = formData.value[selectedRow.value].id
                modalChangeQuantity.value = true
            } else if (event.altKey && event.key == '3') {
                productId.value = formData.value[selectedRow.value].id
                modalToBeOpen.value = 'change_price'
                modalPinAuthorization.value = true
            } else if (event.altKey && event.key == '4') {
                modalOnHold.value = true
            } else if (event.altKey && event.key == '6') {
                if (!discountApplied.value) {
                    amountDue.value = formData.value.reduce((amount, amountPerItem) => {
                        return amount + (amountPerItem.selling_price * amountPerItem.quantity)
                    }, 0)
                } else {
                    amountDue.value = formData.value.reduce((amount, amountPerItem) => {
                        return amount + (amountPerItem.selling_price * amountPerItem.quantity)
                    }, 0)
                    let multAmount = (amountDue.value * discountValue.value)
                    let discountedAmount = amountDue.value - multAmount
                    amountDue.value = discountedAmount
                }
                tender.value = ''
                modalPayment.value = true
            } else if (event.altKey && event.key == '7') {
                pinAuth.value = ''
                modalToBeOpen.value = 'discount'
                modalPinAuthorization.value = true
            } else if (event.altKey && event.key == '8') {
                modalPerItemDiscount.value = true
            }
        })

    }

})
watch(tender, (newTender) => {
    if (newTender >= amountDue.value) {
        showOk.value = true
    }
})
const dropdownOpen = ref(null);
const toggleDropdown = (id) => {
    dropdownOpen.value = dropdownOpen.value === id ? null : id;
};
const total = computed(() => {
    const totalOneThousands = parseInt(one_thousands.value) * 1000 || 0
    const totalFiveHundreds = parseInt(five_hundreds.value) * 500 || 0
    const totalTwoHundreds = parseInt(two_hundreds.value) * 200 || 0
    const totalOneHundreds = parseInt(one_hundreds.value) * 100 || 0
    const totalFifties = parseInt(fifties.value) * 50 || 0
    const totalTwenties = parseInt(twenties.value) * 20 || 0
    const totalTens = parseInt(tens.value) * 10 || 0
    const totalFives = parseInt(fives.value) * 5 || 0
    const totalOnes = parseInt(ones.value) * 1 || 0
    const totalCents = parseInt(cents.value) * 0.25 || 0
    return totalOneThousands + totalFiveHundreds + totalTwoHundreds + totalOneHundreds + totalFifties + totalTwenties + totalTens + totalFives + totalOnes + totalCents
})
fOne();
</script>


<style>
.highlight {
    background-color: yellow;
}

.modal {
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    /* Could be more or less, depending on screen size */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
.modal-n {
    background-color: rgb(31 41 55 / 0.9%);
}
</style>
