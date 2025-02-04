<template>

    <Head title="Units" />
    <AuthenticatedLayout>
        <BreadCrumb title="Units" :bread-crumbs="breadCrumbs">
            <Link :href="route(createRoute)">
            <PrimaryButton @click.prevent="showModalCreate = true">Create</PrimaryButton>
            </Link>
        </BreadCrumb>
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.units.data" :columns="columns" :pagination="props.units.meta"
                :route-edit="editRoute" :route-delete="deleteRoute" @limit-query="limitQuery"
                @search-field-query="searchFieldQuery" @delete-product="deleteProduct" @edit-product="editProduct"
                :query-limit="props.queryLimit" :route-create="createRoute" :query-name="props.queryName"
                :action="true" />
        </div>
        <!---modal create unit-->
        <Modal :show="showModalCreate" @close="showModalCreate = false">
            <template #header>Create Unit</template>
            <template #content>
                <InputLabel for="name" value="Name" class="text-left" />
                <input id="status" type="text" v-model="formData.name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                <InputLabel for="status" value="Status" class="text-left mt-4" />
                <select name="" id="" v-model="formData.status"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                    <option value="">Select status...</option>
                    <option value="Active">Active</option>
                    <option value="Active">Inactive</option>
                </select>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="handleFormSubmit">Create Unit</PrimaryButton>
                <DeleteButton @click.prevent="showModalCreate = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal edit unit-->
        <Modal :show="showModalEdit" @close="showModalEdit = false">
            <template #header>Edit Unit</template>
            <template #content>
                <InputLabel for="name" value="Name" class="text-left" />
                <input id="status" type="text" v-model="formData.name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm" />
                <InputLabel for="status" value="Status" class="text-left mt-4" />
                <select name="" id="" v-model="formData.status"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-teal-400 dark:focus:ring-teal-400 rounded-md shadow-sm">
                    <option value="">Select status...</option>
                    <option value="Active">Active</option>
                    <option value="Active">Inactive</option>
                </select>
            </template>
            <template #footer>
                <PrimaryButton @click.prevent="handleFormUpdate">Update Unit</PrimaryButton>
                <DeleteButton @click.prevent="showModalEdit = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { onMounted, ref } from 'vue';
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import DeleteButton from '@/Components/DeleteButton.vue';

const props = defineProps({
    units: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    },
    queryLimit: {
        type: Number,
        required: true,
        default: 10
    },
    queryName: {
        type: String,
        required: true,
        default: null
    }
})
const unitId = ref('')
const page = usePage()
const showModalCreate = ref(false)
const showModalEdit = ref(false)
const formData = useForm({
    name: '',
    status: ''
})
const handleFormSubmit = () => {
    formData.post(route('unit.store'), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                message: "Success! Unit has been created",
                status: true
            })
        },
        onError: (errors) => {
            formError.value = errors
            Object.values(errors).forEach(error => {
                toast.add({
                    message: "Failed! " + error,
                    status: false
                })
            })
        }
    })
};
const handleFormUpdate = (event) => {
    formData.patch(route('unit.update', unitId.value), {
        preserveState: true,
        onSuccess: () => {
            toast.add({
                message: "Success! Unit has been updated",
                status: true
            })
        },
        onError: (errors) => {
            formError.value = errors
            Object.values(errors).forEach(error => {
                toast.add({
                    message: "Failed! " + error,
                    status: false
                })
            })
        }
    })
    showModalEdit.value = !showModalEdit.value
};
onMounted(() => {
    if (page.props.toast) {
        toast.add({
            message: page.props.toast.message,
            status: page.props.toast.status
        })
    }
})

const showModal = ref(false)
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'NAME', key: 'name' },
    { label: 'CREATED AT', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'Unit', route: route('unit.index'), id: 1 }
])
const editRoute = ref('unit.edit')
const deleteRoute = ref('unit.destroy')
const createRoute = ref('unit.create')

const limitQuery = (name, e) => {
    let qParams = {}
    let nParams = {}
    let params = {}
    if (props.queryName) {
        nParams['name'] = props.queryName
        qParams[name] = e

        params = { ...qParams, ...nParams }
    } else if (!props.queryName) {
        qParams[name] = e
        params = qParams
    } else {
        delete qParams[name]
        delete nParams['name']
    }
    router.get(route('unit.index', params))
}
const searchFieldQuery = (name, e) => {
    let qParams = {}
    let nParams = {}
    let params = {}
    if (props.queryLimit) {
        nParams[name] = e
        qParams['query'] = props.queryLimit

        params = { ...nParams, ...qParams }
    } else if (!props.queryLimit) {
        nParams[name] = e
        params = nParams
    } else {
        delete qParams[name]
        delete nParams['name']
    }
    router.get(route('unit.index', params))
}
const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this unit?')) {
        router.delete(route('unit.destroy', id), {
            onSuccess: () => {
                toast.add({
                    message: "Unit has been deleted.",
                    status: true
                })
            }
        })
    }

}
const editProduct = async (id) => {
    unitId.value = id
    await axios.get(`/unit/${id}`).then((response) => {
        formData.name = response.data.name
        formData.status = response.data.status
    })
    showModalEdit.value = true
}
</script>
<style lang="">

</style>