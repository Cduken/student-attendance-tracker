<!-- resources/js/Components/AttendanceCheckbox.vue -->
<script setup>
const props = defineProps({
    status: {
        type: String,
        default: 'absent'
    },
    studentId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['update:status']);

const updateStatus = (newStatus) => {
    emit('update:status', newStatus);
};

const statusClasses = {
    present: 'bg-green-100 text-green-800 border-green-300',
    absent: 'bg-red-100 text-red-800 border-red-300',
    late: 'bg-yellow-100 text-yellow-800 border-yellow-300',
    excused: 'bg-blue-100 text-blue-800 border-blue-300',
};
</script>

<template>
    <div class="flex space-x-2">
        <button
            @click="updateStatus('present')"
            :class="[
                'px-3 py-1 rounded border',
                status === 'present' ? statusClasses.present : 'bg-gray-100 text-gray-800 border-gray-300'
            ]"
        >
            Present
        </button>
        <button
            @click="updateStatus('absent')"
            :class="[
                'px-3 py-1 rounded border',
                status === 'absent' ? statusClasses.absent : 'bg-gray-100 text-gray-800 border-gray-300'
            ]"
        >
            Absent
        </button>
        <button
            @click="updateStatus('late')"
            :class="[
                'px-3 py-1 rounded border',
                status === 'late' ? statusClasses.late : 'bg-gray-100 text-gray-800 border-gray-300'
            ]"
        >
            Late
        </button>
        <button
            @click="updateStatus('excused')"
            :class="[
                'px-3 py-1 rounded border',
                status === 'excused' ? statusClasses.excused : 'bg-gray-100 text-gray-800 border-gray-300'
            ]"
        >
            Excused
        </button>
    </div>
</template>
