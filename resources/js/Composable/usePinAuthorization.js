import axios from "axios";
import { ref } from "vue";

export default function usePinAuthorization(e) {
    console.log("e: ", e);
    const authPin = ref([]);

    const getPin = async () => {
        let response = await axios.post("/get/pin", {
            pin: e,
        });
        authPin.value = response.data;
    };

    return {
        authPin,
        getPin,
    };
}
