<template>
    <div>
        <VueDatePicker v-model="date" range :preset-dates="presetDates" @update:model-value="emitDate">
            <template #preset-date-range-button="{ label, value, presetDate }">
                <span role="button" :tabindex="0" @click="presetDate(value)" @keyup.enter.prevent="presetDate(value)"
                    @keyup.space.prevent="presetDate(value)">
                    {{ label }}
                </span>
            </template>
        </VueDatePicker>
    </div>
</template>

<script setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths, format } from 'date-fns';
import { onMounted, ref } from 'vue';


const date = ref(new Date());
const emit = defineEmits(['update-date'])


const emitDate = (newDate) => {
    emit('update-date', newDate);
};
const presetDates = ref([
    { label: 'Today', value: [new Date(), new Date()] },
    {
        label: 'Today (Slot)',
        value: [new Date(), new Date()],
        slot: 'preset-date-range-button'
    },
    { label: 'This month', value: [startOfMonth(new Date()), endOfMonth(new Date())] },
    {
        label: 'Last month',
        value: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
    },
    { label: 'This year', value: [startOfYear(new Date()), endOfYear(new Date())] },
]);
const handleDate = (modelData, event) => {
    console.log("event: ", event)
    emit('datePick', format(new Date(modelData), 'M-d-yyyy'))
}

</script>

<style scoped></style>