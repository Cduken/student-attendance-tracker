<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    students: Object,
    filters: Object,
    classes: Array,
});

const searchQuery = ref(props.filters.search ?? '');
const selectedClass = ref(props.filters.class ?? null);

const showFilters = ref(false);
const processing = ref(false);

const applyFilters = debounce(() => {
    const trimmedSearch = searchQuery.value.trim();
    console.log('Sending search:', trimmedSearch); // Add this line

    router.get(route('students.index'), {
        search: trimmedSearch,
        class: selectedClass.value,
    }, {
        preserveState: true,
        replace: true,
        onStart: () => processing.value = true,
        onFinish: () => {
            processing.value = false;
            console.log('Search completed'); // Add this line
        },
    });
}, 300);

watch([searchQuery, selectedClass], () => {
    if (searchQuery.value === '' && !selectedClass.value) {
        router.get(route('students.index'), {}, {
            preserveState: true,
            replace: true,
        });
    } else {
        applyFilters();
    }
});

const resetFilters = () => {
    searchQuery.value = '';
    selectedClass.value = null;
    router.get(route('students.index'), {}, {
        preserveState: false,
        replace: true,
    });
};
</script>

<template>

    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 flex items-center">

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
                                <div v-if="processing" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="showFilters = !showFilters"
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                                    <i class='bx bx-filter-alt mr-1'></i>
                                    Filters
                                </button>
                            </div>
                        </div>

                        <!-- Filters Panel -->
                        <div v-if="showFilters" class="bg-gray-50 p-4 rounded-md mb-6 transition-all duration-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                                    <select v-model="selectedClass"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option :value="null">All Classes</option>
                                        <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">
                                            {{ classItem.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <button @click="resetFilters"
                                        class="px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Reset Filters
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="overflow-x-auto">
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
                                    <tr v-for="student in students.data" :key="student.id"
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
                                    <tr v-if="students.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No students found
                                            <span v-if="searchQuery">matching "{{ searchQuery }}"</span>
                                            <span v-if="selectedClass">in selected class</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="students.data.length"
                            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link v-if="students.prev_page_url" :href="students.prev_page_url"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                                </Link>
                                <span v-else
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    Previous
                                </span>

                                <Link v-if="students.next_page_url" :href="students.next_page_url"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                                </Link>
                                <span v-else
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed">
                                    Next
                                </span>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ students.from }}</span>
                                        to
                                        <span class="font-medium">{{ students.to }}</span>
                                        of
                                        <span class="font-medium">{{ students.total }}</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                        aria-label="Pagination">
                                        <Link v-if="students.prev_page_url" :href="students.prev_page_url"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class='bx bx-chevron-left'></i>
                                        </Link>
                                        <span v-else
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Previous</span>
                                            <i class='bx bx-chevron-left'></i>
                                        </span>

                                        <template v-for="(link, index) in students.links" :key="index">
                                            <Link v-if="link.url && !link.active && link.label !== '...'"
                                                :href="link.url" v-html="link.label"
                                                class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" />
                                            <span v-else-if="link.active" v-html="link.label"
                                                class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium" />
                                            <span v-else v-html="link.label"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400" />
                                        </template>

                                        <Link v-if="students.next_page_url" :href="students.next_page_url"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class='bx bx-chevron-right'></i>
                                        </Link>
                                        <span v-else
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Next</span>
                                            <i class='bx bx-chevron-right'></i>
                                        </span>
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
tr {
    transition: background-color 0.15s ease;
}

tr:hover {
    background-color: #f8fafc;
}

.text-indigo-600:hover,
.text-blue-600:hover,
.text-red-600:hover {
    transform: scale(1.1);
    transition: transform 0.15s ease;
}

input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

@media (max-width: 640px) {
    .flex-col {
        flex-direction: column;
    }

    .gap-4 {
        gap: 1rem;
    }
}
</style>
