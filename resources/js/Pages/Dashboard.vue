<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <BreadCrumb title="Dashboard" :bread-crumbs="breadCrumbs">
            </BreadCrumb>
            <div class="p-12">
                <div class="grid grid-cols-3 rounded-lg">
                    <Card />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import { ref } from 'vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'

const breadCrumbs = ref([
    { label: 'Overview', route: route('dashboard'), id: 1 }
])
</script>
