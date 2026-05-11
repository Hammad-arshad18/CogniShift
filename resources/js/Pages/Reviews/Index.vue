<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ reviews: Array, employees: Array });
const showModal = ref(false);
const expandedReview = ref(null);

const form = useForm({
    employee_id: '',
    bullet_points: '',
});

const submit = () => {
    form.post(route('reviews.generate'), {
        onSuccess: () => { showModal.value = false; form.reset(); },
    });
};

const statusMap = {
    pending:   { bg: 'rgba(245,158,11,0.12)', color: '#f59e0b' },
    generated: { bg: 'rgba(99,102,241,0.12)',  color: '#818cf8' },
    finalized: { bg: 'rgba(16,185,129,0.12)',  color: '#10b981' },
};

const showEditModal = ref(false);
const editingReview = ref(null);
const editForm = useForm({ ai_generated_review: '', status: '' });

const openEdit = (review) => {
    editingReview.value = review;
    editForm.ai_generated_review = review.ai_generated_review || '';
    editForm.status = review.status;
    showEditModal.value = true;
};

const submitEdit = () => {
    editForm.put(route('reviews.update', editingReview.value.id), {
        onSuccess: () => { showEditModal.value = false; editForm.reset(); },
    });
};

const destroy = (id) => {
    if (confirm('Delete this review?')) {
        useForm({}).delete(route('reviews.destroy', id));
    }
};
</script>

<template>
    <Head title="AI Performance Reviews" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-white">AI Performance Reviews</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Generate professional reviews with AI</p>
                </div>
                <button @click="showModal = true"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 8px 20px rgba(99,102,241,0.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Generate Review
                </button>
            </div>
        </template>

        <!-- Generate Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showModal = false">
            <div class="w-full max-w-lg rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <!-- AI Badge -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">AI Review Generator</h3>
                        <p class="text-xs text-slate-500">Powered by GPT-4</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Select Employee</label>
                        <select v-model="form.employee_id" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="" disabled>Choose an employee...</option>
                            <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.user?.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Performance Notes</label>
                        <p class="text-xs text-slate-600 mb-2">Enter 3–5 bullet points about this employee's recent performance.</p>
                        <textarea v-model="form.bullet_points" rows="5" required
                            placeholder="- Exceeded Q1 sales targets by 20%&#10;- Strong team collaboration&#10;- Needs to improve documentation habits"
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none"
                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                    </div>

                    <!-- Info box -->
                    <div class="rounded-xl p-3.5 flex gap-3" style="background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2);">
                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-indigo-300">The AI will transform your notes into a professional, empathetic review document. This may take a few seconds.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-1">
                        <button type="button" @click="showModal = false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="form.processing"
                            class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition flex items-center gap-2 disabled:opacity-60"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <svg v-if="form.processing" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Generating...' : 'Generate Draft' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="showEditModal = false">
            <div class="w-full max-w-2xl rounded-2xl p-7 shadow-2xl" style="background: #0d1424; border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="text-lg font-bold text-white mb-5">Edit Review</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">AI Generated Review</label>
                        <textarea v-model="editForm.ai_generated_review" rows="8" required
                            class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none"
                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1.5">Status</label>
                        <select v-model="editForm.status" required class="w-full px-4 py-3 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                            <option value="pending">Pending</option>
                            <option value="generated">Generated</option>
                            <option value="finalized">Finalized</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-1">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition">Cancel</button>
                        <button type="submit" :disabled="editForm.processing"
                            class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Review Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div v-for="review in reviews" :key="review.id"
                class="rounded-2xl p-6 flex flex-col transition-all hover:border-indigo-500/30 cursor-pointer"
                style="background: #0d1424; border: 1px solid rgba(255,255,255,0.07);"
                @click="expandedReview = (expandedReview === review.id ? null : review.id)">

                <!-- Card Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            {{ review.employee?.user?.name?.charAt(0) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-white">{{ review.employee?.user?.name }}</div>
                            <div class="text-xs text-slate-500">by {{ review.reviewer?.name }}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase"
                        :style="`background: ${statusMap[review.status]?.bg}; color: ${statusMap[review.status]?.color};`">
                        {{ review.status }}
                    </span>
                </div>

                <!-- AI Generated Content -->
                <div v-if="review.ai_generated_review"
                    class="flex-1 rounded-xl p-4 text-sm leading-relaxed mb-4"
                    style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center gap-1.5 mb-2">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">AI Generated</span>
                    </div>
                    <p class="text-slate-300 line-clamp-4" :class="{'line-clamp-none': expandedReview === review.id}">
                        {{ review.ai_generated_review }}
                    </p>
                    <button class="mt-2 text-xs text-indigo-400 hover:text-indigo-300 transition">
                        {{ expandedReview === review.id ? 'Show less ↑' : 'Read full review ↓' }}
                    </button>
                </div>

                <!-- Original Notes (fallback) -->
                <div v-else class="flex-1 rounded-xl p-4 mb-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Original Notes</div>
                    <p class="text-slate-400 text-sm italic line-clamp-4">{{ review.bullet_points }}</p>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <span class="text-xs text-slate-600">{{ review.created_at }}</span>
                    <div class="flex items-center gap-3">
                        <button @click.stop="openEdit(review)" class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition">Edit</button>
                        <button @click.stop="destroy(review.id)" class="text-xs font-medium text-red-400 hover:text-red-300 transition">Delete</button>
                        <button class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition">Export PDF →</button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="reviews.length === 0" class="col-span-full rounded-2xl py-16 text-center" style="background: #0d1424; border: 1px dashed rgba(99,102,241,0.2);">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: rgba(99,102,241,0.1);">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-base">No reviews generated yet</p>
                        <p class="text-slate-500 text-sm mt-1">Click "Generate Review" to transform notes into professional HR documents using AI.</p>
                    </div>
                    <button @click="showModal = true" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        Generate First Review
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
