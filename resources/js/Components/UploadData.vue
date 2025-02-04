<template>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Upload Historical Data</h2>

        <!-- File Upload Form -->
        <form @submit.prevent="uploadData">
            <!-- File Input -->
            <div class="mb-4">
                <label class="block mb-2 font-medium">Select CSV File</label>
                <input type="file" @change="handleFileUpload" accept=".csv"
                    class="block w-full border rounded px-3 py-2 focus:ring focus:outline-none" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                :disabled="!file">
                Upload Data
            </button>
        </form>

        <!-- Success/Error Feedback -->
        <div v-if="message" :class="messageType" class="mt-4">
            {{ message }}
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import toast from '@/Composable/toast'
import Toast from '@/Components/Toast.vue';
const file = ref(null); // Holds the selected file
const message = ref(""); // Feedback message
const messageType = ref(""); // Message class (success or error)

const handleFileUpload = (event) => {
    file.value = event.target.files[0];
};

const uploadData = async () => {
    const toast = useToast(); // Optional for better feedback
    if (!file.value) {
        message.value = "Please select a file to upload.";
        messageType.value = "text-red-500";
        return;
    }

    try {
        const formData = new FormData();
        formData.append("file", file.value);

        // Send file to the Laravel backend
        await axios.post("/api/upload-data", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        // Success feedback
        message.value = "File uploaded successfully!";
        messageType.value = "text-green-500";
        toast.add({
            message: "File uploaded successfully!",
            status: true
        })
        file.value = null; // Reset file input
    } catch (error) {
        // Error feedback
        message.value = "Failed to upload file. Please try again.";
        messageType.value = "text-red-500";
        toast.add({
            message: "File upload failed!",
            status: false
        })
        console.error("File upload error:", error);
    }
};
</script>
