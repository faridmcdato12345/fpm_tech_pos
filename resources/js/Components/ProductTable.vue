<template>
    <div class="h-96 overflow-y-scroll border-teal-400 border-2 rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead>
                <tr>
                    <th>BARCODE</th>
                    <th>PRODUCT NAME</th>
                    <th>QUANTITY</th>
                    <th>PRICE</th>
                    <th>EXTENDED</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in formData" :key="item.id" @click="$emit('row-click', index)"
                    class="cursor-pointer">
                    <td>{{ item.barcode }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.selling_price }}</td>
                    <td>{{ item.selling_price * item.quantity }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
defineProps(["formData", "selectedRow"]);
</script>
