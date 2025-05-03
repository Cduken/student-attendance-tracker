<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() => {
    const baseClasses = 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out';

    return props.active
        ? `${baseClasses} bg-blue-50 text-blue-600 font-semibold`
        : `${baseClasses} text-gray-600 hover:bg-gray-100 hover:text-gray-900`;
});
</script>

<template>
    <Link
        :href="href"
        :class="classes"
        class="group relative"
    >
        <slot />
        <span
            v-if="active"
            class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-1 bg-blue-600 rounded-r-full"
        ></span>
    </Link>
</template>
