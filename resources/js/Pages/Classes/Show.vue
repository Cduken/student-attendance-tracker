<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    classData: {
        type: Object,
        default: () => ({}),
    },
    students: {
        type: Array,
        default: () => [],
    },
});

// Add Student Modal
const showStudentModal = ref(false);
const studentForm = ref({
    name: '',
    email: '',
    student_id: '',
    errors: {},
});

const addStudent = () => {
    if (!props.classData?.id) {
        console.error('Class ID is missing');
        return;
    }

    router.post(route('classes.students.store', props.classData.id), {
        name: studentForm.value.name,
        email: studentForm.value.email,
        student_id: studentForm.value.student_id,
    }, {
        onSuccess: () => {
            showStudentModal.value = false;
            studentForm.value = {
                name: '',
                email: '',
                student_id: '',
                errors: {},
            };
        },
        onError: (errors) => {
            console.error('Form errors:', errors);
            studentForm.value.errors = errors;
        },
    });
};

// Edit Student Modal
const showEditStudentModal = ref(false);
const editStudentForm = ref({
    id: null,
    name: '',
    email: '',
    student_id: '',
    errors: {},
});

const openEditStudentModal = (student) => {
    editStudentForm.value = {
        id: student.id,
        name: student.name || '',
        email: student.email || '',
        student_id: student.student_id || '',
        errors: {},
    };
    showEditStudentModal.value = true;
};

const updateStudent = () => {
    router.put(route('students.update', editStudentForm.value.id), {
        name: editStudentForm.value.name,
        email: editStudentForm.value.email,
        student_id: editStudentForm.value.student_id,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showEditStudentModal.value = false;
        },
        onError: (errors) => {
            editStudentForm.value.errors = errors;
        },
    });
};

// Attendance Form
const showAttendanceForm = ref(false);
const attendanceForm = ref({
    date: new Date().toISOString().split('T')[0],
    attendances: props.students.map(student => ({
        student_id: student.id,
        status: 'present',
        notes: '',
    })),
    errors: {},
});

const toggleAttendanceForm = () => {
    showAttendanceForm.value = !showAttendanceForm.value;
    if (showAttendanceForm.value) {
        attendanceForm.value.attendances = props.students.map(student => ({
            student_id: student.id,
            status: 'present',
            notes: '',
        }));
    }
};

const updateAttendance = (studentId, field, value) => {
    const index = attendanceForm.value.attendances.findIndex(a => a.student_id === studentId);
    if (index !== -1) {
        attendanceForm.value.attendances[index][field] = value;
        if (field === 'status' && value !== 'excused') {
            attendanceForm.value.attendances[index].notes = '';
        }
    }
};

const submitAttendance = () => {
    if (!props.classData?.id) {
        console.error('Class ID is missing');
        return;
    }

    router.post(route('attendance.store', props.classData.id), {
        date: attendanceForm.value.date,
        attendances: attendanceForm.value.attendances,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            attendanceForm.value.errors = {};
            showAttendanceForm.value = false;
        },
        onError: (errors) => {
            console.error('Attendance errors:', errors);
            attendanceForm.value.errors = errors;
        },
    });
};
</script>

