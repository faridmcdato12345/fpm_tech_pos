import {ref} from "vue";

export default function useAddProduct(){
    let formData = ref([])

    const add = () => {
        if(formData.value.length === 0) {
            return formData.value.push({

            })
        }
    }

    return {
        formData,
        add
    }
}
