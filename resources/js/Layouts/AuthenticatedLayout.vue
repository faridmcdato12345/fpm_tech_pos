<script setup>
import { computed, onMounted, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, router } from '@inertiajs/vue3';
// import SidebarProvider from "@/Components/Shadcn/ui/sidebar/SidebarProvider.vue";
// import AppSidebar from "@/Components/AppSidebar.vue";
// import SidebarTrigger from "@/Components/Shadcn/ui/sidebar/SidebarTrigger.vue";


const showingNavigationDropdown = ref(false);

const isMinimized = ref(false);

const dropDown = ref(false)
const dropDownMethod = () => {
    dropDown.value = !dropDown.value
}
const isActive = ref(false)

const classes = computed(() => {
    isActive.value ? 'dark:bg-teal-700' : ''
})

const toggleMinimize = () => {
    isMinimized.value = !isMinimized.value;
};
const getActive = (e) => {
    return e == true ? 'dark:bg-teal-700' : ''
}
onMounted(() => {
    if (route().current('dashboard') || route().current('sales.dashboard')) {
        dropDown.value = true
    }
})
</script>
<style scoped>
h6 .after-line {
    content: "";
    flex: 1 1 auto;
    margin-left: 5px;
    border-top: 1px solid #ffffff;
}

::-webkit-scrollbar {
    width: 5px;
    height: 50px;
    border-radius: 100px;
}

