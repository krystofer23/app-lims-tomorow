<template>
    <div class="fixed left-0 right-0 top-0 z-[100] flex h-[100dvh] w-full items-center justify-center bg-slate-50 px-4">
        <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl shadow-slate-200/70">
            <div class="mb-8">
                <div
                    class="mb-4 inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                    GreenLab
                </div>

                <h2 class="text-3xl font-black text-slate-900">Iniciar sesión</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Accede a tu plataforma y sigue gestionando todo con una experiencia moderna, clara y profesional.
                </p>
            </div>

            <form class="space-y-5" @submit.prevent="onSubmit">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Rol
                    </label>

                    <div
                        class="group flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 transition duration-300 focus-within:border-emerald-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400 transition group-focus-within:text-emerald-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2m12 0H7m10-10a4 4 0 11-8 0a4 4 0 018 0z" />
                        </svg>

                        <select v-model="form.role"
                            class="w-full appearance-none bg-transparent px-3 py-4 text-sm text-slate-800 focus:outline-none">
                            <option disabled value="">Selecciona un rol</option>
                            <option value="admin">Super Admin</option>
                        </select>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Correo
                    </label>

                    <div
                        class="group flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 transition duration-300 focus-within:border-emerald-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400 transition group-focus-within:text-emerald-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 12H8m8 0a4 4 0 10-8 0m8 0v6a2 2 0 01-2 2H10a2 2 0 01-2-2v-6" />
                        </svg>

                        <input v-model="form.email" type="email" placeholder="nombre@empresa.com"
                            class="w-full bg-transparent px-3 py-4 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="block text-sm font-medium text-slate-700">
                            Contraseña
                        </label>

                        <a href="#" class="text-sm font-medium text-emerald-600 transition hover:text-emerald-500">
                            ¿La olvidaste?
                        </a>
                    </div>

                    <div
                        class="group flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 transition duration-300 focus-within:border-emerald-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400 transition group-focus-within:text-emerald-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2zm3-10V9a3 3 0 016 0v2H9z" />
                        </svg>

                        <input v-model="form.password" type="password" placeholder="Ingresa tu contraseña"
                            class="w-full bg-transparent px-3 py-4 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400" />
                        Recordarme
                    </label>

                    <div
                        class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                        Protegido
                    </div>
                </div>

                <button @click.prevent="onSubmit"
                    class="group relative w-full overflow-hidden rounded-2xl bg-emerald-400 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition duration-300 hover:scale-[1.01] hover:bg-emerald-500 hover:shadow-xl">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Ingresar
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition group-hover:translate-x-1"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>

                    <span
                        class="absolute inset-0 -translate-x-full bg-white/20 transition duration-500 group-hover:translate-x-0"></span>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500">
                ¿Aún no tienes cuenta?
                <a href="#" class="font-semibold text-emerald-600 transition hover:text-emerald-500">
                    Solicitar acceso
                </a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '../../stores/auth';
import { ElNotification } from 'element-plus';
import { reactive } from 'vue'

const authStore = useAuthStore()

const form = reactive({
    role: null,
    email: null,
    password: null,
})

const onSubmit = async () => {
    if (!form.email) {
        ElNotification.error('Ingrese su correo electrónico');
    }
    if (!form.password) {
        ElNotification.error('Ingrese su contraseña');
    }

    if (form.email && form.password) {
        authStore.login(form.email, form.password, form.role);
    }
}
</script>