import axios from "axios";
import { ref } from "vue";

export default function useGetDiscount() {
    const discount = ref([]);

    const getDiscount = async () => {
        let response = await axios.get("/discount");
        discount.value = response.data;
    };

    return {
        discount,
        getDiscount,
    };
}
