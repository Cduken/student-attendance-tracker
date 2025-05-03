<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    student: Object,
});
</script>

<template>
    <Head :title="`Student: ${student.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ student.name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">Student ID: {{ student.student_id }}</p>
                            <p class="mt-1 text-sm text-gray-600">Email: {{ student.email || 'N/A' }}</p>
                            <p class="mt-1 text-sm text-gray-600">
                                Class:
                                <Link v-if="student.class" :href="route('classes.show', student.class.id)" class="text-indigo-600 hover:text-indigo-900">
                                    {{ student.class.name }}
                                </Link>
                                <span v-else>N/A</span>
                            </p>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900">Attendance Records</h4>
                            <div v-if="student.attendances && student.attendances.length" class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="attendance in student.attendances" :key="attendance.id">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ attendance.date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ attendance.status }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p v-else class="mt-2 text-sm text-gray-600">No attendance records found.</p>
                        </div>

                        <div class="mt-6 flex space-x-4">
                            <Link :href="route('students.edit', student.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Edit
                            </Link>
                            <Link :href="route('classes.show', student.class.id)" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Back to Class
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
