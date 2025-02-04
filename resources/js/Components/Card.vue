<template>
    <CardList :lists="result" />
</template>

<script setup>
import { onMounted, ref } from 'vue';
import CardList from './CardList.vue';
import useGetData from '@/Composable/useGetData'

const {
    result,
    getData
} = useGetData()

onMounted(async () => {
    await getData()
})

</script>

<style lang="scss" scoped></style>