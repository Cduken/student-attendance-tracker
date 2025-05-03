<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    as: {
        type: String,
        default: 'a'
    }
});
</script>

<template>
    <Link
        :href="href"
        :as="as"
        class="flex items-center w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150"
    >
        <slot />
    </Link>
</template>
