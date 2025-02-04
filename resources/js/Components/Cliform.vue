<template>
    <div class="cli-form-container text-teal-400">
        <div class="cli-output">
            <!-- Display previous answers -->
            <div v-for="(output, index) in outputs" :key="index">{{ output }}</div>
        </div>
        <div class="cli-input"
            v-if="!isSubmitting && !showCongrats && (currentFieldIndex < fields.length || isFinalQuestion)">
            <div v-if="notSaveYet">
                <!-- Current question prompt -->
                <span class="prompt">{{ isFinalQuestion ? finalQuestion.label : fields[currentFieldIndex].label }} {{
                    promptText }}</span>
                <input v-model="currentInput" @keydown.enter.prevent="handleSubmit" class="cli-input-field" type="text"
                    autocomplete="off" @input="onInput" spellcheck="false" autofocus
                    :placeholder="showPlaceholder ? 'Press Enter ⏎' : ''" />
                <div v-if="showPressEnter" class="press-enter-text">Press Enter ⏎</div>
            </div>

        </div>
        <div v-else-if="isSubmitting">
            <!-- Show progress bar during form submission -->
            <div class="progress-container">
                <span>Progress</span>
                <span class="progress-bar">[{{ progressBar }}] {{ progress }}%</span>
            </div>
        </div>

        <!-- Congratulations Message -->
        <div v-else-if="showCongrats">
            <div class="congrats-message">
                🎉 Congratulations on completing the first step! 🎉
            </div>
            <div class="follow-up-question">
                <span>Would you like to continue and register an account? (yes/no)</span>
                <input v-model="followUpAnswer" @keydown.enter.prevent="handleFollowUp" @input="onInput" autofocus
                    class="cli-input-field" type="text" />
                <div v-if="showPressEnter" class="press-enter-text">Press Enter ⏎</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const outputs = ref([]); // Stores CLI-like output
const currentInput = ref(""); // Tracks user input
const showPressEnter = ref(false); // Controls the visibility of 'Press Enter' text
const isSubmitting = ref(false); // Tracks submission progress
const progress = ref(0); // Tracks progress percentage
const isFinalQuestion = ref(false); // Tracks if the final question is active
const notSaveYet = ref(true)
const showCongrats = ref(false); // Controls the display of the congrats message
const followUpAnswer = ref(""); // Tracks the user's answer to the follow-up question

const progressBar = computed(() => {
    const totalLength = 30; // Length of the progress bar
    const filledLength = Math.floor((progress.value / 100) * totalLength);
    const emptyLength = totalLength - filledLength;
    return "=".repeat(filledLength) + "-".repeat(emptyLength); // Dynamic progress bar
});

const promptText = ref(">"); // CLI prompt

// Define the fields with validation rules and custom output messages
const fields = [
    {
        label: "What is your business name?(required)",
        key: "business_name",
        required: true,
        successOutput: (input) => `Your business name is: ${input}`,
        errorOutput: "You must provide a business name.",
    },
    {
        label: "Where is your business located?(required)",
        key: "address",
        required: true,
        successOutput: (input) => `Your business is located at: ${input}`,
        errorOutput: "You must provide your business location.",
    },
    {
        label: "Where is your business or email address?(optional)",
        key: "email_address",
        required: false,
        successOutput: (input) => `Your business/personal email address is: ${input}`,
    },
    {
        label: "What is your business contact number?(optional)",
        key: "contact_number",
        required: false,
        successOutput: (input) => `Your business contact number is: ${input}`,
    },
    {
        label: "What is your business TIN?(required)",
        key: "tin",
        required: true,
        successOutput: (input) => `Your business TIN is: ${input}`,
        errorOutput: "You must provide your business TIN.",
    },
    {
        label: "What is your business permit number?(required)",
        key: "business_permit",
        required: true,
        successOutput: (input) => `Your business permit number is: ${input}`,
        errorOutput: "You must provide your business permit number.",
    },
];

