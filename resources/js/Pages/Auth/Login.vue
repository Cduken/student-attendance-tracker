<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

// Track focused state for floating labels
const emailFocused = ref(false);
const passwordFocused = ref(false);
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <div class="flex items-center justify-center gap-2 animate-bounce">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center">
                        <i class='bx bx-book-alt text-xl text-white'></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">AttendTrack</h1>
                </div>
                <p class="mt-2 text-gray-500">
                    Sign in to continue to your account
                </p>
            </div>

            <div v-if="status" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div class="relative">
                        <div class="relative">
                            <InputLabel
                                for="email"
                                value="Email"
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
                                autofocus
                                autocomplete="username"
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
                                autocomplete="current-password"
                                @focus="passwordFocused = true"
                                @blur="passwordFocused = !!form.password"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <label class="ml-2 text-sm text-gray-600">
                            Remember me
                        </label>
                    </div>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-medium text-blue-600 hover:text-blue-500"
                    >
                        Forgot password?
                    </Link>
                </div>

                <div>
                    <PrimaryButton
                        class="group relative w-full flex justify-center py-2.5 px-4 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bx bx-log-in text-blue-200 group-hover:text-blue-100 transition-colors"></i>
                        </span>
                        Sign in
                    </PrimaryButton>
                </div>
            </form>

            <div class="text-center text-sm text-gray-500">
                <p>
                    Don't have an account?
                    <Link :href="route('register')" class="font-medium text-blue-600 hover:text-blue-500">
                        Register now
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
