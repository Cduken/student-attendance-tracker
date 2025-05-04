<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    stats: {
        type: Array,
        required: true,
    },
});

const features = [
    {
        icon: 'bx-chalkboard',
        title: "Class Management",
        description: "Easily create and organize your classes with student lists and attendance records.",
        gradient: "from-blue-500 to-blue-600",
    },
    {
        icon: 'bx-user-check',
        title: "Attendance Tracking",
        description: "Quickly mark attendance with just a few clicks and view historical records.",
        gradient: "from-purple-500 to-purple-600"
    },
    {
        icon: 'bx-rocket',
        title: "Simple & Intuitive",
        description: "Designed for teachers with a clean, easy-to-use interface that saves time.",
        gradient: "from-orange-500 to-orange-600"
    }
];

const steps = [
    {
        number: "01",
        title: "Create Your Class",
        description: "Set up your class with a name and description. Create multiple classes for different subjects.",
        icon: "bx-book-add"
    },
    {
        number: "02",
        title: "Add Your Students",
        description: "Import your student list or add them manually with contact details.",
        icon: "bx-user-plus"
    },
    {
        number: "03",
        title: "Track Attendance",
        description: "Mark attendance daily with our simple interface and view reports.",
        icon: "bx-check-shield"
    }
];

const advantages = [
    {
        title: "Designed for Educators",
        description: "Built specifically for teachers by former educators who understand your workflow.",
        icon: "bx-group"
    },
    {
        title: "Data Privacy First",
        description: "We never sell your data and use enterprise-grade security to protect student information.",
        icon: "bx-lock-alt"
    },

    {
        title: "Integrations",
        description: "Connect with popular LMS platforms and student information systems.",
        icon: "bx-plug"
    },
];
</script>

