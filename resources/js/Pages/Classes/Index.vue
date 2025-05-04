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
        description: createForm.value.description,
    }, {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.value = {
                name: '',
                subject: '',
                description: '',
                errors: {},
            };
        },
        onError: (errors) => {
            createForm.value.errors = errors;
        },
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
        errors: {},
    };
    showEditModal.value = true;
};

const updateClass = () => {
    router.put(route('classes.update', editForm.value.id), {
        name: editForm.value.name,
        subject: editForm.value.subject,
        description: editForm.value.description,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
        },
        onError: (errors) => {
            editForm.value.errors = errors;
        },
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
                <h2 class="text-xl font-semibold leading-tight text-gray-800">My Classes</h2>
                <PrimaryButton @click="showCreateModal = true"
                    class="inline-flex items-center bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                    <i class="bx bx-plus mr-2"></i> Create New Class
                </PrimaryButton>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div v-if="classes.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="bx bx-book-open mx-auto text-5xl text-gray-400"></i>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">No classes yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Get started by creating a new class.</p>
                            <div class="mt-6">
                                <PrimaryButton @click="showCreateModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                                    <i class="bx bx-plus mr-2"></i> Create Class
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
                                    <tr v-for="classItem in classes" :key="classItem.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <i class="bx bx-book-alt text-xl text-indigo-600"></i>
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
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ classItem.subject }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <i class="bx bx-user mr-1 text-gray-400"></i>
                                                {{ classItem.students_count }} students
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openEditModal(classItem)"
                                                class="text-indigo-600 hover:text-indigo-800 mr-4 transition-colors">
                                                <i class="bx bx-edit-alt mr-1"></i> Edit
                                            </button>
                                            <button @click="deleteClass(classItem.id)"
                                                class="text-red-600 hover:text-red-800 transition-colors">
                                                <i class="bx bx-trash mr-1"></i> Delete
                                            </button>
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
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="bx bx-plus-circle mr-2 text-indigo-600"></i> Create New Class
                    </h2>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-500">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <form @submit.prevent="createClass" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Class Name" class="text-sm font-medium text-gray-700" />
                        <TextInput id="name" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.name" required autofocus />
                        <InputError class="mt-2" :message="createForm.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="subject" value="Subject" class="text-sm font-medium text-gray-700" />
                        <TextInput id="subject" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.subject" required />
                        <InputError class="mt-2" :message="createForm.errors.subject" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description (Optional)"
                            class="text-sm font-medium text-gray-700" />
                        <textarea id="description"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="createForm.description" rows="4"></textarea>
                        <InputError class="mt-2" :message="createForm.errors.description" />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <SecondaryButton @click="showCreateModal = false" type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition shadow-sm">
                            <i class="bx bx-x mr-2"></i> Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                            <i class="bx bx-check mr-2"></i> Create Class
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Class Modal -->
        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="bx bx-edit-alt mr-2 text-indigo-600"></i> Edit Class
                    </h2>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <form @submit.prevent="updateClass" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="edit-name" value="Class Name" class="text-sm font-medium text-gray-700" />
                        <TextInput id="edit-name" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.name" required autofocus />
                        <InputError class="mt-2" :message="editForm.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="edit-subject" value="Subject" class="text-sm font-medium text-gray-700" />
                        <TextInput id="edit-subject" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.subject" required />
                        <InputError class="mt-2" :message="editForm.errors.subject" />
                    </div>

                    <div>
                        <InputLabel for="edit-description" value="Description (Optional)"
                            class="text-sm font-medium text-gray-700" />
                        <textarea id="edit-description"
                            class="mt-1 block w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                            v-model="editForm.description" rows="4"></textarea>
                        <InputError class="mt-2" :message="editForm.errors.description" />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <SecondaryButton @click="showEditModal = false" type="button"
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
