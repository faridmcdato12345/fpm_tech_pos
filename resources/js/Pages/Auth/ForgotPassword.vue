<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    username: '',
    password: '',
    password_confirmation: '',
    secret_key: ''
});

const password_length = ref(0)
const contains_eight_characters = ref(false)
const contains_number = ref(false)
const contains_uppercase = ref(false)
const contains_lowercase = ref(false)
const contains_special_character = ref(false)
const passStrength = ref(0)

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.secret_key) {
                form.reset('secret_key');
                currentPasswordInput.value.focus();
            }
        },
    });
};

const checkPassword = () => {
  password_length.value = form.password.length
  const format = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/
			
  contains_eight_characters.value = password_length.value > 8
  contains_number.value = /\d/.test(form.password)
  contains_uppercase.value = /[A-Z]/.test(form.password)
  contains_lowercase.value = /[a-z]/.test(form.password)
  contains_special_character.value = format.test(form.password)

  checkPasswordStrength()
}

const checkPasswordStrength = () => {
  passStrength.value = 0
  if(contains_eight_characters.value) passStrength.value += 1
  if(contains_number.value) passStrength.value += 1
  if(contains_uppercase.value) passStrength.value += 1
  if(contains_lowercase.value) passStrength.value += 1
  if(contains_special_character.value) passStrength.value += 1

  console.log('Pass Strength : ', passStrength.value)
}
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="username" value="Username" />

                <TextInput
                    id="username"
                    ref="usernameInput"
                    v-model="form.username"
                    type="text"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.username" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    @input="checkPassword"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div>
                <InputLabel for="secret_key" value="Secret Key" />

                <TextInput
                    id="secret_key"
                    ref="secretKeyInput"
                    v-model="form.secret_key"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.secret_key" class="mt-2" />
            </div>

            <div class="flex mt-2 gap-x-2 px-1">
                <div class="rounded-lg flex-auto relative h-1.5 bg-gray-300">
                <div 
                    class="absolute left-0 bg-teal-400 top-0 transition-all ease-in-out duration-300 w-0 z-10 h-1.5 rounded-lg" 
                    :style="{ width: passStrength >= 1 ? '100%' : '0%'}"></div>
                </div>
                <div class="rounded-lg flex-auto relative h-1.5 bg-gray-300">
                <div 
                    class="absolute left-0 bg-teal-400 top-0 transition-all ease-in-out duration-300 z-10 h-1.5 rounded-lg" 
                    :style="{ width: passStrength >= 2 ? '100%' : '0%'}"></div>
                </div>
                <div class="rounded-lg flex-auto relative h-1.5 bg-gray-300">
                <div 
                    class="absolute left-0 bg-teal-400 top-0 transition-all ease-in-out duration-300 z-10 h-1.5 rounded-lg" 
                    :style="{ width: passStrength >= 3 ? '100%' : '0%'}"></div>
                </div>
                <div class="rounded-lg flex-auto relative h-1.5 bg-gray-300">
                <div 
                    class="absolute left-0 bg-teal-400 top-0 transition-all ease-in-out duration-300 z-10 h-1.5 rounded-lg" 
                    :style="{ width: passStrength >= 4 ? '100%' : '0%'}"></div>
                </div>
                <div class="rounded-lg flex-auto relative h-1.5 bg-gray-300">
                <div 
                    class="absolute left-0 bg-teal-400 top-0 transition-all ease-in-out duration-300 z-10 h-1.5 rounded-lg" 
                    :style="{ width: passStrength >= 5 ? '100%' : '0%'}"></div>
                </div>
            </div>
            <div class="mt-2 ml-1">
                <p class="leading-4 text-white text-sm">
                Password must be at least
                <span :class="contains_eight_characters ? 'text-teal-400' : 'text-red-600'">8 characters</span>, 
                <span :class="contains_number ? 'text-teal-400' : 'text-red-600'">contains a number</span>, 
                <span :class="contains_special_character ? 'text-teal-400' : 'text-red-600'">special character</span>, 
                <span :class="contains_uppercase ? 'text-teal-400' : 'text-red-600'">uppercase</span>, 
                <span :class="contains_lowercase ? 'text-teal-400' : 'text-red-600'">lowercase</span>, 
                </p>
            </div>
            <div class="flex items-end justify-end gap-4">
                
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
                <Link :href="route('login')" class="inline-flex items-center px-4 py-2 border border-red-400 border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-400 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Back</Link>
            </div>
        </form>
    </GuestLayout>
</template>
