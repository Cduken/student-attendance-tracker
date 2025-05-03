<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    attendances: {
        type: Object,
        default: () => ({}),
    },
});

const selectedDate = ref(new Date());
const selectedClass = ref(null);
const currentPage = ref(1); // Current page for pagination
const recordsPerPage = 10; // Number of records per page

// Format date for display and comparison
const formatDate = (date) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
};

const formatShortDate = (date) => {
    return date.toISOString().split('T')[0];
};

// Filter and group attendances by class for the selected date
const groupedClasses = computed(() => {
    const dateKey = formatShortDate(selectedDate.value);
    const records = props.attendances[dateKey] || [];

    // Group by class ID to avoid duplicates
    const classMap = records.reduce((acc, record) => {
        const classId = record.class.id;
        if (!acc[classId]) {
            acc[classId] = {
                class: record.class,
                records: [],
                student_count: 0,
                present_count: 0,
                absent_count: 0,
                late_count: 0,
                excused_count: 0,
            };
        }
        acc[classId].records.push(record);
        acc[classId].student_count += 1;

        // Count statuses
        if (record.status === 'present') acc[classId].present_count++;
        if (record.status === 'absent') acc[classId].absent_count++;
        if (record.status === 'late') acc[classId].late_count++;
        if (record.status === 'excused') acc[classId].excused_count++;

        return acc;
    }, {});

    return Object.values(classMap);
});

// Pagination logic
const totalRecords = computed(() => {
    return selectedClass.value ? groupedClasses.value.find(group => group.class.id === selectedClass.value.id)?.records.length || 0 : 0;
});

const totalPages = computed(() => {
    return Math.ceil(totalRecords.value / recordsPerPage);
});

const paginatedRecords = computed(() => {
    if (!selectedClass.value) return [];
    const records = groupedClasses.value.find(group => group.class.id === selectedClass.value.id)?.records || [];
    const start = (currentPage.value - 1) * recordsPerPage;
    const end = start + recordsPerPage;
    return records.slice(start, end);
});

const showingFrom = computed(() => {
    return totalRecords.value ? (currentPage.value - 1) * recordsPerPage + 1 : 0;
});

const showingTo = computed(() => {
    return Math.min(currentPage.value * recordsPerPage, totalRecords.value);
});

// Pagination navigation
const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

// Handle class selection
const selectClass = (classItem) => {
    selectedClass.value = classItem;
    currentPage.value = 1; // Reset to first page when selecting a new class
};

