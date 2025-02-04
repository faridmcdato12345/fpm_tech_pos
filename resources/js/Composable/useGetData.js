import axios from "axios";
import { ref } from "vue";

export default function useGetData() {
    const result = ref([]);

    const getData = async () => {
        let response = await axios.get("/dashboard/data");
        result.value = response.data;
    };
    console.log(result.value);
    return { result, getData };
}
