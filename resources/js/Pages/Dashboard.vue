<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    dashboardData: {
        type: Object,
        required: true,
    },
});
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex  flex-col md:flex-row md:items-center md:justify-between">
                <h2 class="font-semibold leading-tight text-gray-900 text-xl">
                    Teacher Dashboard
                </h2>
                <div class="mt-2 md:mt-0 text-sm text-blue-600 flex items-center ml-5">
                    <i class='bx bx-time-five mr-2 text-lg'></i>
                    {{ new Date().toLocaleDateString('en-US', {
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                    }) }}
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!---- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Students Card -->
                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg overflow-hidden border border-blue-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100/80 text-blue-600 shadow-inner">
                                    <i class='bx bx-group text-2xl'></i>-
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-800/80">Total Students</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ dashboardData.totalStudents }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Attendance -->
                    <div
                        class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-lg overflow-hidden border border-green-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100/80 text-green-600 shadow-inner">
                                    <i class='bx bx-calendar-check text-2xl'></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-800/80">Today's Attendance</p>
                                    <p class="text-3xl font-bold text-gray-900">
                                        {{ dashboardData.todaysAttendance }}%
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2.5 rounded-full shadow-inner"
                                            :style="`width: ${dashboardData.todaysAttendance}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Average -->
                    <div
                        class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden border border-purple-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100/80 text-purple-600 shadow-inner">
                                    <i class='bx bx-trending-up text-2xl'></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-purple-800/80">Monthly Average</p>
                                    <p class="text-3xl font-bold text-gray-900">
                                        {{ dashboardData.monthlyAverage }}%
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                                        <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2.5 rounded-full shadow-inner"
                                            :style="`width: ${dashboardData.monthlyAverage}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200/80 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-history mr-2 text-blue-600'></i>
                                Recent Attendance Updates
                            </h3>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                Last 5 records
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div v-for="(activity, index) in dashboardData.recentActivities" :key="index"
                                class="flex items-start p-4 hover:bg-blue-50/50 rounded-lg transition-all duration-200 group border border-transparent hover:border-blue-200/50">
                                <div class="flex-shrink-0 mt-1">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner group-hover:bg-blue-200 transition-colors">
                                        <i class='bx bx-check-circle text-xl'></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ activity.description }}</p>
                                    <p class="text-xs text-gray-500 flex items-center mt-1">
                                        <i class='bx bx-time mr-1'></i>
                                        {{ activity.time_ago }}
                                    </p>
                                </div>
                                <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class='bx bx-chevron-right text-gray-400'></i>
                                </div>
                            </div>

                            <div v-if="dashboardData.recentActivities.length === 0"
                                class="text-center py-8 bg-gray-50/50 rounded-lg">
                                <i class='bx bx-calendar-exclamation text-4xl text-gray-300 mb-2'></i>
                                <p class="text-gray-500">No recent attendance records found</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
