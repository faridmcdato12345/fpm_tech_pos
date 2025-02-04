<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Cliform from '@/Components/Cliform.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});



const progress = ref(0);
const { props } = usePage();

onMounted(() => {
    const interval = setInterval(() => {
        if (progress.value >= 100) {
            clearInterval(interval); // Stop the interval once it reaches 100%
            //redirectToLogin();
        } else {
            progress.value++;
        }
    }, 50); // Adjust timing to control the speed of the progress
});
const redirectToLogin = () => {
    router.visit('/login')
}

// const messages = ['🎉 Welcome to IMS & POS App! 🎉
//                     Hi there! We're absolutely thrilled to have you join our community. 💫
//                     of possibilities with IMS & POS App, and we can't wait to see all the amazing things you'll do !
//                     🚀
//                     If you have any questions, need help getting started, or just want to say hello, our support team is
//                     here for you anytime.
//                     Enjoy exploring and have fun! Let’s make magic happen together! ✨

//                     With excitement,

//     The FPM Tech Team']
</script>

<template>

    <Head title="Loading" />
    <div class="bg-black text-white h-screen flex flex-col items-center justify-center space-y-6">
        <img src="assets/images/logo.png" alt="" srcset="" class="w-40" :class="progress <= 99 ? 'animate-bounce' : ''">
        <!-- Loading text -->
        <div class="flex justify-between w-full max-w-md px-4">
            <span class="text-xl font-mono">LOADING...</span>
            <span class="text-xl font-mono">{{ progress }}%</span>
        </div>

        <!-- Loading bar -->
        <div class="w-full max-w-md h-6 border border-white relative">
            <div class="bg-white h-full" :style="{ width: `${progress}%` }"></div>
        </div>
        <!-- <Stepper2 /> -->
        <!-- <div class="border border-teal-400 h-screen w-1/2 greeting"> -->
        <!-- <Typewriter class="typewriter" :options="{ delay: 75, loop: true }" :text="messages">

            </Typewriter> -->
        <!--
        </div> -->
        <Cliform />
    </div>
</template>
<style scoped></style>
