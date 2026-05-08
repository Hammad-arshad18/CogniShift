<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Sign In — HRMS" />

    <div class="min-h-screen flex font-sans" style="background: #0a0f1e;">

        <!-- Left Panel: Branding & Illustration -->
        <div class="hidden lg:flex lg:w-1/2 relative flex-col items-center justify-center p-12 overflow-hidden"
            style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
            <!-- Glowing circles background decoration -->
            <div class="absolute top-[-80px] left-[-80px] w-96 h-96 rounded-full opacity-20"
                style="background: radial-gradient(circle, #818cf8, transparent);"></div>
            <div class="absolute bottom-[-60px] right-[-60px] w-80 h-80 rounded-full opacity-15"
                style="background: radial-gradient(circle, #c084fc, transparent);"></div>
            <div class="absolute top-1/2 left-1/4 w-48 h-48 rounded-full opacity-10"
                style="background: radial-gradient(circle, #38bdf8, transparent);"></div>

            <!-- Brand -->
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center gap-3 mb-12">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-2xl"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white tracking-tight">NexusHR</span>
                </div>

                <!-- Stats cards -->
                <div class="grid grid-cols-2 gap-4 mb-10 text-left">
                    <div class="rounded-2xl p-5 border"
                        style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                        <div class="text-3xl font-bold text-white mb-1">12K+</div>
                        <div class="text-sm text-indigo-300">Employees Managed</div>
                    </div>
                    <div class="rounded-2xl p-5 border"
                        style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                        <div class="text-3xl font-bold text-white mb-1">98%</div>
                        <div class="text-sm text-indigo-300">Payroll Accuracy</div>
                    </div>
                    <div class="rounded-2xl p-5 border"
                        style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                        <div class="text-3xl font-bold text-white mb-1">340+</div>
                        <div class="text-sm text-indigo-300">Organizations</div>
                    </div>
                    <div class="rounded-2xl p-5 border"
                        style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                        <div class="text-3xl font-bold text-white mb-1">AI</div>
                        <div class="text-sm text-indigo-300">Powered Reviews</div>
                    </div>
                </div>

                <h1 class="text-4xl font-extrabold text-white leading-tight mb-4">
                    The Future of<br><span
                        style="background: linear-gradient(90deg, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">HR
                        Management</span>
                </h1>
                <p class="text-indigo-200 text-base leading-relaxed max-w-sm mx-auto">
                    Unify attendance, payroll, performance reviews, and AI analytics into one beautiful platform.
                </p>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="flex flex-1 items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="flex items-center gap-3 mb-10 lg:hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">NexusHR</span>
                </div>

                <h2 class="text-3xl font-bold text-white mb-2">Welcome back</h2>
                <p class="text-slate-400 mb-8">Sign in to your workspace</p>

                <div v-if="status" class="mb-6 text-sm font-medium text-emerald-400 rounded-xl p-4"
                    style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" type="email" v-model="form.email" required autofocus
                                autocomplete="username" placeholder="admin@example.com"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 transition-all"
                                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); focus-ring-color: #6366f1;"
                                :class="{ 'border-red-500': form.errors.email }" />
                        </div>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" type="password" v-model="form.password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 transition-all"
                                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);"
                                :class="{ 'border-red-500': form.errors.password }" />
                        </div>
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">{{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <Checkbox name="remember" v-model:checked="form.remember" class="rounded" />
                            <span class="text-sm text-slate-400">Remember me</span>
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Submit -->
                    <button type="submit" :disabled="form.processing"
                        class="w-full py-3.5 px-6 rounded-xl text-sm font-semibold text-white transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2 shadow-xl"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 10px 25px rgba(99,102,241,0.35);">
                        <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 text-white" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        {{ form.processing ? 'Signing in...' : 'Sign in to workspace' }}
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-slate-600">
                    © {{ new Date().getFullYear() }} NexusHR. Enterprise HR Platform.
                </p>
            </div>
        </div>
    </div>
</template>