::-webkit-scrollbar-thumb {
    background: rgb(16, 102, 88);
}
</style>
<template>
    <div>
        <div class="min-h-screen bg-black dark:bg-gray-900">
            <div class="min-h-screen flex">
                <div :class="{
                    'w-[20%]': !isMinimized,
                    'w-[5%]': isMinimized
                }" class="min-h-screen dark:bg-teal-300 transition-all duration-300 text-white">
                    <div class="flex flex-col h-full divide-y divide-teal-400">
                        <div class="">
                            <div class="shrink-0 flex items-center justify-center  p-2">
                                <Link :href="route('dashboard')" class="rounded-full dark:bg-teal-700 p-2">
                                <ApplicationLogo class="text-gray-800 dark:text-gray-200" />
                                </Link>
                                <button @click="toggleMinimize" class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 12h16m-7-7l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div
                            class="min-h-screen dark:bg-teal-300 overflow-hidden hover:overflow-y-auto transition-all duration-300">
                            <nav class="p-4 max-h-screen">
                                <div class="relative">
                                    <!-- Navigation Links -->
                                    <div class="space-y-4 text-md font-black">
                                        <!--- dashboard menu -->
                                        <div class="text-white">
                                            <div>
                                                <h6 class="flex items-center">Menu<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <button type="button"
                                                    class="hover:border-teal-100 hover:border text-white flex items-center w-full p-2 text-base transition duration-75 rounded-lg group"
                                                    aria-controls="dropdown-example"
                                                    data-collapse-toggle="dropdown-example"
                                                    @click.prevent="dropDownMethod">
                                                    <img src="/assets/icons/svg/dashboard.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <span
                                                        class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">Dashboard</span>
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 10 6">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                                    </svg>
                                                </button>
                                                <ul id="dropdown-example" class="py-2 space-y-2" v-if="dropDown">
                                                    <li>
                                                        <Link :href="route('dashboard')"
                                                            :class="getActive(route().current('dashboard'))"
                                                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:border-teal-100 hover:border text-white">
                                                        Admin
                                                        Dashboard</Link>
                                                    </li>
                                                    <li>
                                                        <Link :href="route('sales.dashboard')"
                                                            :class="getActive(route().current('sales.dashboard'))"
                                                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:border-teal-100 hover:border text-white">
                                                        Sales
                                                        Dashboard</Link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--- inventory menu-->
                                        <div class="">
                                            <div>
                                                <h6 class="flex items-center">Inventory<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('product.index'))">
                                                    <img src="/assets/icons/svg/product.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('product.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap  text-white">
                                                            <p>Products</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('product.create'))">
                                                    <img src="/assets/icons/svg/product.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('product.create')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Create Product</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('brand.index'))">
                                                    <img src="/assets/icons/svg/categories.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('brand.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Brands</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('category.index'))">
                                                    <img src="/assets/icons/svg/categories.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('category.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Categories</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('unit.index'))">
                                                    <img src="/assets/icons/svg/unit.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('unit.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Units</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('out_of_stock.index'))">
                                                    <img src="/assets/icons/svg/unit.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('out_of_stock.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Out of Stocks</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/unit.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('unit.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Expired Products</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                            </div>
                                        </div>
                                        <!---- stock menu-->
                                        <div>
                                            <div>
                                                <h6 class="flex items-center">Stock<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('stock.manage.index'))">
                                                    <img src="/assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('stock.manage.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Manage Stock</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('adjust_stock.index'))">
                                                    <img src=" /assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('adjust_stock.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Adjust Stock</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                            </div>

                                        </div>
                                        <!---- Sales menu-->
                                        <div>
                                            <div>
                                                <h6 class="flex items-center">Sales<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('sale.index'))">
                                                    <img src="/assets/icons/svg/sales.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('sale.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Sales</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('return.item.index'))">
                                                    <img src="/assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('return.item.index')"
                                                        :active="route().current('return.item.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Return Items</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('voided_transaction.index'))">
                                                    <img src="/assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('voided_transaction.index')"
                                                        :active="route().current('voided_transaction.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Voided Transactions</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('pos.index'))">
                                                    <img src="/assets/icons/svg/point_of_sales.svg" width="24"
                                                        height="24" alt="dashboard">
                                                    <NavLink :href="route('pos.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Point of Sales</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('ai.index'))">
                                                    <img src="/assets/icons/svg/point_of_sales.svg" width="24"
                                                        height="24" alt="dashboard">
                                                    <NavLink :href="route('ai.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Artificial Intelegence</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                            </div>
                                        </div>
                                        <!---- Reports menu-->
                                        <div>
                                            <div>
                                                <h6 class="flex items-center">Reports<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer"
                                                    :class="getActive(route().current('sale.index'))">
                                                    <img src="/assets/icons/svg/sales.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('sale.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Sales Report</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base  rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('dashboard')"
                                                        :active="route().current('dashboard')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Purchased Report</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/supplier.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('dashboard')"
                                                        :active="route().current('dashboard')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Inventory Report</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/point_of_sales.svg" width="24"
                                                        height="24" alt="dashboard">
                                                    <NavLink :href="route('pos.index')"
                                                        :active="route().current('pos.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Income Report</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/point_of_sales.svg" width="24"
                                                        height="24" alt="dashboard">
                                                    <NavLink :href="route('pos.index')"
                                                        :active="route().current('pos.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Transaction Report</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div
                                                    class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-lg hover:border-teal-100 hover:border hover:cursor-pointer">
                                                    <img src="/assets/icons/svg/point_of_sales.svg" width="24"
                                                        height="24" alt="dashboard">
                                                    <NavLink :href="route('pos.index')"
                                                        :active="route().current('pos.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Profit & Loss</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                            </div>
                                        </div>
                                        <!---- User management menu-->
                                        <div>
                                            <div>
                                                <h6 class="flex items-center">User Management<span
                                                        class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-l"
                                                    :class="getActive(route().current('users.index'))">
                                                    <img src="/assets/icons/svg/sales.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('users.index')"
                                                        :active="route().current('users.index')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Users</p>
                                                        </div>
                                                    </NavLink>
                                                </div>
                                                <div class="flex w-full p-2 text-base dark:hover:bg-teal-700 rounded-l">
                                                    <img src="/assets/icons/svg/roles.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <NavLink :href="route('dashboard')"
                                                        :active="route().current('dashboard')">
                                                        <div
                                                            class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                            <p>Roles & Permission</p>
                                                        </div>

                                                    </NavLink>
                                                </div>
                                            </div>
                                        </div>
                                        <!--- Settings menu -->
                                        <div class="">
                                            <div>
                                                <h6 class="flex items-center">Settings<span class="after-line"></span>
                                                </h6>
                                            </div>
                                            <div>
                                                <button type="button"
                                                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-teal-700"
                                                    aria-controls="dropdown-example"
                                                    data-collapse-toggle="dropdown-example"
                                                    @click.prevent="dropDownMethod">
                                                    <img src="/assets/icons/svg/dashboard.svg" width="24" height="24"
                                                        alt="dashboard">
                                                    <span
                                                        class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap text-white">
                                                        Settings</span>
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 10 6">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                                                    </svg>
                                                </button>
                                                <ul id="dropdown-example" class="py-2 space-y-2" v-if="dropDown">
                                                    <li>
                                                        <a href="#"
                                                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-teal-100 dark:text-white dark:hover:bg-teal-700">
                                                            App Setting</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"
                                                            class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-teal-100 dark:text-white dark:hover:bg-teal-700">
                                                            General Setting</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>

                    </div>
                </div>
                <main class="w-full overflow-hidden border-l-2 border-teal-400">
                    <slot />
                </main>
            </div>
        </div>
    </div>
    <!--    <SidebarProvider>-->
    <!--        <AppSidebar />-->
    <!--        <main>-->
    <!--            <SidebarTrigger />-->
    <!--            {children}-->
    <!--        </main>-->
    <!--    </SidebarProvider>-->
</template>
