<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingMobileMenu = ref(false);
const page = usePage();

// Compute user initials for avatar
const userInitials = computed(() => {
    const name = page.props.auth.user.name;
    return name.split(' ').map(part => part[0]).join('').toUpperCase();
});

// Static navigation items
const navItems = [
    { href: '/dashboard', icon: 'bx-home', label: 'Dashboard', routePattern: 'dashboard' },
    { href: '/classes', icon: 'bx-group', label: 'Classes', routePattern: 'classes*' },
    { href: '/students', icon: 'bx-user', label: 'Students', routePattern: 'students*' },
    { href: '/attendance', icon: 'bx-calendar-check', label: 'Attendance', routePattern: 'attendance*' },
];

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-blue-50/30 to-white">
        <!-- Mobile menu button -->
        <div class="sm:hidden fixed top-4 left-4 z-50">
            <button @click="showingMobileMenu = !showingMobileMenu"
                class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                <i class="bx bx-menu text-2xl"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <div class="hidden sm:fixed sm:inset-y-0 sm:flex sm:w-64 sm:flex-col">
            <!-- Sidebar component -->
            <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white shadow-lg">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex items-center px-4 mb-8">
                        <Link href="/dashboard" class="flex items-center focus:outline-none">
                        <ApplicationLogo class="h-8 w-auto" />
                        <span
                            class="ml-2 text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">AttendTrack</span>
                        </Link>
                    </div>
                    <nav class="flex-1 space-y-1 px-4">
                        <NavLink v-for="item in navItems" :key="item.href" :href="item.href"
                            :active="route().current(item.routePattern)"
                            class="group flex items-center px-4 py-4 text-sm font-medium rounded-lg transition-all duration-200 w-full"
                            activeClass="bg-blue-50 text-blue-600 font-semibold shadow-sm"
                            inactiveClass="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            <i class="bx mr-3 text-xl flex-shrink-0" :class="{
                                'text-blue-500': route().current(item.routePattern),
                                'text-gray-500 group-hover:text-blue-500': !route().current(item.routePattern),
                                [item.icon]: true
                            }"></i>
                            <span class="truncate">{{ item.label }}</span>
                            <span v-if="route().current(item.routePattern)"
                                class="ml-auto h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        </NavLink>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 flex-col p-4 border-t border-gray-200 bg-gray-50/50">
                    <!-- Profile and Settings Links -->
                    <div class="space-y-1 mb-4">
                        <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 w-full"
                            activeClass="bg-blue-50 text-blue-600 font-semibold shadow-sm"
                            inactiveClass="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            <i class="bx bx-cog mr-3 text-xl flex-shrink-0" :class="{
                                'text-blue-500': route().current('profile.edit'),
                                'text-gray-500 group-hover:text-blue-500': !route().current('profile.edit')
                            }"></i>
                            <span class="truncate">Profile Settings</span>
                        </NavLink>
                    </div>

                    <!-- User Info and Logout -->
                    <div class="group block w-full">
                        <div
                            class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center min-w-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                                    {{ userInitials }}
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">
                                        {{ page.props.auth.user.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                            <button @click="logout"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-blue-500 transition-all duration-200 flex-shrink-0"
                                title="Logout">
                                <i class="bx bx-log-out text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div v-show="showingMobileMenu" class="fixed inset-0 z-40 sm:hidden">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="showingMobileMenu = false">
            </div>
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white transform transition-all duration-300 ease-in-out"
                :class="showingMobileMenu ? 'translate-x-0' : '-translate-x-full'">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button"
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        @click="showingMobileMenu = false">
                        <i class="bx bx-x text-white text-2xl"></i>
                    </button>
                </div>
                <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                    <div class="flex items-center px-4 mb-8">
                        <ApplicationLogo class="h-8 w-auto" />
                        <span
                            class="ml-2 text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">AttendTrack</span>
                    </div>
                    <nav class="space-y-1 px-2">
                        <ResponsiveNavLink v-for="item in navItems" :key="item.href" :href="item.href"
                            :active="route().current(item.routePattern)"
                            class="group flex items-center px-4 py-3 text-base font-medium rounded-lg transition-all duration-200 w-full"
                            activeClass="bg-blue-50 text-blue-600 font-semibold shadow-sm"
                            inactiveClass="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            <i class="bx mr-3 text-xl flex-shrink-0" :class="{
                                'text-blue-500': route().current(item.routePattern),
                                'text-gray-500 group-hover:text-blue-500': !route().current(item.routePattern),
                                [item.icon]: true
                            }"></i>
                            <span class="truncate">{{ item.label }}</span>
                            <span v-if="route().current(item.routePattern)"
                                class="ml-auto h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        </ResponsiveNavLink>

                        <!-- Profile and Settings in mobile -->
                        <ResponsiveNavLink :href="route('profile.edit')" :active="route().current('profile.edit')"
                            class="group flex items-center px-4 py-3 text-base font-medium rounded-lg transition-all duration-200 w-full"
                            activeClass="bg-blue-50 text-blue-600 font-semibold shadow-sm"
                            inactiveClass="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            <i class="bx bx-cog mr-3 text-xl flex-shrink-0" :class="{
                                'text-blue-500': route().current('profile.edit'),
                                'text-gray-500 group-hover:text-blue-500': !route().current('profile.edit')
                            }"></i>
                            <span class="truncate">Profile Settings</span>
                        </ResponsiveNavLink>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 border-t border-gray-200 p-4 bg-gray-50/50">
                    <div class="group block w-full">
                        <div
                            class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center min-w-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                                    {{ userInitials }}
                                </div>
                                <div class="ml-3 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">
                                        {{ page.props.auth.user.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                            <button @click="logout"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-blue-500 transition-all duration-200 flex-shrink-0"
                                title="Logout">
                                <i class="bx bx-log-out text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-1 flex-col sm:pl-64">
            <!-- Page Heading -->
            <header class="bg-white shadow-sm" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class=" p-2">
                            <slot />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
