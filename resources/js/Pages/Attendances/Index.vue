<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ attendances: Array, employees: Array });
const showModal = ref(false);
const form = useForm({ employee_id: '', date: new Date().toISOString().split('T')[0], clock_in: '', clock_out: '', status: 'present' });

const statusMap = {
    present: { bg: 'rgba(16,185,129,0.12)', color: '#10b981', label: 'Present' },
    absent:  { bg: 'rgba(239,68,68,0.12)', color: '#ef4444', label: 'Absent' },
    late:    { bg: 'rgba(245,158,11,0.12)', color: '#f59e0b', label: 'Late' },
    half_day:{ bg: 'rgba(99,102,241,0.12)', color: '#818cf8', label: 'Half Day' },
};

const editingAtt = ref(null);

const openCreate = () => {
    editingAtt.value = null;
    form.reset();
    form.date = new Date().toISOString().split('T')[0];
    showModal.value = true;
};

const openEdit = (att) => {
    editingAtt.value = att;
    form.employee_id = att.employee_id;
    form.date = att.date;
    form.status = att.status;
    form.clock_in = att.clock_in ? new Date(att.clock_in).toISOString().slice(0, 16) : '';
    form.clock_out = att.clock_out ? new Date(att.clock_out).toISOString().slice(0, 16) : '';
    showModal.value = true;
};

const formatTime = (dt) => { if (!dt) return '—'; return new Date(dt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); };

const submit = () => {
    if (editingAtt.value) {
        form.put(route('attendances.update', editingAtt.value.id), { onSuccess: () => { showModal.value = false; form.reset(); } });
    } else {
        form.post(route('attendances.store'), { onSuccess: () => { showModal.value = false; form.reset(); } });
    }
};

const destroy = (id) => {
    if (confirm('Delete this record?')) {
        useForm({}).delete(route('attendances.destroy', id));
    }
};
</script>

<template>
    <Head title="Attendance" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">Attendance</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Track clock-in/out and status</p>
                </div>
                <button @click="openCreate" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:-translate-y-0.5" style="background: linear-gradient(135deg, #ec4899, #db2777); box-shadow: 0 8px 20px rgba(236,72,153,0.25);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Log Entry
                </button>
            </div>
        </template>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal=false">
            <div class="w-full max-w-md rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">{{ editingAtt ? 'Edit Entry' : 'Log Attendance' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div v-if="!editingAtt">
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Employee</label>
                        <select v-model="form.employee_id" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="" disabled>Select employee...</option>
                            <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.user?.name }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Date</label>
                            <input v-model="form.date" type="date" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Status</label>
                            <select v-model="form.status" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half Day</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Clock In</label>
                            <input v-model="form.clock_in" type="datetime-local" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Clock Out</label>
                            <input v-model="form.clock_out" type="datetime-local" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl" style="background: linear-gradient(135deg, #ec4899, #db2777);">{{ editingAtt ? 'Update' : 'Log Entry' }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="rounded-2xl overflow-hidden" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Employee</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Date</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Clock In</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Clock Out</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Status</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="att in attendances" :key="att.id" class="hover:bg-white/[0.02] transition-colors" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: linear-gradient(135deg, #ec4899, #db2777);">{{ att.employee?.user?.name?.charAt(0) }}</div>
                                    <span class="text-sm font-medium text-white">{{ att.employee?.user?.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 text-center">{{ att.date }}</td>
                            <td class="px-6 py-4 text-center font-mono text-sm text-green-400">{{ formatTime(att.clock_in) }}</td>
                            <td class="px-6 py-4 text-center font-mono text-sm text-rose-400">{{ formatTime(att.clock_out) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase" :style="`background: ${statusMap[att.status]?.bg}; color: ${statusMap[att.status]?.color};`">{{ statusMap[att.status]?.label || att.status }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(att)" class="px-3 py-1.5 text-xs font-medium text-pink-400 rounded-lg hover:bg-pink-500/10 transition">Edit</button>
                                    <button @click="destroy(att.id)" class="px-3 py-1.5 text-xs font-medium text-red-400 rounded-lg hover:bg-red-500/10 transition">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="attendances.length === 0">
                            <td colspan="6" class="py-16 text-center text-slate-500 text-sm">No attendance records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
