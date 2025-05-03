<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    classData: Object,
    attendances: Object,
    calendar: Object,
    filters: Object,
});

const selectedDate = ref(props.filters.date || null);
const attendanceDates = Object.keys(props.attendances);

const selectDate = (date) => {
    selectedDate.value = date;
    // Optional: Update URL with date filter
    router.get(route('attendance.class.index', { class: props.classData.id }), {
        date: date,
        month: props.filters.month,
        year: props.filters.year,
    }, {
        preserveState: true,
    });
};

const navigateMonth = (monthYear) => {
    router.get(route('attendance.class.index', { class: props.class.id }), {
        month: monthYear.split('-')[1],
        year: monthYear.split('-')[0],
        date: selectedDate.value,
    }, {
        preserveState: true,
    });
};

const statusClasses = {
    present: 'bg-green-100 text-green-800',
    absent: 'bg-red-100 text-red-800',
    late: 'bg-yellow-100 text-yellow-800',
    excused: 'bg-purple-100 text-purple-800',
    default: 'bg-gray-100 text-gray-800',
};

const getStatusClass = (status) => {
    return statusClasses[status] || statusClasses.default;
};
</script>

<template>
    <Head :title="`Attendance - ${props.classData.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Attendance for {{ props.classData.name }}
                </h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Calendar Section -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ calendar.month_name }}</h3>
                                <div class="flex space-x-2">
                                    <button
                                        @click="navigateMonth(calendar.prev_month)"
                                        class="p-2 rounded-md hover:bg-gray-100"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0-front matter
    01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="navigateMonth(calendar.next_month)"
                                        class="p-2 rounded-md hover:bg-gray-100"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']"
                                     :key="day"
                                     class="text-center text-sm font-medium text-gray-500 py-1">
                                    {{ day }}
                                </div>
                            </div>

                            <div v-for="(week, weekIndex) in calendar.weeks" :key="weekIndex" class="grid grid-cols-7 gap-1">
                                <template v-for="(day, dayIndex) in week" :key="dayIndex">
                                    <div v-if="day === null" class="h-10"></div>
                                    <button
                                        v-else
                                        @click="selectDate(day.date)"
                                        :class="{
                                            'bg-blue-100 text-blue-800 font-semibold': selectedDate === day.date,
                                            'bg-gray-50 hover:bg-gray-100': selectedDate !== day.date,
                                            'ring-2 ring-indigo-500': day.is_today,
                                            'bg-indigo-50': day.has_attendance && selectedDate !== day.date,
                                        }"
                                        class="h-10 rounded-md transition-colors duration-200 flex flex-col items-center justify-center"
                                    >
                                        <span>{{ day.day }}</span>
                                        <span v-if="day.has_attendance" class="w-1 h-1 rounded-full bg-indigo-500 mt-1"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Attendance Records Section -->
                        <div v-if="selectedDate && attendances[selectedDate]?.length" class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Attendance for {{ new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </h3>
                                <div class="flex space-x-2">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ attendances[selectedDate].length }} records
                                    </span>
                                    <Link
                                        :href="route('attendance.create', { class: classData.id, date: selectedDate })"
                                        class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm hover:bg-green-200"
                                    >
                                        Edit
                                    </Link>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-xs">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="attendance in attendances[selectedDate]" :key="attendance.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <Link :href="route('students.show', attendance.student.id)" class="flex items-center text-blue-600 hover:text-blue-900 hover:underline">
                                                    {{ attendance.student.name }}
                                                </Link>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span :class="getStatusClass(attendance.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ attendance.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate">
                                                {{ attendance.notes || '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <Link :href="route('attendances.edit', attendance.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Empty States -->
                        <div v-if="!Object.keys(attendances).length" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records this month</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by taking attendance for today.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('attendance.create', { class: classData.id })"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                                >
                                    Take Attendance
                                </Link>
                            </div>
                        </div>

                        <div v-else-if="!selectedDate" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Select a date</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Choose a date from the calendar above to view attendance records.
                                Dates with attendance are marked with a dot.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