// Final question
const finalQuestion = {
    label: "Would you like to save the information? (yes/no)",
    validate: (input) => ["yes", "no"].includes(input.toLowerCase()),
    successOutput: "Thank you for confirming! Proceeding with submission...",
    errorOutput: "Answering this question is necessary to set up the system accordingly.",
};

const formData = ref({}); // Stores the form data
let currentFieldIndex = 0; // Tracks the current question index
const form = useForm({
    business_name: "",
    address: "",
    email_address: "",
    contact_number: "",
    tin: "",
    business_permit: "",
})
function onInput() {
    // Show 'Press Enter' text if input is not empty
    showPressEnter.value = currentInput.value.trim().length > 0;
}

function handleSubmit() {
    const input = currentInput.value.trim(); // Get user input
    const currentField = fields[currentFieldIndex];
    if (isFinalQuestion.value && progress.value === 0) {
        if (!finalQuestion.validate(input)) {
            outputs.value.push(finalQuestion.errorOutput);
        } else if (input.toLowerCase() === "yes") {
            outputs.value.push(finalQuestion.successOutput);
            submitForm(); // Proceed with form submission
            notSaveYet.value = false
        } else {
            outputs.value.push("You chose not to save the information. Ending process...");
        }
        currentInput.value = ""; // Clear input
        return;
    } else {
        if (currentField.required && !input) {
            outputs.value.push(currentField.errorOutput);
        } else {
            form[currentField.key] = input || null;
            outputs.value.push(currentField.successOutput(input));
            currentFieldIndex++;
            if (currentFieldIndex >= fields.length) {
                isFinalQuestion.value = true;
                // outputs.value.push(finalQuestion.label);
            }
        }
    }
    currentInput.value = ""; // Clear input
    showPressEnter.value = false; // Reset visibility
}

function submitForm() {
    isSubmitting.value = true; // Show progress bar
    progress.value = 0; // Reset progress


    // Submit the form using Inertia
    form.post("/business", {
        onSuccess: (page) => {
            // Access the estimated duration from the response
            const estimatedDuration = page.props.estimatedDuration || 2000; // Default to 2 seconds if not provided
            const intervalTime = 100; // Update every 100ms
            const increment = 100 / (estimatedDuration / intervalTime); // Calculate increment per interval

            // Simulate a progress bar
            const interval = setInterval(() => {
                if (progress.value >= 100) {
                    clearInterval(interval);
                    isSubmitting.value = false; // Hide progress bar
                    outputs.value.push("Form submission complete!");
                    outputs.value = []
                    showCongrats.value = true; // Show congrats message
                } else {
                    progress.value += increment;
                }
            }, intervalTime);


        },
        onError: (errors) => {
            isSubmitting.value = false; // Hide progress bar on error
            outputs.value.push("Form submission failed.");
            console.error(errors);
        },
    });

    console.log(form)
}

function handleFollowUp() {
    const answer = followUpAnswer.value.trim().toLowerCase();
    if (answer === "yes") {
        router.visit("/register"); // Redirect to registration form
    } else if (answer === "no") {
        outputs.value.push("Thank you! Have a great day!");
        followUpAnswer.value = ""; // Clear input
    } else {
        outputs.value.push("Please answer with 'yes' or 'no'.");
    }
}
</script>

<style scoped>
.progress-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    color: #0f0;
}

.progress-bar {
    font-family: monospace;
}

.cli-form-container {
    font-family: monospace;
    background: #000;
    /* color: #0f0; */
    padding: 20px;
    border-radius: 10px;
    width: 600px;
    margin: 0 auto;
}

.cli-output {
    margin-bottom: 10px;
}

.cli-input {
    display: flex;
    align-items: center;
}

.prompt {
    margin-right: 5px;
}

.cli-input-field {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    font-family: monospace;
}

.cli-input-field:active,
.cli-input-field:focus {
    border: none;
    --tw-ring-shadow: none
}

.press-enter-text {
    margin-top: 5px;
    color: #555;
    font-style: italic;
}
</style>
