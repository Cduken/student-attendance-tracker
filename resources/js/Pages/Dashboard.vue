<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium">Welcome back!</h3>
                    <div class="flex space-x-2">
                        <i class="bx bx-bell text-xl text-gray-500"></i>
                        <i class="bx bx-cog text-xl text-gray-500"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="bx bx-group text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Students</p>
                                <p class="text-2xl font-semibold">124</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="bx bx-calendar-check text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Today's Attendance</p>
                                <p class="text-2xl font-semibold">98%</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="bx bx-bar-chart-alt-2 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Monthly Average</p>
                                <p class="text-2xl font-semibold">95.4%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-medium mb-4">Recent Activity</h4>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="bx bx-check text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Marked attendance for Class 7A</p>
                                <p class="text-sm text-gray-500">10 minutes ago</p>
                            </div>
                        </div>
                        <!-- Add more activity items as needed -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
