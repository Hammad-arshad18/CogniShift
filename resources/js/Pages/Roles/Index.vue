<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ roles: Array });
const showModal = ref(false);
const editingRole = ref(null);
const form = useForm({ name: '', description: '' });

const openCreate = () => { editingRole.value = null; form.reset(); showModal.value = true; };
const openEdit = (role) => { editingRole.value = role; form.name = role.name; form.description = role.description || ''; showModal.value = true; };

const submit = () => {
    if (editingRole.value) {
        form.put(route('roles.update', editingRole.value.id), { onSuccess: () => { showModal.value = false; form.reset(); } });
    } else {
        form.post(route('roles.store'), { onSuccess: () => { showModal.value = false; form.reset(); } });
    }
};

const destroy = (id) => { if (confirm('Delete this role?')) useForm({}).delete(route('roles.destroy', id)); };
</script>

<template>
    <Head title="Roles" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">Roles</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Define job roles and permissions</p>
                </div>
                <button @click="openCreate" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:-translate-y-0.5" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 8px 20px rgba(245,158,11,0.25);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Role
                </button>
            </div>
        </template>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal=false">
            <div class="w-full max-w-md rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">{{ editingRole ? 'Edit Role' : 'New Role' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Role Name</label>
                        <input v-model="form.name" type="text" required placeholder="e.g. Senior Developer" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Description</label>
                        <textarea v-model="form.description" rows="3" class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: linear-gradient(135deg, #f59e0b, #d97706);">{{ editingRole ? 'Update' : 'Create' }}</button>
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
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Role</th>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Description</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Members</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider text-slate-500 px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="role in roles" :key="role.id" class="hover:bg-white/[0.02] transition-colors" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <span class="font-semibold text-white text-sm">{{ role.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">{{ role.description || '—' }}</td>
                            <td class="px-6 py-4 text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold" style="background: rgba(245,158,11,0.12); color: #fbbf24;">{{ role.employees_count }}</span></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(role)" class="px-3 py-1.5 text-xs font-medium text-amber-400 rounded-lg hover:bg-amber-500/10 transition">Edit</button>
                                    <button @click="destroy(role.id)" class="px-3 py-1.5 text-xs font-medium text-red-400 rounded-lg hover:bg-red-500/10 transition">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="roles.length === 0">
                            <td colspan="4" class="py-16 text-center text-slate-500 text-sm">No roles defined yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
