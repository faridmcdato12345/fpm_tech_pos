<template>
    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
        <div class=" mt-4 w-full grid grid-cols-1 gap-4" :class="props.fields.length > 1 ? 'md:grid-cols-2' : ''">
            <div class="flex flex-col" v-for="(field, index) in fields" :key="index">
                <div class="w-full pr-4">
                    <div v-if="!field.select">
                        <InputLabel :for="field.name" :value="field.label" :class="classes" />
                        <input :id="field.name" :type="field.type" v-model="formData[field.name]"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                        <div v-if="formError[field.name]" class="text-red-500 mt-1">{{ formError[field.name] }}</div>
                    </div>
                    <div v-else>
                        <InputLabel :for="field.name" :value="field.label" :class="classes" class="mt-4" />
                        <select class="rounded w-full mt-1" v-model="formData[field.name]">
                            <option disabled value="">-- Select {{ field.label }} --</option>
                            <option v-for="option in field.options" :value="option.id" :key="option.id">{{ option.name
                                }}
                            </option>
                        </select>
                    </div>
                    <div v-if="field.status">
                        <InputLabel :for="field.status" :value="field.status.label" :class="classes" class="mt-4">
                        </InputLabel>
                        <select name="" id=""
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                            <option value="">Select status...</option>
                            <option v-for="option in field.status.options" :key="option.value" :value="option.value">{{
                                option.label }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <DeleteButton v-if="props.backButton" @click="backTo" class="ms-4" type="button">
                Back
            </DeleteButton>
            <DeleteButton v-if="props.backButtonIndex" @click="backToIndex" class="ms-4" type="button">
                Back
            </DeleteButton>
            <PrimaryButton class="ms-4" type="submit" v-if="props.submitButton">
                {{ props.submitButtonText }}
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import { reactive, watchEffect } from "vue"
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue'
import PrimaryButton from "./PrimaryButton.vue";
import DeleteButton from "./DeleteButton.vue"
import { computed, ref } from "vue";
import { onMounted } from "vue";

const props = defineProps({
    fields: {
        type: Array,
        required: true
    },
    submitButtonText: {
        type: String,
        required: true,
        default: 'Submit'
    },
    formError: {
        type: Object,
        default: () => ({})
    },
    backButton: {
        type: Boolean,
        default: false,
    },
    productObj: {
        type: Array,
        default: () => ({})
    },
    backButtonIndex: {
        type: Boolean,
        default: false,
    },
    submitButton: {
        type: Boolean,
        default: false
    },
    status: {
        type: Boolean,
        default: false
    },
    leftlabel: {
        tyoe: Boolean,
        default: false
    }
})
const classes = ref('')
const emit = defineEmits(['submit', 'backTo', 'backToIndex'])
let formData = reactive({})
if (Object.keys(props.productObj).length > 0) {
    for (let i = 0; i < Object.keys(props.productObj).length; i++) {
        let productObjProp = Object.keys(props.productObj)[i]
        for (let x = 0; x < Object.keys(props.fields).length; x++) {
            let fieldProp = Object.values(props.fields)[x].name
            if (productObjProp === fieldProp) {
                formData[productObjProp] = Object.values(props.productObj)[i]
                break;
            }
        }
    }
    formData = ({ product_id: props.productObj.id, ...formData })
}

const handleSubmit = (event) => {
    emit('submit', formData, event)
}
const backTo = () => {
    emit('backTo')
}
const backToIndex = () => {
    emit('backToIndex')
}
watchEffect(() => {
    props.fields.forEach(field => {
        if (!(field.name in formData)) {
            formData[field.name] = '';
        }
    });
});
onMounted(() => {
    classes.value = props.leftlabel ? 'text-left' : 'text-center'
})
</script>

<style scoped></style>