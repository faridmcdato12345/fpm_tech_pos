<template>

    <Head title="AI" />
    <AuthenticatedLayout>
        <BreadCrumb title="AI Prediction" :bread-crumbs="breadCrumbs">
        </BreadCrumb>
        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6 text-white">Prediction Dashboard</h1>
            <!-- <UploadData /> -->
            <PredictionSettings @prediction-results="handlePrediction" />
            <PredictionResults :prediction-result="predictionResult" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import UploadData from '@/Components/UploadData.vue';
import PredictionSettings from '@/Components/PredictionSettings.vue';
import PredictionResults from '@/Components/PredictionResults.vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'


const predictionResult = ref([])
const breadCrumbs = ref([
    { label: 'AI Sales Prediction', route: route('ai.index'), id: 1 }
])
const handlePrediction = (data) => {
    predictionResult.value = data
    console.log("data: ", data)
}
</script>