<template>

    <Head>
        <title>AttendTrack - Free Student Attendance Tracker for Teachers</title>
        <meta name="description"
            content="AttendTrack: A free, intuitive solution for teachers to track student attendance efficiently." />
        <meta name="keywords" content="attendance tracking, student attendance, teacher tools, classroom management" />
    </Head>

    <div class="min-h-screen bg-gray-50 font-sans">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100 fixed w-full z-50 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                        <ApplicationLogo class="h-8 w-auto" />
                        <span
                            class="ml-2 text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">AttendTrack</span>
                        </Link>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-8">
                        <Link href="/login"
                            class="text-gray-700 hover:text-blue-600 px-4 py-2 text-sm font-medium transition-colors duration-200">
                        Sign In
                        </Link>
                        <Link href="/register"
                            class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-blue-50 to-gray-100 pt-24 pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBmaWxsPSJub25lIiBzdHJva2U9IiMxMTExMTEiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMSI+PHBhdGggZD0iTTAgMEg0MFY0MEgweiIvPjwvc3ZnPg==')]">
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative z-10">
                    <div class="text-center">
                        <h1
                            class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl animate-fade-in">
                            <span class="block">Empowering Teachers with</span>
                            <span
                                class="block bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Free
                                Attendance Tracking</span>
                        </h1>
                        <p class="mt-6 max-w-lg mx-auto text-xl text-gray-600 animate-fade-in-up animation-delay-200">
                            A free, modern, and intuitive tool designed exclusively for teachers to manage attendance
                            effortlessly.
                        </p>
                        <div class="mt-10 flex justify-center gap-4">
                            <Link href="/register"
                                class="px-8 py-4 rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-lg font-medium transition-all duration-200 transform hover:-translate-y-1 animate-fade-in-up animation-delay-400">
                            Get Started Free
                            </Link>
                        </div>
                    </div>
                    <div class="mt-16 max-w-4xl mx-auto transform hover:scale-105 transition-transform duration-300">
                        <div class="relative rounded-xl shadow-2xl overflow-hidden border border-gray-200">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-10"></div>
                            <div class="relative bg-white p-1">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img class="w-full h-auto rounded-lg shadow-sm"
                                        src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&h=900&q=80"
                                        alt="AttendTrack dashboard screenshot" loading="lazy">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Everything Teachers Need
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Streamline attendance tracking with tools designed for educators.
                    </p>
                </div>
                <div class="mt-16">
                    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(feature, index) in features" :key="feature.title" class="pt-8 animate-fade-in-up"
                            :style="{ animationDelay: `${index * 200}ms` }">
                            <div
                                class="flow-root bg-gray-50 rounded-xl px-6 pb-8 h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                <div class="-mt-6">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r text-white mx-auto"
                                        :class="feature.gradient">
                                        <i class="bx text-xl" :class="feature.icon" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="mt-8 text-lg font-medium text-gray-900 text-center">{{ feature.title }}
                                    </h3>
                                    <p class="mt-4 text-base text-gray-500 text-center">
                                        {{ feature.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">How It Works</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Start Tracking in Minutes
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Our simple 3-step process makes attendance effortless.
                    </p>
                </div>
                <div class="mt-16">
                    <div class="grid gap-8 md:grid-cols-3">
                        <div v-for="(step, index) in steps" :key="step.number"
                            class="bg-white p-8 rounded-xl shadow-md border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 animate-fade-in-up"
                            :style="{ animationDelay: `${index * 200}ms` }">
                            <div
                                class="flex items-center justify-center h-16 w-16 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white mx-auto text-2xl font-bold">
                                {{ step.number }}
                            </div>
                            <div class="mt-6 flex justify-center">
                                <div
                                    class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                                    <i class="bx text-xl" :class="step.icon" aria-hidden="true"></i>
                                </div>
                            </div>
                            <h3 class="mt-6 text-lg font-medium text-gray-900 text-center">{{ step.title }}</h3>
                            <p class="mt-4 text-base text-gray-500 text-center">
                                {{ step.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                    <div class="lg:col-span-5">
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Why Teachers Love AttendTrack
                        </h2>
                        <p class="mt-3 text-lg text-gray-500">
                            Built from the ground up to address the unique needs of educators.
                        </p>
                    </div>
                    <div class="mt-10 lg:mt-0 lg:col-span-7">
                        <div class="space-y-10">
                            <div v-for="advantage in advantages" :key="advantage.title" class="group relative">
                                <div
                                    class="absolute -left-4 top-1 h-8 w-8 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-200 transition-colors duration-200">
                                    <i class="bx text-blue-600" :class="advantage.icon" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 pl-12">{{ advantage.title }}</h3>
                                <p class="mt-2 text-base text-gray-500 pl-12">{{ advantage.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="(stat, index) in stats" :key="stat.label" class="text-center animate-fade-in-up"
                        :style="{ animationDelay: `${index * 200}ms` }">
                        <p class="text-4xl font-extrabold text-white sm:text-5xl">{{ stat.value }}</p>
                        <p class="mt-2 text-lg font-medium text-blue-100">{{ stat.label }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-12 sm:p-16">
                        <div class="md:flex md:items-center md:justify-between">
                            <div class="md:w-2/3">
                                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                                    Simplify Attendance Today
                                </h2>
                                <p class="mt-4 max-w-2xl text-lg text-blue-100">
                                    Join thousands of teachers using AttendTrack for free.
                                </p>
                            </div>
                            <div class="mt-8 md:mt-0 md:w-1/3 flex flex-col items-center">
                                <div class="rounded-md shadow">
                                    <Link href="/register"
                                        class="w-full flex items-center justify-center px-6 py-4 border border-transparent text-lg font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                                    Get Started Free
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-3">
                    <div class="col-span-2">
                        <h3 class="text-white text-xl font-bold">AttendTrack</h3>
                        <p class="mt-4 text-gray-400">
                            A free, powerful solution for teachers to track student attendance.
                        </p>
                        <div class="mt-6 flex space-x-6">
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Facebook">
                                <i class="bx bxl-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Twitter">
                                <i class="bx bxl-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Instagram">
                                <i class="bx bxl-instagram text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="LinkedIn">
                                <i class="bx bxl-linkedin text-xl"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Support</h3>
                        <div class="mt-4 space-y-4">
                            <Link href="/help"
                                class="text-gray-400 hover:text-white block transition-colors duration-200">Help Center
                            </Link>
                            <Link href="/contact"
                                class="text-gray-400 hover:text-white block transition-colors duration-200">Contact Us
                            </Link>
                            <Link href="/privacy"
                                class="text-gray-400 hover:text-white block transition-colors duration-200">Privacy
                            Policy</Link>
                        </div>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © 2025 AttendTrack. All rights reserved.
                    </p>
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <Link href="/terms"
                            class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Terms</Link>
                        <Link href="/privacy"
                            class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Privacy</Link>
                        <Link href="/cookies"
                            class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Cookies</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.animation-delay-200 {
    animation-delay: 200ms;
}

.animation-delay-400 {
    animation-delay: 400ms;
}

.animation-delay-600 {
    animation-delay: 600ms;
}

.animation-delay-800 {
    animation-delay: 800ms;
}
</style>
