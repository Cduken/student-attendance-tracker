<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    attendances: {
        type: Object,
        default: () => ({}),
    },
    classes: {
        type: Array,
        default: () => [],
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

// State management
const selectedDate = ref(new Date());
const selectedClass = ref(null);
const currentPage = ref(1);
const recordsPerPage = 10;
const loading = ref(false);

// Safe date formatting with error handling
const formatDate = (date) => {
    if (!date) return 'No date selected';
    try {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-US', options);
    } catch (e) {
        console.error('Date formatting error:', e);
        return 'Invalid date';
    }
};

// Safe ISO date formatting
const formatShortDate = (date) => {
    if (!date) return '';
    try {
        return new Date(date).toISOString().split('T')[0];
    } catch (e) {
        console.error('Date formatting error:', e);
        return '';
    }
};

// Validate date selection
const validateDate = (date) => {
    if (!date) {
        selectedDate.value = new Date();
    }
};

// Group classes with attendance data
const groupedClasses = computed(() => {
    const dateKey = formatShortDate(selectedDate.value);
    const attendanceRecords = props.attendances[dateKey] || [];

    return props.classes.reduce((acc, classItem) => {
        const classRecords = attendanceRecords.filter(record =>
            record?.class?.id === classItem.id
        );

        const presentCount = classRecords.filter(r => r.status === 'present').length;
        const absentCount = classRecords.filter(r => r.status === 'absent').length;
        const lateCount = classRecords.filter(r => r.status === 'late').length;
        const excusedCount = classRecords.filter(r => r.status === 'excused').length;

        acc.push({
            class: classItem,
            records: classRecords,
            student_count: classItem.students_count || 0,
            present_count: presentCount,
            absent_count: absentCount,
            late_count: lateCount,
            excused_count: excusedCount,
            has_attendance: classRecords.length > 0
        });

        return acc;
    }, []);
});

// Pagination calculations
const totalRecords = computed(() => {
    if (!selectedClass.value) return 0;
    const classGroup = groupedClasses.value.find(g => g.class.id === selectedClass.value.id);
    return classGroup?.records.length || 0;
});

const totalPages = computed(() => {
    return Math.ceil(totalRecords.value / recordsPerPage);
});

const paginatedRecords = computed(() => {
    if (!selectedClass.value) return [];
    const classGroup = groupedClasses.value.find(g => g.class.id === selectedClass.value.id);
    if (!classGroup) return [];

    const start = (currentPage.value - 1) * recordsPerPage;
    const end = start + recordsPerPage;
    return classGroup.records.slice(start, end);
});

const showingFrom = computed(() => {
    return totalRecords.value ? (currentPage.value - 1) * recordsPerPage + 1 : 0;
});

const showingTo = computed(() => {
    return Math.min(currentPage.value * recordsPerPage, totalRecords.value);
});

// Navigation methods
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

const selectClass = (classItem) => {
    selectedClass.value = classItem;
    currentPage.value = 1;
};

const resetClassSelection = () => {
    selectedClass.value = null;
    currentPage.value = 1;
};

// Initialize with today's date
onMounted(() => {
    selectedDate.value = new Date();
});
</script>

<template>
    <Head title="Attendance" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">Attendance Management</h2>
        </template>

        <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-75 z-50">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>

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
                                <Datepicker
                                    v-model="selectedDate"
                                    :enable-time="false"
                                    format="yyyy-MM-dd"
                                    class="w-full md:w-64"
                                    teleport
                                    :dark="true"
                                    :max-date="new Date()"
                                    @update:model-value="validateDate"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Classes panel -->
                    <div class="w-full lg:w-1/3">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class='bx bx-group mr-2'></i>
                                    All Classes for {{ formatDate(selectedDate) }}
                                </h3>
                            </div>

                            <div v-if="groupedClasses.length" class="divide-y divide-gray-200">
                                <div
                                    v-for="classGroup in groupedClasses"
                                    :key="classGroup.class.id"
                                    @click="selectClass(classGroup.class)"
                                    class="p-4 hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                                    :class="{
                                        'bg-blue-50': selectedClass?.id === classGroup.class.id,
                                        'border-l-4 border-blue-500': selectedClass?.id === classGroup.class.id
                                    }"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ classGroup.class.name }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ classGroup.student_count }} students</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span v-if="classGroup.has_attendance" class="text-green-600 text-sm font-medium mr-3">
                                                <i class='bx bx-check-circle mr-1'></i>{{ classGroup.present_count }}
                                            </span>
                                            <span v-if="classGroup.has_attendance" class="text-red-600 text-sm font-medium">
                                                <i class='bx bx-x-circle mr-1'></i>{{ classGroup.absent_count }}
                                            </span>
                                            <span v-else class="text-yellow-600 text-sm font-medium">
                                                <i class='bx bx-time text-xs mr-1'></i>No attendance taken
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="classGroup.has_attendance" class="mt-2 flex items-center text-xs text-gray-500">
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
                                <p class="text-gray-500">No classes found</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance details panel -->
                    <div class="w-full lg:w-2/3">
                        <div v-if="selectedClass" class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class='bx bx-list-check mr-2'></i>
                                        Attendance for {{ selectedClass.name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ formatDate(selectedDate) }}</p>
                                </div>
                                <button
                                    @click="resetClassSelection"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <i class='bx bx-arrow-back mr-1'></i> Back to all classes
                                </button>
                            </div>

                            <div v-if="groupedClasses.find(g => g.class.id === selectedClass.id)?.has_attendance">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                                    <i class='bx bx-user mr-1'></i> Student
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                                    <i class='bx bx-check-shield mr-1'></i> Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                                    <i class='bx bx-note mr-1'></i> Notes
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="record in paginatedRecords" :key="record.id" class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap w-2/5">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                            <i class='bx bx-user text-indigo-600'></i>
                                                        </div>
                                                        <div class="ml-4 min-w-0">
                                                            <Link
                                                                :href="route('students.show', record.student.id)"
                                                                class="text-sm font-medium text-gray-900 hover:text-indigo-600 truncate block max-w-[180px]"
                                                            >
                                                                {{ record.student.name }}
                                                            </Link>
                                                            <p class="text-sm text-gray-500 truncate max-w-[180px]">
                                                                {{ record.student.id_number }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap w-1/5">
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
                                                <td class="px-6 py-4 text-sm text-gray-500 w-2/5 max-w-[200px] truncate">
                                                    {{ record.notes || 'No notes' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/5">
                                                    <Link
                                                        :href="route('students.show', record.student.id)"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                                                        title="View student"
                                                    >
                                                        <i class='bx bx-show text-lg'></i>
                                                    </Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        <button
                                            @click="prevPage"
                                            :disabled="currentPage === 1"
                                            :class="{
                                                'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50': currentPage !== 1,
                                                'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed': currentPage === 1
                                            }"
                                        >
                                            Previous
                                        </button>
                                        <button
                                            @click="nextPage"
                                            :disabled="currentPage === totalPages"
                                            :class="{
                                                'ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50': currentPage !== totalPages,
                                                'ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-white cursor-not-allowed': currentPage === totalPages
                                            }"
                                        >
                                            Next
                                        </button>
                                    </div>
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ showingFrom }}</span>
                                                to
                                                <span class="font-medium">{{ showingTo }}</span>
                                                of
                                                <span class="font-medium">{{ totalRecords }}</span>
                                                students
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                <button
                                                    @click="prevPage"
                                                    :disabled="currentPage === 1"
                                                    :class="{
                                                        'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50': currentPage !== 1,
                                                        'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed': currentPage === 1
                                                    }"
                                                >
                                                    <span class="sr-only">Previous</span>
                                                    <i class='bx bx-chevron-left'></i>
                                                </button>

                                                <template v-for="page in totalPages" :key="page">
                                                    <button
                                                        @click="currentPage = page"
                                                        :class="{
                                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium': true,
                                                            'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': currentPage === page,
                                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page
                                                        }"
                                                    >
                                                        {{ page }}
                                                    </button>
                                                </template>

                                                <button
                                                    @click="nextPage"
                                                    :disabled="currentPage === totalPages"
                                                    :class="{
                                                        'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50': currentPage !== totalPages,
                                                        'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-not-allowed': currentPage === totalPages
                                                    }"
                                                >
                                                    <span class="sr-only">Next</span>
                                                    <i class='bx bx-chevron-right'></i>
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-8 text-center">
                                <div class="bg-yellow-50 p-4 rounded-lg inline-block">
                                    <i class='bx bx-time text-4xl text-yellow-500 mb-3'></i>
                                    <h4 class="text-lg font-medium text-yellow-800">No attendance taken yet</h4>
                                    <p class="text-yellow-600 mt-2">No attendance records found for this class on {{ formatDate(selectedDate) }}</p>

                                </div>
                            </div>
                        </div>

                        <div v-else class="bg-white shadow rounded-lg overflow-hidden h-full flex items-center justify-center">
                            <div class="p-8 text-center">
                                <i class='bx bx-group text-4xl text-gray-400 mb-3'></i>
                                <h4 class="text-lg font-medium text-gray-700">Select a class to view attendance</h4>
                                <p class="text-gray-500 mt-2">Click on any class from the list to see detailed attendance records</p>
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

/* Table styling */
table {
    table-layout: fixed;
    width: 100%;
}

/* Column width adjustments */
.w-1\/5 {
    width: 20%;
}

.w-2\/5 {
    width: 40%;
}

/* Hover effects for table rows */
tr {
    transition: background-color 0.15s ease;
}

tr:hover {
    background-color: #f8fafc;
}

/* Status badge styling */
.bg-green-100,
.bg-red-100,
.bg-yellow-100,
.bg-blue-100 {
    transition: all 0.15s ease;
}

/* Action icons */
.bx-show {
    transition: transform 0.15s ease;
}

.bx-show:hover {
    transform: scale(1.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .flex-col {
        flex-direction: column;
    }

    .gap-6 {
        gap: 1.5rem;
    }

    .w-full {
        width: 100%;
    }

    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-4 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .max-w-180px {
        max-width: 180px;
    }
}
</style>