// Reset class selection
const resetClassSelection = () => {
    selectedClass.value = null;
    currentPage.value = 1; // Reset to first page
};
</script>
<template>

    <Head title="Attendance" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Attendance Management</h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header with date selection -->
                <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-lg font-medium text-gray-900">Attendance Records</h3>
                                <p class="text-sm text-gray-500 mt-1">View and manage student attendance</p>
                            </div>
                            <div class="w-full md:w-auto">
                                <Datepicker v-model="selectedDate" :enable-time="false" format="yyyy-MM-dd"
                                    class="w-full md:w-64" teleport :dark="true">
                                </Datepicker>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Classes panel -->
                    <div class="w-full lg:w-1/3" v-if="!selectedClass">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class='bx bx-group mr-2'></i>
                                    Classes for {{ formatDate(selectedDate) }}
                                </h3>
                            </div>

                            <div v-if="groupedClasses.length" class="divide-y divide-gray-200">
                                <div v-for="classGroup in groupedClasses" :key="classGroup.class.id"
                                    @click="selectClass(classGroup.class)"
                                    class="p-4 hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ classGroup.class.name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ classGroup.student_count }}
                                                students</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-green-600 text-sm font-medium mr-3">
                                                <i class='bx bx-check-circle mr-1'></i>{{ classGroup.present_count }}
                                            </span>
                                            <span class="text-red-600 text-sm font-medium">
                                                <i class='bx bx-x-circle mr-1'></i>{{ classGroup.absent_count }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center text-xs text-gray-500">
                                        <span class="mr-3">
                                            <i class='bx bx-time-five mr-1'></i>{{ classGroup.late_count }} late
                                        </span>
                                        <span>
                                            <i class='bx bx-notepad mr-1'></i>{{ classGroup.excused_count }} excused
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-6 text-center">
                                <i class='bx bx-calendar-x text-4xl text-gray-400 mb-3'></i>
                                <p class="text-gray-500">No classes found for this date</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance details panel -->
                    <div class="w-full lg:w-2/3" v-if="selectedClass">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class='bx bx-list-check mr-2'></i>
                                        Attendance for {{ selectedClass.name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ formatDate(selectedDate) }}</p>
                                </div>
                                <button @click="resetClassSelection"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class='bx bx-arrow-back mr-1'></i> Back to classes
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                                <i class='bx bx-user mr-1'></i> Student
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                                <i class='bx bx-check-shield mr-1'></i> Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                                <i class='bx bx-note mr-1'></i> Notes
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="record in paginatedRecords" :key="record.id">
                                            <td class="px-6 py-4 whitespace-nowrap w-1/3">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <i class='bx bx-user text-indigo-600'></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <Link :href="route('students.show', record.student.id)"
                                                            class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                        {{ record.student.name }}
                                                        </Link>
                                                        <p class="text-sm text-gray-500">{{ record.student.id_number }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/6">
                                                <span :class="{
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium': true,
                                                    'bg-green-100 text-green-800': record.status === 'present',
                                                    'bg-red-100 text-red-800': record.status === 'absent',
                                                    'bg-yellow-100 text-yellow-800': record.status === 'late',
                                                    'bg-blue-100 text-blue-800': record.status === 'excused',
                                                }">
                                                    <i :class="{
                                                        'bx bx-check-circle mr-1': record.status === 'present',
                                                        'bx bx-x-circle mr-1': record.status === 'absent',
                                                        'bx bx-time-five mr-1': record.status === 'late',
                                                        'bx bx-notepad mr-1': record.status === 'excused',
                                                    }"></i>
                                                    {{ record.status.charAt(0).toUpperCase() + record.status.slice(1) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate w-1/3">
                                                {{ record.notes || 'No notes' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/6">
                                                <Link :href="route('students.show', record.student.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-4"
                                                    title="View student">
                                                <i class='bx bx-show'></i>
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                                <div class="flex-1 flex justify-between items-center">
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ showingFrom }}</span> to <span
                                            class="font-medium">{{ showingTo }}</span> of <span class="font-medium">{{
                                            totalRecords }}</span> students
                                    </p>
                                    <div class="flex space-x-2">
                                        <button @click="prevPage" :disabled="currentPage === 1" :class="{
                                            'px-3 py-1 border border-gray-300 rounded-md text-sm font-medium': true,
                                            'bg-white text-gray-700 hover:bg-gray-50': currentPage !== 1,
                                            'bg-gray-100 text-gray-400 cursor-not-allowed': currentPage === 1
                                        }">
                                            Previous
                                        </button>
                                        <button @click="nextPage" :disabled="currentPage === totalPages" :class="{
                                            'px-3 py-1 border border-gray-300 rounded-md text-sm font-medium': true,
                                            'bg-white text-gray-700 hover:bg-gray-50': currentPage !== totalPages,
                                            'bg-gray-100 text-gray-400 cursor-not-allowed': currentPage === totalPages
                                        }">
                                            Next
                                        </button>
                                    </div>
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
/* Custom styles for datepicker */
:deep(.dp__input) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    width: 100%;
    background-color: #fff;
    color: #111827;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

:deep(.dp__input:focus) {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

:deep(.dp__input_icon) {
    left: auto;
    right: 1rem;
}

:deep(.dp__menu) {
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.dp__calendar_header_item),
:deep(.dp__calendar_item) {
    color: #111827;
}

:deep(.dp__today) {
    border-color: #6366f1;
}

:deep(.dp__active_date) {
    background-color: #6366f1;
    color: #ffffff;
}

:deep(.dp__action_buttons) {
    border-top: 1px solid #e5e7eb;
}

/* Table styling fixes */
table {
    table-layout: fixed; /* Ensures consistent column widths */
    width: 100%; /* Full width */
}

/* Column width adjustments */
.w-1\/6 {
    width: 16.666667%;
}
.w-1\/3 {
    width: 33.333333%;
}

/* Hover effects for table rows */
tr:hover td {
    background-color: #f9fafb;
}
</style>
