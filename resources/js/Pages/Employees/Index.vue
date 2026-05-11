<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ employees: Array, departments: Array, roles: Array, users: Array });
const showModal = ref(false);
const editingEmp = ref(null);
const form = useForm({ user_id: '', department_id: '', role_id: '', date_of_joining: '', status: 'active' });

const statusOptions = [
    { value: 'active', label: 'Active', bg: 'rgba(16,185,129,0.12)', color: '#10b981' },
    { value: 'on_leave', label: 'On Leave', bg: 'rgba(245,158,11,0.12)', color: '#f59e0b' },
    { value: 'terminated', label: 'Terminated', bg: 'rgba(239,68,68,0.12)', color: '#ef4444' },
];

const getStatus = (s) => statusOptions.find(o => o.value === s) || statusOptions[0];

const openCreate = () => {
    editingEmp.value = null;
    form.reset();
    form.date_of_joining = new Date().toISOString().split('T')[0];
    showModal.value = true;
};

const openEdit = (emp) => {
    editingEmp.value = emp;
    form.user_id = emp.user_id;
    form.department_id = emp.department_id || '';
    form.role_id = emp.role_id || '';
    form.date_of_joining = emp.date_of_joining || '';
    form.status = emp.status;
    showModal.value = true;
};

const submit = () => {
    if (editingEmp.value) {
        form.put(route('employees.update', editingEmp.value.id), { onSuccess: () => { showModal.value = false; form.reset(); } });
    } else {
        form.post(route('employees.store'), { onSuccess: () => { showModal.value = false; form.reset(); } });
    }
};

const destroy = (id) => {
    if (confirm('Delete this employee?')) {
        useForm({}).delete(route('employees.destroy', id));
    }
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
                <button @click="openCreate" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:-translate-y-0.5" style="background: linear-gradient(135deg, #3b82f6, #06b6d4); box-shadow: 0 8px 20px rgba(59,130,246,0.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Employee
                </button>
            </div>
        </template>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal=false">
            <div class="w-full max-w-md rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">{{ editingEmp ? 'Update Employee' : 'New Employee' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div v-if="!editingEmp">
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">User</label>
                        <select v-model="form.user_id" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="" disabled>Select User...</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                        </select>
                    </div>
                    <div v-if="!editingEmp">
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Date of Joining</label>
                        <input v-model="form.date_of_joining" type="date" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                    </div>
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
                        <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">{{ editingEmp ? 'Update' : 'Create' }}</button>
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
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(emp)" class="px-3 py-1.5 text-xs font-medium text-blue-400 rounded-lg hover:bg-blue-500/10 transition">Edit</button>
                                    <button @click="destroy(emp.id)" class="px-3 py-1.5 text-xs font-medium text-red-400 rounded-lg hover:bg-red-500/10 transition">Delete</button>
                                </div>
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
