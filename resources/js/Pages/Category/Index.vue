<template>

    <Head title="Categories" />
    <AuthenticatedLayout>
        <BreadCrumb title="Categories" :bread-crumbs="breadCrumbs">
            <PrimaryButton @click.prevent="showModalCreate = true">Create</PrimaryButton>
        </BreadCrumb>
        <div class="pl-12 pr-4 py-4">
            <DataTable :data="props.categories.data" :columns="columns" :pagination="props.categories.meta"
                :route-edit="editRoute" :route-delete="deleteRoute" @limit-query="limitQuery"
                @search-field-query="searchFieldQuery" @delete-product="deleteProduct" @edit-product="editProduct"
                :query-limit="props.queryLimit" :route-create="createRoute" :query-name="props.queryName"
                :action="true" />
        </div>
        <!---modal create category-->
        <Modal :show="showModalCreate" @close="showModalCreate = false">
            <template #header>Create Category</template>
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
                <PrimaryButton @click.prevent="handleFormSubmit">Create Category</PrimaryButton>
                <DeleteButton @click.prevent="showModalCreate = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <!---modal edit category-->
        <Modal :show="showModalEdit" @close="showModalEdit = false">
            <template #header>Edit Category</template>
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
                <PrimaryButton @click.prevent="handleFormUpdate">Update Category</PrimaryButton>
                <DeleteButton @click.prevent="showModalEdit = false" class="ml-2">Cancel</DeleteButton>
            </template>
        </Modal>
        <Toast />
    </AuthenticatedLayout>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import BreadCrumb from '@/Components/Breadcrumb.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';
import { onMounted, ref } from 'vue';
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import DeleteButton from '@/Components/DeleteButton.vue';

const formError = ref({})
const catId = ref('')
const page = usePage()
const showModalCreate = ref(false)
const showModalEdit = ref(false)
const props = defineProps({
    categories: {
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
const formData = useForm({
    name: '',
    status: ''
})
const handleFormSubmit = () => {
    formData.post(route('category.store'), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                message: "Success! Category has been created",
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
    formData.patch(route('category.update', catId.value), {
        preserveState: true,
        onSuccess: () => {
            toast.add({
                message: "Success! Category has been updated",
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
const columns = ref([
    { label: 'ID', key: 'id' },
    { label: 'NAME', key: 'name' },
    { label: 'CREATED AT', key: 'createdAt' },
])
const breadCrumbs = ref([
    { label: 'Category', route: route('category.index'), id: 1 }
])
const editRoute = ref('category.edit')
const deleteRoute = ref('category.destroy')
const createRoute = ref('category.create')

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
    router.get(route('category.index', params))
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
    router.get(route('category.index', params))
}
const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('category.destroy', id), {
            onSuccess: () => {
                toast.add({
                    message: "Category has been deleted.",
                    status: true
                })
            }
        })
    }

}
const editProduct = async (id) => {
    catId.value = id
    await axios.get(`/category/${id}`).then((response) => {
        formData.name = response.data.name
        formData.status = response.data.status
    })
    showModalEdit.value = true
}
</script>
<style lang="">

</style>