<template>

    <Head :title="classData?.name || 'Class Details'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <h2 class="font-bold text-2xl text-gray-900">{{ classData?.name || 'Loading...' }}</h2>
                <Link :href="route('classes.index')"
                    class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 transition">
                <i class="bx bx-arrow-back mr-1"></i> Back to Classes
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen w-full">
            <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Class Information -->
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <i class="bx bx-book-alt text-3xl text-indigo-600"></i>
                            <h3 class="text-xl font-semibold text-gray-900">Class Information</h3>
                        </div>
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Subject</p>
                                <p class="mt-1 text-sm text-gray-900">{{ classData?.subject || 'No subject' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Description</p>
                                <p class="mt-1 text-sm text-gray-900">{{ classData?.description || 'No description' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students and Attendance -->
                <div class="bg-white shadow-lg rounded-xl overflow-hidden w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="bx bx-group mr-2 text-indigo-600"></i> Students
                            </h3>
                            <div class="flex space-x-3">
                                <PrimaryButton @click="toggleAttendanceForm"
                                    :disabled="!students.length || !classData?.id"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                                    <i class="bx bx-calendar-check mr-2"></i>
                                    {{ showAttendanceForm ? 'Cancel Attendance' : 'Take Attendance' }}
                                </PrimaryButton>
                                <PrimaryButton @click="showStudentModal = true" :disabled="!classData?.id"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                                    <i class="bx bx-user-plus mr-2"></i> Add Student
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Attendance Date Picker -->
                        <form @submit.prevent="submitAttendance" v-if="showAttendanceForm"
                            class="mb-8 bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-end space-x-4">
                                <div>
                                    <InputLabel for="attendance-date" value="Attendance Date"
                                        class="text-sm font-medium text-gray-700" />
                                    <TextInput id="attendance-date" type="date"
                                        class="mt-1 block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        v-model="attendanceForm.date" required />
                                    <InputError class="mt-2" :message="attendanceForm.errors.date" />
                                </div>
                                <PrimaryButton type="submit" :disabled="!students.length || !classData?.id"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition shadow-sm">
                                    <i class="bx bx-save mr-2"></i> Save Attendance
                                </PrimaryButton>
                            </div>
                        </form>

                        <!-- Students and Attendance Table -->
                        <div class="w-full">
                            <table class="w-full divide-y divide-gray-200 table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider flex-1">
                                            Name</th>
                                        <th
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                            Student ID</th>
                                        <th
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider flex-1">
                                            Email</th>
                                        <th v-if="showAttendanceForm"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">
                                            Status</th>
                                        <th v-if="showAttendanceForm"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Notes</th>
                                        <th
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in students" :key="student.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-2 py-4 text-sm text-gray-900 truncate flex-1">{{ student.name ||
                                            'N/A' }}
                                        </td>
                                        <td class="px-2 py-4 text-sm text-gray-900 w-24">{{ student.student_id || 'N/A'
                                            }}</td>
                                        <td class="px-2 py-4 text-sm text-gray-900 truncate flex-1">{{ student.email ||
                                            '-' }}
                                        </td>
                                        <td v-if="showAttendanceForm" class="px-2 py-4 w-64">
                                            <div class="flex space-x-2">
                                                <label v-for="status in ['present', 'absent', 'late', 'excused']"
                                                    :key="status" class="flex items-center">
                                                    <input type="radio" :name="`status-${student.id}`" :value="status"
                                                        v-model="attendanceForm.attendances.find(a => a.student_id === student.id).status"
                                                        @change="updateAttendance(student.id, 'status', status)"
                                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                                                    <span class="ml-1 text-sm capitalize text-gray-700">{{ status
                                                        }}</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td v-if="showAttendanceForm" class="px-2 py-4 w-32">
                                            <TextInput type="text"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                v-model="attendanceForm.attendances.find(a => a.student_id === student.id).notes"
                                                placeholder="Notes (excused)"
                                                :disabled="attendanceForm.attendances.find(a => a.student_id === student.id).status !== 'excused'"
                                                :class="{ 'bg-gray-100 cursor-not-allowed': attendanceForm.attendances.find(a => a.student_id === student.id).status !== 'excused' }" />
                                        </td>
                                        <td class="px-2 py-4 text-sm font-medium w-32">
                                            <button v-if="student.class" @click="openEditStudentModal(student)"
                                                class="text-indigo-600 hover:text-indigo-800 mr-2 transition-colors">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </button>
                                            <Link v-if="student.class" :href="route('students.destroy', student.id)"
                                                method="delete" as="button"
                                                class="text-red-600 hover:text-red-800 transition-colors">
                                            <i class="bx bx-trash"></i> Delete
                                            </Link>
                                            <span v-else class="text-gray-500">Invalid class</span>
                                        </td>
                                    </tr>
                                    <tr v-if="!students || students.length === 0">
                                        <td :colspan="showAttendanceForm ? 6 : 4"
                                            class="px-2 py-4 text-center text-sm text-gray-500">
                                            <i class="bx bx-info-circle mr-2"></i> No students in this class yet
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Student Modal -->
        <Modal :show="showStudentModal" @close="showStudentModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-user-plus mr-2 text-indigo-600"></i> Add New Student
                </h2>
                <form @submit.prevent="addStudent" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="name" value="Student Name" class="text-sm font-medium text-gray-700" />
                        <TextInput id="name" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="studentForm.name" required autofocus />
                        <InputError class="mt-2" :message="studentForm.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="student_id" value="Student ID" class="text-sm font-medium text-gray-700" />
                        <TextInput id="student_id" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="studentForm.student_id" required />
                        <InputError class="mt-2" :message="studentForm.errors.student_id" />
                    </div>
                    <div>
                        <InputLabel for="email" value="Email (Optional)" class="text-sm font-medium text-gray-700" />
                        <TextInput id="email" type="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="studentForm.email" />
                        <InputError class="mt-2" :message="studentForm.errors.email" />
                    </div>
                    <div class="flex justify-end space-x-3">
                        <SecondaryButton @click="showStudentModal = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition shadow-sm">
                            <i class="bx bx-x mr-2"></i> Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                            <i class="bx bx-check mr-2"></i> Add Student
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Student Modal -->
        <Modal :show="showEditStudentModal" @close="showEditStudentModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-edit-alt mr-2 text-indigo-600"></i> Edit Student
                </h2>
                <form @submit.prevent="updateStudent" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="edit-name" value="Student Name" class="text-sm font-medium text-gray-700" />
                        <TextInput id="edit-name" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.name" required autofocus />
                        <InputError class="mt-2" :message="editStudentForm.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="edit-student_id" value="Student ID"
                            class="text-sm font-medium text-gray-700" />
                        <TextInput id="edit-student_id" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.student_id" required />
                        <InputError class="mt-2" :message="editStudentForm.errors.student_id" />
                    </div>
                    <div>
                        <InputLabel for="edit-email" value="Email (Optional)"
                            class="text-sm font-medium text-gray-700" />
                        <TextInput id="edit-email" type="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.email" />
                        <InputError class="mt-2" :message="editStudentForm.errors.email" />
                    </div>
                    <div class="flex justify-end space-x-3">
                        <SecondaryButton @click="showEditStudentModal = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition shadow-sm">
                            <i class="bx bx-x mr-2"></i> Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                            <i class="bx bx-check mr-2"></i> Save Changes
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style>
@import 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css';

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
