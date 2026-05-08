<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ employees: Array, departments: Array, roles: Array });
const showModal = ref(false);
const editingEmp = ref(null);
const form = useForm({ user_id: '', department_id: '', role_id: '', date_of_joining: '', status: 'active' });

const statusOptions = [
    { value: 'active', label: 'Active', bg: 'rgba(16,185,129,0.12)', color: '#10b981' },
    { value: 'on_leave', label: 'On Leave', bg: 'rgba(245,158,11,0.12)', color: '#f59e0b' },
    { value: 'terminated', label: 'Terminated', bg: 'rgba(239,68,68,0.12)', color: '#ef4444' },
];

const getStatus = (s) => statusOptions.find(o => o.value === s) || statusOptions[0];

const openEdit = (emp) => {
    editingEmp.value = emp;
    form.department_id = emp.department_id || '';
    form.role_id = emp.role_id || '';
    form.status = emp.status;
    showModal.value = true;
};

const submit = () => {
    form.put(route('employees.update', editingEmp.value.id), { onSuccess: () => { showModal.value = false; } });
};
</script>

<template>
    <Head title="Employees" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">Employees</h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ employees.length }} team members</p>
                </div>
            </div>
        </template>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal=false">
            <div class="w-full max-w-md rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">Update Employee</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Department</label>
                        <select v-model="form.department_id" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="">No Department</option>
                            <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Role</label>
                        <select v-model="form.role_id" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="">No Role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Status</label>
                        <select v-model="form.status" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="active">Active</option>
                            <option value="on_leave">On Leave</option>
                            <option value="terminated">Terminated</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-2xl overflow-hidden" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Employee</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Department / Role</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Joined</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Status</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emp in employees" :key="emp.id" class="hover:bg-white/[0.02] transition-colors" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">
                                        {{ emp.user?.name?.charAt(0) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-white">{{ emp.user?.name }}</div>
                                        <div class="text-xs text-slate-500">{{ emp.user?.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">{{ emp.department?.name || '—' }}</div>
                                <div class="text-xs text-slate-500">{{ emp.role?.name || '—' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">{{ emp.date_of_joining }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wide"
                                    :style="`background: ${getStatus(emp.status).bg}; color: ${getStatus(emp.status).color};`">
                                    {{ getStatus(emp.status).label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="openEdit(emp)" class="px-3 py-1.5 text-xs font-medium text-blue-400 rounded-lg hover:bg-blue-500/10 transition">Edit</button>
                            </td>
                        </tr>
                        <tr v-if="employees.length === 0">
                            <td colspan="5" class="py-16 text-center text-slate-500 text-sm">No employees found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
