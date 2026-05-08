<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const sidebarOpen = ref(false);

const navItems = [
    { name: 'Dashboard', route: 'dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', color: 'from-indigo-500 to-violet-500' },
    { name: 'Employees', route: 'employees.index', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', color: 'from-blue-500 to-cyan-500' },
    { name: 'Departments', route: 'departments.index', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', color: 'from-emerald-500 to-teal-500' },
    { name: 'Roles', route: 'roles.index', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', color: 'from-amber-500 to-orange-500' },
    { name: 'Attendance', route: 'attendances.index', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', color: 'from-pink-500 to-rose-500' },
    { name: 'Payroll', route: 'payroll.index', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', color: 'from-fuchsia-500 to-purple-500' },
    { name: 'AI Reviews', route: 'reviews.index', icon: 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', color: 'from-violet-500 to-fuchsia-500' },
];

const isActive = (routeName) => {
    try { return route().current(routeName.replace('.index', '.*')); }
    catch(e) { return false; }
};
</script>

<template>
    <div class="flex h-screen overflow-hidden" style="background: #070d1a; font-family: 'Inter', sans-serif;">

        <!-- Mobile Sidebar Backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-30 w-64 flex flex-col transition-transform duration-300"
            style="background: #0d1424; border-right: 1px solid rgba(255,255,255,0.06);"
        >
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 h-16 flex-shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <span class="text-white font-bold text-base tracking-tight">NexusHR</span>
                    <div class="text-xs" style="color: #6366f1;">Enterprise</div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                <div class="mb-3 px-3">
                    <span class="text-xs font-semibold uppercase tracking-widest" style="color: rgba(255,255,255,0.2);">Main Menu</span>
                </div>
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group"
                    :class="isActive(item.route)
                        ? 'text-white'
                        : 'text-slate-400 hover:text-slate-200 hover:bg-white/5'"
                    :style="isActive(item.route) ? 'background: rgba(99,102,241,0.15); color: white;' : ''"
                >
                    <span
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all"
                        :class="`bg-gradient-to-br ${item.color}`"
                        :style="isActive(item.route) ? 'opacity: 1;' : 'opacity: 0.5;'"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                        </svg>
                    </span>
                    {{ item.name }}
                    <span v-if="isActive(item.route)" class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #6366f1;"></span>
                </Link>
            </nav>

            <!-- User Profile -->
            <div class="px-4 py-4 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-3 p-3 rounded-xl" style="background: rgba(255,255,255,0.04);">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm text-white" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</div>
                        <div class="text-xs truncate" style="color: rgba(255,255,255,0.35);">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="p-1.5 rounded-lg transition-colors hover:bg-white/10" title="Logout">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <header class="h-16 flex-shrink-0 flex items-center justify-between px-6" style="background: #070d1a; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Page Header Slot -->
                <div class="hidden lg:block flex-1">
                    <slot name="header" />
                </div>

                <!-- Right section -->
                <div class="flex items-center gap-3 ml-auto">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background: #ef4444;"></span>
                    </button>

                    <!-- Avatar dropdown -->
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl cursor-pointer hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm text-white" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                        <span class="hidden md:block text-sm font-medium text-slate-300">{{ $page.props.auth.user.name }}</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </header>

            <!-- Mobile Page Header -->
            <div class="lg:hidden px-6 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <slot name="header" />
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6" style="background: #070d1a;">
                <slot />
            </main>
        </div>
    </div>
</template>
