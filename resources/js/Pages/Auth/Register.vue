<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Track focused state for floating labels
const nameFocused = ref(false);
const emailFocused = ref(false);
const passwordFocused = ref(false);
const passwordConfirmationFocused = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <div class="flex items-center justify-center gap-2 mb-4 animate-bounce">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center">
                        <i class='bx bx-book-alt text-xl  text-white'></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">AttendTrack</h1>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Create account
                </h2>
                <p class="mt-2 text-gray-500">
                    Or <Link :href="route('login')" class="font-medium text-blue-600 hover:text-blue-500">sign in to your existing account</Link>
                </p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div class="relative">
                        <div class="relative">
                            <InputLabel
                                for="name"
                                value="Full Name"
                                class="absolute left-10 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-all duration-200 origin-left"
                                :class="{
                                    'transform -translate-y-7 scale-75 text-blue-600': nameFocused || form.name,
                                    'text-gray-400': !nameFocused && !form.name
                                }"
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="bx bx-user" :class="{
                                    'text-blue-500': nameFocused,
                                    'text-gray-400': !nameFocused
                                }"></i>
                            </div>
                            <TextInput
                                id="name"
                                type="text"
                                class="block w-full pl-10 pt-4"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                @focus="nameFocused = true"
                                @blur="nameFocused = !!form.name"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div class="relative">
                        <div class="relative">
                            <InputLabel
                                for="email"
                                value="Email Address"
                                class="absolute left-10 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-all duration-200 origin-left"
                                :class="{
                                    'transform -translate-y-7 scale-75 text-blue-600': emailFocused || form.email,
                                    'text-gray-400': !emailFocused && !form.email
                                }"
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="bx bx-envelope" :class="{
                                    'text-blue-500': emailFocused,
                                    'text-gray-400': !emailFocused
                                }"></i>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full pl-10 pt-4"
                                v-model="form.email"
                                required
                                autocomplete="email"
                                @focus="emailFocused = true"
                                @blur="emailFocused = !!form.email"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div class="relative">
                        <div class="relative">
                            <InputLabel
                                for="password"
                                value="Password"
                                class="absolute left-10 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-all duration-200 origin-left"
                                :class="{
                                    'transform -translate-y-7 scale-75 text-blue-600': passwordFocused || form.password,
                                    'text-gray-400': !passwordFocused && !form.password
                                }"
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="bx bx-lock-alt" :class="{
                                    'text-blue-500': passwordFocused,
                                    'text-gray-400': !passwordFocused
                                }"></i>
                            </div>
                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full pl-10 pt-4"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                @focus="passwordFocused = true"
                                @blur="passwordFocused = !!form.password"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <div class="relative">
                        <div class="relative">
                            <InputLabel
                                for="password_confirmation"
                                value="Confirm Password"
                                class="absolute left-10 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-all duration-200 origin-left"
                                :class="{
                                    'transform -translate-y-7 scale-75 text-blue-600': passwordConfirmationFocused || form.password_confirmation,
                                    'text-gray-400': !passwordConfirmationFocused && !form.password_confirmation
                                }"
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="bx bx-check-shield" :class="{
                                    'text-blue-500': passwordConfirmationFocused,
                                    'text-gray-400': !passwordConfirmationFocused
                                }"></i>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="block w-full pl-10 pt-4"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                @focus="passwordConfirmationFocused = true"
                                @blur="passwordConfirmationFocused = !!form.password_confirmation"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div>
                    <PrimaryButton
                        class="group relative w-full flex justify-center py-2.5 px-4 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bx bx-user-plus text-blue-200 group-hover:text-blue-100 transition-colors"></i>
                        </span>
                        Create Account
                    </PrimaryButton>
                </div>
            </form>

            <div class="text-center text-sm text-gray-500">
                <p>
                    Already have an account?
                    <Link :href="route('login')" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign in here
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
