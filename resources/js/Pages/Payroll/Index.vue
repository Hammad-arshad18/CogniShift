<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ records: Array, employees: Array });
const showModal = ref(false);

const form = useForm({
    employee_id: '',
    pay_period_start: '',
    pay_period_end: '',
    base_salary: '',
    overtime_pay: '0',
    deductions: '0',
    status: 'draft',
});

const statusMap = {
    draft:      { bg: 'rgba(148,163,184,0.12)', color: '#94a3b8', label: 'Draft' },
    processing: { bg: 'rgba(59,130,246,0.12)',  color: '#60a5fa', label: 'Processing' },
    paid:       { bg: 'rgba(16,185,129,0.12)',  color: '#10b981', label: 'Paid' },
    failed:     { bg: 'rgba(239,68,68,0.12)',   color: '#ef4444', label: 'Failed' },
};

const formatCurrency = (v) =>
    new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v || 0);

const netPay = (r) =>
    (Number(r.base_salary) + Number(r.overtime_pay) - Number(r.deductions));

const totalPayroll = computed(() =>
    props.records.reduce((sum, r) => sum + netPay(r), 0)
);
const paidCount = computed(() => props.records.filter(r => r.status === 'paid').length);
const draftCount = computed(() => props.records.filter(r => r.status === 'draft').length);

const editingRecord = ref(null);

const openCreate = () => {
    editingRecord.value = null;
    form.reset();
    showModal.value = true;
};

const openEdit = (record) => {
    editingRecord.value = record;
    form.employee_id = record.employee_id;
    form.pay_period_start = record.pay_period_start;
    form.pay_period_end = record.pay_period_end;
    form.base_salary = record.base_salary;
    form.overtime_pay = record.overtime_pay || '0';
    form.deductions = record.deductions || '0';
    form.status = record.status;
    showModal.value = true;
};

const submit = () => {
    if (editingRecord.value) {
        form.put(route('payroll.update', editingRecord.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('payroll.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
};

const destroy = (id) => {
    if (confirm('Delete this record?')) {
        useForm({}).delete(route('payroll.destroy', id));
    }
};
</script>

<template>
    <Head title="Payroll" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">Payroll Records</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Manage salary processing and payslips</p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 8px 20px rgba(139,92,246,0.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Record
                </button>
            </div>
        </template>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl p-5 flex items-center gap-4" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ formatCurrency(totalPayroll) }}</div>
                    <div class="text-xs text-slate-500">Total Payroll</div>
                </div>
            </div>
            <div class="rounded-2xl p-5 flex items-center gap-4" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ paidCount }}</div>
                    <div class="text-xs text-slate-500">Paid Records</div>
                </div>
            </div>
            <div class="rounded-2xl p-5 flex items-center gap-4" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ draftCount }}</div>
                    <div class="text-xs text-slate-500">Pending Drafts</div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal = false">
            <div class="w-full max-w-lg rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">{{ editingRecord ? 'Edit Payroll Record' : 'New Payroll Record' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div v-if="!editingRecord">
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Employee</label>
                        <select v-model="form.employee_id" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="" disabled>Select employee...</option>
                            <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.user?.name }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3" v-if="!editingRecord">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Period Start</label>
                            <input v-model="form.pay_period_start" type="date" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Period End</label>
                            <input v-model="form.pay_period_end" type="date" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Base Salary</label>
                            <input v-model="form.base_salary" type="number" step="0.01" required placeholder="0.00" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Overtime</label>
                            <input v-model="form.overtime_pay" type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1.5">Deductions</label>
                            <input v-model="form.deductions" type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">{{ editingRecord ? 'Update Record' : 'Create Record' }}</button>
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
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Pay Period</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Base</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Overtime</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Deductions</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Net Pay</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Status</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in records" :key="record.id"
                            class="hover:bg-white/[0.02] transition-colors"
                            style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                                        {{ record.employee?.user?.name?.charAt(0) }}
                                    </div>
                                    <span class="text-sm font-medium text-white">{{ record.employee?.user?.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ record.pay_period_start }} → {{ record.pay_period_end }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-slate-300">{{ formatCurrency(record.base_salary) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-green-400">+{{ formatCurrency(record.overtime_pay) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-red-400">-{{ formatCurrency(record.deductions) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-white">{{ formatCurrency(netPay(record)) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase"
                                    :style="`background: ${statusMap[record.status]?.bg}; color: ${statusMap[record.status]?.color};`">
                                    {{ statusMap[record.status]?.label || record.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(record)" class="px-3 py-1.5 text-xs font-medium text-purple-400 rounded-lg hover:bg-purple-500/10 transition">Edit</button>
                                    <button @click="destroy(record.id)" class="px-3 py-1.5 text-xs font-medium text-red-400 rounded-lg hover:bg-red-500/10 transition">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="records.length === 0">
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: rgba(139,92,246,0.1);">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 text-sm">No payroll records yet. Create the first one.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
