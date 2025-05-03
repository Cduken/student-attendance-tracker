<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

defineProps({
    students: Array,
});

const searchQuery = ref('');
</script>

<template>

    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class='bx bx-group text-3xl mr-2 text-indigo-600'></i>
                    Student Management
                </h2>

            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="p-6">
                        <!-- Search and Filter Bar -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-search text-gray-400'></i>
                                </div>
                                <input v-model="searchQuery" type="text"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Search students..." />
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                                    <i class='bx bx-filter-alt mr-1'></i>
                                    Filters
                                </button>

                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="overflow-x-auto">x
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class='bx bx-user mr-2'></i>
                                                Name
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class='bx bx-id-card mr-2'></i>
                                                Student ID
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class='bx bx-envelope mr-2'></i>
                                                Email
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class='bx bx-book-alt mr-2'></i>
                                                Class
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in students" :key="student.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class='bx bx-user text-indigo-600'></i>
                                                </div>
                                                <div class="ml-4">
                                                    <Link :href="route('students.show', student.id)"
                                                        class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                                                    {{ student.name }}
                                                    </Link>
                                                    <div class="text-sm text-gray-500">{{ student.gender || 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ student.student_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <i class='bx bx-envelope mr-1 text-gray-400'></i>
                                                {{ student.email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link v-if="student.class" :href="route('classes.show', student.class.id)"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                            <i class='bx bx-book-alt mr-1'></i>
                                            {{ student.class.name }}
                                            </Link>
                                            <span v-else
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class='bx bx-x-circle mr-1'></i>
                                                Not assigned
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">

                                                <Link :href="route('students.show', student.id)"
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50">
                                                <i class='bx bx-show'></i>
                                                </Link>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="#"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a href="#"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">1</span>
                                        to
                                        <span class="font-medium">10</span>
                                        of
                                        <span class="font-medium">20</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                        aria-label="Pagination">
                                        <a href="#"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class='bx bx-chevron-left'></i>
                                        </a>
                                        <a href="#" aria-current="page"
                                            class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            1
                                        </a>
                                        <a href="#"
                                            class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            2
                                        </a>
                                        <a href="#"
                                            class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            3
                                        </a>
                                        <a href="#"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class='bx bx-chevron-right'></i>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles */
tr {
    transition: background-color 0.15s ease;
}

/* Hover effect for table rows */
tr:hover {
    background-color: #f8fafc;
}

/* Action buttons hover effect */
.text-indigo-600:hover,
.text-blue-600:hover,
.text-red-600:hover {
    transform: scale(1.1);
    transition: transform 0.15s ease;
}

/* Search input focus effect */
input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .flex-col {
        flex-direction: column;
    }

    .gap-4 {
        gap: 1rem;
    }
}
</style>
