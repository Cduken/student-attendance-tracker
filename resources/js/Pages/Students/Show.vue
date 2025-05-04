<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';

// Define props
const props = defineProps({
    student: Object,
});

// Edit Student Modal
const showEditStudentModal = ref(false);
const editStudentForm = ref({
    name: '',
    email: '',
    student_id: '',
    errors: {},
});

const openEditStudentModal = () => {
    editStudentForm.value = {
        name: props.student.name || '',
        email: props.student.email || '',
        student_id: props.student.student_id || '',
        errors: {},
    };
    showEditStudentModal.value = true;
};

const updateStudent = () => {
    router.put(route('students.update', props.student.id), {
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
</script>

<template>
    <Head :title="`Student: ${student.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-900">Student Details</h2>
                <Link :href="route('classes.show', student.class.id)" class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 transition">
                    <i class="bx bx-arrow-back mr-1"></i> Back to Class
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <!-- Student Info Card -->
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <i class="bx bx-user-circle text-4xl text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-semibold text-gray-900">{{ student.name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Student ID: {{ student.student_id }}</p>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="bx bx-envelope mr-2 text-indigo-500"></i>
                                    <span>Email: {{ student.email || 'N/A' }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="bx bx-book mr-2 text-indigo-500"></i>
                                    <span>Class: </span>
                                    <Link v-if="student.class" :href="route('classes.show', student.class.id)" class="ml-1 text-indigo-600 hover:text-indigo-800 transition">
                                        {{ student.class.name }}
                                    </Link>
                                    <span v-else class="ml-1">N/A</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-4">
                            <button @click="openEditStudentModal" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                                <i class="bx bx-edit-alt mr-2"></i> Edit Student
                            </button>
                            <Link :href="route('classes.show', student.class.id)" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition shadow-sm">
                                <i class="bx bx-group mr-2"></i> View Class
                            </Link>
                        </div>
                    </div>

                    <!-- Attendance Records -->
                    <div class="p-8">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bx bx-calendar-check mr-2 text-indigo-500"></i> Attendance Records
                        </h4>
                        <div v-if="student.attendances && student.attendances.length" class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="attendance in student.attendances" :key="attendance.id" class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ attendance.date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 py-1 text-xs font-medium rounded-full': true,
                                                'bg-green-100 text-green-800': attendance.status === 'Present',
                                                'bg-red-100 text-red-800': attendance.status === 'Absent',
                                                'bg-yellow-100 text-yellow-800': attendance.status === 'Late'
                                            }">
                                                {{ attendance.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else class="mt-4 text-sm text-gray-500 flex items-center">
                            <i class="bx bx-info-circle mr-2 text-indigo-500"></i> No attendance records found.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Student Modal -->
        <Modal :show="showEditStudentModal" @close="showEditStudentModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Edit Student</h2>
                <form @submit.prevent="updateStudent" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="edit-name" value="Student Name" />
                        <TextInput
                            id="edit-name"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.name"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="editStudentForm.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="edit-student_id" value="Student ID" />
                        <TextInput
                            id="edit-student_id"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.student_id"
                            required
                        />
                        <InputError class="mt-2" :message="editStudentForm.errors.student_id" />
                    </div>
                    <div>
                        <InputLabel for="edit-email" value="Email (Optional)" />
                        <TextInput
                            id="edit-email"
                            type="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            v-model="editStudentForm.email"
                        />
                        <InputError class="mt-2" :message="editStudentForm.errors.email" />
                    </div>
                    <div class="flex justify-end space-x-3">
                        <SecondaryButton @click="showEditStudentModal = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" class="bg-indigo-600 hover:bg-indigo-700">
                            Save Changes
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

