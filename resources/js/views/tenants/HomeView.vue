<template>
    <div class="relative">
        <header
            class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/70 backdrop-blur supports-[backdrop-filter]:bg-white/55 lg:hidden">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-sm">
                        <span class="text-sm font-black">GL</span>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold tracking-tight">Dashboard</p>
                        <p class="truncate text-xs text-slate-500">Resumen en tiempo real</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 bg-white shadow-sm hover:bg-slate-50">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-slate-700" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14 21a2 2 0 0 1-4 0m10-4H4a2 2 0 0 0 1.7-1.1c.5-.9 1.3-2.7 1.3-5.9a5 5 0 1 1 10 0c0 3.2.8 5 1.3 5.9A2 2 0 0 0 20 17Z" />
                        </svg>
                    </button>
                    <button
                        class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 bg-white shadow-sm hover:bg-slate-50">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-slate-700" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-7xl px-4 lg:px-6 py-6 lg:py-8">
            <main class="space-y-6 pb-20 lg:pb-0">
                <section
                    class="rounded-3xl border border-slate-200 bg-white/80 backdrop-blur shadow-sm overflow-hidden">
                    <div class="relative p-6 sm:p-8">
                        <div aria-hidden="true"
                            class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-sky-500/10">
                        </div>
                        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div
                                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-900">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Bienvenido de vuelta
                                </div>
                                <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                                    Tu panel está listo para hoy
                                </h1>
                                <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                                    Visualiza métricas clave, prioriza pendientes y ejecuta acciones rápidas sin perder
                                    tiempo.
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-700">
                                        <span class="mr-2 h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Última actualización: hace 2 min
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-700">
                                        <span class="mr-2 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                        Estado: estable
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                                <button
                                    class="w-full lg:w-auto rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                                    Crear nuevo
                                </button>
                                <button
                                    class="w-full lg:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                                    Ver reportes
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <article class="rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Pendientes</p>
                                <p class="mt-2 text-2xl font-black tracking-tight">128</p>
                                <p class="mt-1 text-xs text-slate-500">+12 vs ayer</p>
                            </div>
                            <span
                                class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 h-2 w-full rounded-full bg-slate-200">
                            <div class="h-2 w-[68%] rounded-full bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Atendidos</p>
                                <p class="mt-2 text-2xl font-black tracking-tight">94</p>
                                <p class="mt-1 text-xs text-slate-500">+7 hoy</p>
                            </div>
                            <span
                                class="grid h-11 w-11 place-items-center rounded-2xl bg-sky-50 border border-sky-200 text-sky-700">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 h-2 w-full rounded-full bg-slate-200">
                            <div class="h-2 w-[54%] rounded-full bg-gradient-to-r from-sky-500 to-indigo-500"></div>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Alertas</p>
                                <p class="mt-2 text-2xl font-black tracking-tight">6</p>
                                <p class="mt-1 text-xs text-slate-500">-2 vs ayer</p>
                            </div>
                            <span
                                class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-50 border border-amber-200 text-amber-700">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v4m0 4h.01M10.3 4.3l-7.2 12.5A2 2 0 0 0 4.8 20h14.4a2 2 0 0 0 1.7-3.2L13.7 4.3a2 2 0 0 0-3.4 0Z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-[11px] font-semibold text-amber-800 border border-amber-200">
                                Revisión
                            </span>
                            <span class="text-xs text-slate-500">2 críticas</span>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Rendimiento</p>
                                <p class="mt-2 text-2xl font-black tracking-tight">98.2%</p>
                                <p class="mt-1 text-xs text-slate-500">SLA semanal</p>
                            </div>
                            <span
                                class="grid h-11 w-11 place-items-center rounded-2xl bg-fuchsia-50 border border-fuchsia-200 text-fuchsia-700">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 2v4m0 16v-4M4.9 4.9l2.8 2.8m8.6 8.6 2.8 2.8M2 12h4m16 0h-4M4.9 19.1l2.8-2.8m8.6-8.6 2.8-2.8" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 h-2 w-full rounded-full bg-slate-200">
                            <div class="h-2 w-[82%] rounded-full bg-gradient-to-r from-fuchsia-500 to-rose-500"></div>
                        </div>
                    </article>
                </section>

                <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                    <article
                        class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm">
                        <div class="p-5 sm:p-6 flex items-start justify-between gap-3">
                            <div>
                                <h2 class="text-sm font-black tracking-tight">Actividad reciente</h2>
                                <p class="mt-1 text-xs text-slate-500">Lo último que pasó en tu sistema</p>
                            </div>
                            <button
                                class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                Ver todo
                            </button>
                        </div>

                        <div class="px-5 sm:px-6 pb-5 sm:pb-6">
                            <div class="space-y-3">
                                <div class="flex gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                                    <div
                                        class="mt-0.5 h-9 w-9 rounded-2xl bg-emerald-50 border border-emerald-200 grid place-items-center text-emerald-700">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900 truncate">Se completó una tarea
                                            programada</p>
                                        <p class="mt-1 text-xs text-slate-500">Proceso finalizado sin errores • hace 8
                                            min
                                        </p>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold text-slate-600 rounded-full border border-slate-200 bg-slate-50 px-2 py-1 h-fit">
                                        OK
                                    </span>
                                </div>

                                <div class="flex gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                                    <div
                                        class="mt-0.5 h-9 w-9 rounded-2xl bg-amber-50 border border-amber-200 grid place-items-center text-amber-700">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v4m0 4h.01M10.3 4.3l-7.2 12.5A2 2 0 0 0 4.8 20h14.4a2 2 0 0 0 1.7-3.2L13.7 4.3a2 2 0 0 0-3.4 0Z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900 truncate">Hay alertas pendientes
                                            de
                                            revisión</p>
                                        <p class="mt-1 text-xs text-slate-500">2 críticas • 4 moderadas • hace 25 min
                                        </p>
                                    </div>
                                    <button
                                        class="text-xs font-semibold text-amber-800 rounded-2xl bg-amber-50 border border-amber-200 px-3 py-2 hover:bg-amber-100">
                                        Revisar
                                    </button>
                                </div>

                                <div class="flex gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                                    <div
                                        class="mt-0.5 h-9 w-9 rounded-2xl bg-sky-50 border border-sky-200 grid place-items-center text-sky-700">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 19V5m0 14h16M8 16v-6m4 6V8m4 8v-4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900 truncate">Se actualizó un
                                            indicador
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">Rendimiento semanal recalculado • hace 1
                                            h
                                        </p>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold text-sky-700 rounded-full border border-sky-200 bg-sky-50 px-2 py-1 h-fit">
                                        INFO
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white/85 backdrop-blur shadow-sm">
                        <div class="p-5 sm:p-6">
                            <h2 class="text-sm font-black tracking-tight">Prioridades</h2>
                            <p class="mt-1 text-xs text-slate-500">Lo que no se puede pasar hoy</p>

                            <div class="mt-4 space-y-3">
                                <label
                                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold truncate">Revisar pendientes críticos</p>
                                        <p class="text-xs text-slate-500">Antes de mediodía</p>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200 px-2 py-1">
                                        Urgente
                                    </span>
                                </label>

                                <label
                                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold truncate">Consolidar reporte diario</p>
                                        <p class="text-xs text-slate-500">Exportar y compartir</p>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold rounded-full bg-slate-50 text-slate-700 border border-slate-200 px-2 py-1">
                                        Hoy
                                    </span>
                                </label>

                                <label
                                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold truncate">Validar integridad de datos</p>
                                        <p class="text-xs text-slate-500">Muestreo / registros</p>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1">
                                        OK
                                    </span>
                                </label>

                                <button
                                    class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                                    Abrir tablero de tareas
                                </button>
                            </div>
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>
</template>

<script setup></script>

<style>
.title-header {
    font-weight: 500 !important;
}
</style>