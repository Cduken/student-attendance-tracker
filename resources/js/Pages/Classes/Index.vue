<!-- resources/js/Pages/Classes/Index.vue -->
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

defineProps({
    classes: Array,
});

// Create Class Modal
const showCreateModal = ref(false);
const createForm = ref({
    name: '',
    subject: '',
    description: '',
    errors: {},
});

const createClass = () => {
    router.post(route('classes.store'), {
        name: createForm.value.name,
        subject: createForm.value.subject,
        description: createForm.value.description
    }, {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.value = {
                name: '',
                subject: '',
                description: '',
                errors: {}
            };
        },
        onError: (errors) => {
            createForm.value.errors = errors;
        }
    });
};

// Edit Class Modal
const showEditModal = ref(false);
const editForm = ref({
    id: null,
    name: '',
    subject: '',
    description: '',
    errors: {},
});

const openEditModal = (classItem) => {
    editForm.value = {
        id: classItem.id,
        name: classItem.name,
        subject: classItem.subject,
        description: classItem.description,
        errors: {}
    };
    showEditModal.value = true;
};

const updateClass = () => {
    router.put(route('classes.update', editForm.value.id), {
        name: editForm.value.name,
        subject: editForm.value.subject,
        description: editForm.value.description
    }, {
        preserveScroll: true,  // Add this to prevent scrolling to top
        onSuccess: () => {
            showEditModal.value = false;
        },
        onError: (errors) => {
            editForm.value.errors = errors;
        }
    });
};

const deleteClass = (id) => {
    if (confirm('Are you sure you want to delete this class?')) {
        router.delete(route('classes.destroy', id));
    }
};
</script>

<template>

    <Head title="Classes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-5">My Classes</h2>
                <PrimaryButton @click="showCreateModal = true" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Create New Class
                </PrimaryButton>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="classes.length === 0" class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No classes yet</h3>
                            <p class="mt-1 text-gray-500">Get started by creating a new class.</p>
                            <div class="mt-6">
                                <PrimaryButton @click="showCreateModal = true">
                                    Create Class
                                </PrimaryButton>
                            </div>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Class Name</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subject</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Students</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="classItem in classes" :key="classItem.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <Link :href="route('classes.show', classItem.id)"
                                                        class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                                                    {{ classItem.name }}
                                                    </Link>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ classItem.subject }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                {{ classItem.students_count }} students
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openEditModal(classItem)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                            <button @click="deleteClass(classItem.id)"
                                                class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Class Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Create New Class</h2>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="createClass" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Class Name" class="block text-sm font-medium text-gray-700" />
                        <TextInput id="name" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.name" required autofocus />
                        <InputError class="mt-2" :message="createForm.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="subject" value="Subject" class="block text-sm font-medium text-gray-700" />
                        <TextInput id="subject" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.subject" required />
                        <InputError class="mt-2" :message="createForm.errors.subject" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description (Optional)"
                            class="block text-sm font-medium text-gray-700" />
                        <textarea id="description"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.description" rows="3"></textarea>
                        <InputError class="mt-2" :message="createForm.errors.description" />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <SecondaryButton @click="showCreateModal = false" type="button">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit">
                            Create Class
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Class Modal -->
        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Class</h2>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="updateClass" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="edit-name" value="Class Name"
                            class="block text-sm font-medium text-gray-700" />
                        <TextInput id="edit-name" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.name" required autofocus />
                        <InputError class="mt-2" :message="editForm.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="edit-subject" value="Subject"
                            class="block text-sm font-medium text-gray-700" />
                        <TextInput id="edit-subject" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.subject" required />
                        <InputError class="mt-2" :message="editForm.errors.subject" />
                    </div>

                    <div>
                        <InputLabel for="edit-description" value="Description"
                            class="block text-sm font-medium text-gray-700" />
                        <textarea id="edit-description"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.description" rows="3"></textarea>
                        <InputError class="mt-2" :message="editForm.errors.description" />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <SecondaryButton @click="showEditModal = false" type="button">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit">
                            Save Changes
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Add smooth transitions for hover effects */
tr {
    transition: background-color 0.2s ease;
}

/* Improve modal transitions */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Style the table rows with alternating colors */
tbody tr:nth-child(odd) {
    background-color: #f9fafb;
}

/* Improve button transitions */
button {
    transition: color 0.2s ease;
}

/* Style the empty state */
.empty-state {
    background-color: #f8fafc;
}
</style>
