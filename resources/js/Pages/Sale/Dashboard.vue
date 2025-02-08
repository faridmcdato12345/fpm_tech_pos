<template>

    <Head title="Sales Dasboard" />
    <AuthenticatedLayout>
        <div class="p-6 min-h-screen">
            <header
                class="flex items-center justify-between dark:bg-gray-800 p-4 rounded shadow border-b border-b-teal-400">
                <h1 class="text-xl font-semibold text-white">Hi {{ user_name }}, here's what's happening with your
                    store today.</h1>
                <div class="flex items-center space-x-4">
                    <div class="space-x-2 items-center justify-center">
                        <Datepicker @update-date="getDatePick" />
                    </div>
                    <button class="p-2 bg-gray-200 rounded">🔄</button>
                </div>
            </header>

            <section class="my-6 flex w-full space-x-2">
                <div class="w-2/4 border border-teal-400">
                    <div class="col-span-2 dark:bg-gray-800 p-6 rounded shadow">
                        <h2 class="text-2xl font-semibold text-white">Weekly Earning</h2>
                        <p class="text-3xl mt-2 text-white">{{ weekEarnings }}</p>
                        <div class="mt-1">
                            <span :class="percentage > 0 ? 'text-teal-400' : 'text-red-400'">{{ percentage
                                }}%</span>
                            <span class="text-white" v-if="percentage > 0"> increase compare to last week</span>
                            <span class="text-white" v-else> decrease compare to last week</span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 w-2/4 border border-teal-400">
                    <div
                        class="dark:bg-gray-800 p-6 rounded shadow flex items-center justify-between w-2/4 border-r border-teal-400">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">{{ totalSalesNumber }}</h2>
                            <p class="text-teal-400">Grand Total Sales</p>
                        </div>
                        <div class="text-4xl">📈</div>
                    </div>
                    <div class="dark:bg-gray-800 p-6 rounded shadow flex items-center justify-between w-2/4">
                        <div>
                            <h2 class="text-2xl font-semibold text-white">{{ numberSalesToday }}</h2>
                            <p class="text-teal-400">No of Total Sales today</p>
                        </div>
                        <div class="text-4xl">📉</div>
                    </div>
                </div>

            </section>

            <section class="grid grid-cols-2 gap-2">
                <div class="dark:bg-gray-800 p-6 rounded shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-white">Best Seller</h2>
                    </div>
                    <div class="border border-teal-400">
                        <ul>
                            <li class="flex items-center justify-between py-2 px-2 border-b border-teal-400"
                                v-for="seller in topSellers" :key="seller.id">
                                <div class="flex flex-col">
                                    <span class="text-teal-400 uppercase font-bold">{{ seller?.products?.product_name
                                        }}</span>
                                    <span class="text-white">{{ formatCurrency(calculateTotalAmount(seller)) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white font-bold">Sales</span>
                                    <span class="text-teal-400">{{ seller.total_quantity_sold }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="p-6 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-white">Recent Transactions</h2>
                    </div>
                    <div class="relative overflow-x-auto shadow-md border border-teal-400">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-md font-bold text-teal-400 uppercase dark:text-gray-400 border-b border-teal-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Invoice #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sale in recentSales" :key="sale.id"
                                    class="border-b border-teal-400 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium whitespace-nowrap uppercase text-teal-400">
                                        {{ sale.products?.product_name }}
                                    </th>
                                    <td class="px-6 py-4 text-white">
                                        {{ sale.invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        {{ sale.quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        {{ formatCurrency(calculateRecentSaleAmount(sale)) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class=" my-6 w-full dark:bg-gray-800">
                <div class=" p-6 rounded shadow w-full">
                    <h2 class="text-xl font-semibold text-white">Sales Analytics</h2>
                    <!-- Placeholder for chart -->
                    <div class="mt-4">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import { router } from '@inertiajs/vue3';
import Datepicker from '@/Components/Datepicker.vue';
import { Bar, Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    BarElement
} from 'chart.js'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend
)
// Example data for chart
const props = defineProps({
    user_name: String,
    months: Array,
    topSellers: Object,
    recentSales: Object,
    percentage: Number,
    weekEarnings: Number,
    numberSalesToday: Number,
    totalSalesNumber: Number,
    totalSalesAnalytics: Array
})

const selectedDate = ref(null);
const getDatePick = (date) => {
    selectedDate.value = date; // Update the selected date
}
const monthNames = props.months.map(item => item.month_name);
const chartData = {
    labels: monthNames,
    datasets: [
        {
            backgroundColor: '#16beca',
            data: props.totalSalesAnalytics,
            fill: true,
            tension: 0.1,
            borderColor: 'rgb(75, 192, 192)',
        }
    ]
}
const chartOptions = {
    responsive: true
}

const calculateTotalAmount = (seller) => {
    if (seller.products?.product_stocks.length > 0) {
        const sellingPrice = seller.products?.product_stocks[0].selling_price;
        return seller.total_quantity_sold * sellingPrice;
    }
    return 0;
};
const calculateRecentSaleAmount = (seller) => {
    if (seller.products?.product_stocks.length > 0) {
        const sellingPrice = seller.products?.product_stocks[0].selling_price;
        return seller.quantity * sellingPrice;
    }
    return 0;
}
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
};

watch(selectedDate, (newDate) => {
    if (newDate) {
        router.get(route('sales.dashboard'), { date: newDate }, { preserveState: true, preserveScroll: true });
    }
});
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
