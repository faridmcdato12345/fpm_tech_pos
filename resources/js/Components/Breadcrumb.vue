<template>
    <div class="flex justify-between border-b-2 border-gray-900 dark:border-teal-400 mb-2 pl-8 pr-4">
        <div class="p-4">
            <h1 class="text-white text-3xl">{{ props.title }}</h1>
            <div v-for="(breadCrumb, index) in props.breadCrumbs" class="inline-flex justify-center items-center"
                :key="breadCrumb.id">
                <Link :href="breadCrumb.route" class="text-sm text-teal-400 dark:text-teal-400 hover:underline">
                {{ breadCrumb.label }}</Link>
                <span v-if="index !== props.breadCrumbs.length - 1"
                    class="text-teal-400 dark:text-teal-400">&nbsp;>&nbsp;</span>
            </div>
        </div>
        <div class="justify-center items-center flex">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    breadCrumbs: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        required: true
    }
})

</script>

<style lang="scss" scoped></style>