<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h1 class="text-xl font-bold text-white">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-0.5">Welcome back — here's what's happening today.</p>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            <!-- Card -->
            <div v-for="stat in stats" :key="stat.label"
                class="rounded-2xl p-5 flex items-center gap-4 transition-all hover:scale-[1.02]"
                style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);"
            >
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" :style="`background: linear-gradient(135deg, ${stat.from}, ${stat.to});`">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">{{ stat.value }}</div>
                    <div class="text-sm text-slate-400">{{ stat.label }}</div>
                </div>
            </div>
        </div>

        <!-- Two column layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Recent Activity -->
            <div class="xl:col-span-2 rounded-2xl p-6" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-semibold text-white">Recent Activity</h3>
                    <span class="text-xs text-indigo-400 cursor-pointer hover:text-indigo-300">View all</span>
                </div>
                <div class="space-y-4">
                    <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-xs text-white" :style="`background: linear-gradient(135deg, ${activity.from}, ${activity.to});`">
                            {{ activity.initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-300">
                                <span class="font-medium text-white">{{ activity.name }}</span>
                                {{ activity.action }}
                            </p>
                            <p class="text-xs text-slate-600 mt-0.5">{{ activity.time }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0" :style="`background: ${activity.badgeBg}; color: ${activity.badgeColor};`">
                            {{ activity.badge }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Headcount by Department -->
            <div class="rounded-2xl p-6" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
                <h3 class="text-base font-semibold text-white mb-5">Headcount by Dept.</h3>
                <div class="space-y-4">
                    <div v-for="dept in departments" :key="dept.name">
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-slate-400">{{ dept.name }}</span>
                            <span class="text-white font-medium">{{ dept.count }}</span>
                        </div>
                        <div class="h-2 rounded-full" style="background: rgba(255,255,255,0.07);">
                            <div class="h-2 rounded-full transition-all duration-700" :style="`width: ${dept.pct}%; background: linear-gradient(90deg, ${dept.from}, ${dept.to});`"></div>
                        </div>
                    </div>
                </div>

                <!-- AI Burnout alert -->
                <div class="mt-6 rounded-xl p-4" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-xs font-semibold text-red-400">AI Burnout Alert</span>
                    </div>
                    <p class="text-xs text-slate-400">2 employees show high burnout risk based on overtime patterns.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            stats: [
                { label: 'Total Employees', value: '124', from: '#6366f1', to: '#8b5cf6', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
                { label: 'Present Today', value: '98', from: '#10b981', to: '#059669', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
                { label: 'Payroll This Month', value: '$84.2K', from: '#f59e0b', to: '#d97706', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
                { label: 'Open Positions', value: '7', from: '#ec4899', to: '#db2777', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
            ],
            recentActivity: [
                { id: 1, name: 'Sarah K.', action: 'clocked in at 09:02 AM', time: '2 minutes ago', initials: 'SK', from: '#6366f1', to: '#8b5cf6', badge: 'On Time', badgeBg: 'rgba(16,185,129,0.12)', badgeColor: '#10b981' },
                { id: 2, name: 'James R.', action: 'submitted a leave request', time: '15 minutes ago', initials: 'JR', from: '#f59e0b', to: '#d97706', badge: 'Pending', badgeBg: 'rgba(245,158,11,0.12)', badgeColor: '#f59e0b' },
                { id: 3, name: 'Payroll Batch', action: 'generated for April 2026', time: '1 hour ago', initials: 'AI', from: '#ec4899', to: '#db2777', badge: 'Draft', badgeBg: 'rgba(99,102,241,0.12)', badgeColor: '#818cf8' },
                { id: 4, name: 'AI Review', action: 'generated for Marcus T.', time: '3 hours ago', initials: 'AI', from: '#8b5cf6', to: '#6d28d9', badge: 'Complete', badgeBg: 'rgba(139,92,246,0.12)', badgeColor: '#c084fc' },
            ],
            departments: [
                { name: 'Engineering', count: 42, pct: 72, from: '#6366f1', to: '#8b5cf6' },
                { name: 'Marketing', count: 18, pct: 30, from: '#ec4899', to: '#db2777' },
                { name: 'HR & Admin', count: 11, pct: 18, from: '#10b981', to: '#059669' },
                { name: 'Finance', count: 9, pct: 15, from: '#f59e0b', to: '#d97706' },
            ],
        };
    },
};
</script>
