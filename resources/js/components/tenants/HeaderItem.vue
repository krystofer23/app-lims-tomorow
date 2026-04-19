<template>
    <div class="flex min-h-screen bg-slate-50">
        <aside ref="headerRef"
            class="hidden md:flex md:w-80 lg:w-84 xl:w-84 shrink-0 flex-col border-r border-slate-200 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/70 sticky top-0 h-screen z-40">
            <div class="flex h-full flex-col">
                <div class="border-b border-slate-200 px-4 py-4">
                    <a href="#" class="group flex items-center gap-3 min-w-0">
                        <span
                            class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-400 text-white shadow-sm font-normal">
                            <i class="fa-solid fa-flask-vial"></i>
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="truncate text-sm font-semibold tracking-tight text-slate-900">GreenLab</span>
                                <span
                                    class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                                    Pro
                                </span>
                            </div>
                            <p class="truncate text-[12px] text-slate-500">Sistema LIMS</p>
                        </div>
                    </a>

                    <div class="mt-4">
                        <label class="relative block">
                            <span class="sr-only">Buscar</span>
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </span>

                            <input v-model="q" ref="searchRef" type="search"
                                placeholder="Buscar recursos, cursos, guías..."
                                class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-11 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-emerald-300 focus:ring-4 focus:ring-emerald-100"
                                @keydown.ctrl.k.prevent="focusSearch" @keydown.meta.k.prevent="focusSearch" />

                            <span
                                class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 hidden rounded-xl border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-500 lg:inline">
                                Ctrl K
                            </span>
                        </label>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-2">
                    <a href="/"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                        <i class="fa-solid fa-house"></i>
                        Inicio
                    </a>

                    <div v-for="menu in menus" :key="menu.key"
                        class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                        <button v-if="menu.items?.length" type="button"
                            class="flex w-full items-center justify-between gap-2 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 active:scale-[.98] transition"
                            :aria-expanded="openTop === menu.key ? 'true' : 'false'" @click="toggleTop(menu.key)">
                            <span class="flex items-center gap-3">
                                <i :class="menu.icon"></i>
                                {{ menu.label }}
                            </span>

                            <svg class="h-4 w-4 text-slate-500 transition"
                                :class="openTop === menu.key ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                aria-hidden="true">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>

                        <Transition name="accordion">
                            <div v-if="menu.items?.length && openTop === menu.key" class="px-2 pb-2">
                                <div v-for="item in menu.items" :key="item.key" class="relative">
                                    <button type="button"
                                        class="w-full text-left group rounded-2xl border border-transparent bg-white p-3 hover:bg-slate-50 transition"
                                        @click="item.children?.length ? toggleSub(item.key) : go(item)">
                                        <div class="flex items-start gap-3">
                                            <span class="grid h-10 w-10 place-items-center rounded-2xl"
                                                :class="item.iconBg">
                                                <i :class="item.icon"></i>
                                            </span>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <p class="text-sm font-medium text-slate-900 truncate">
                                                        {{ item.title }}
                                                    </p>

                                                    <svg v-if="item.children?.length"
                                                        class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition"
                                                        :class="openSub === item.key ? 'rotate-180' : ''"
                                                        viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" />
                                                    </svg>
                                                </div>

                                                <p class="text-xs text-slate-600">{{ item.desc }}</p>

                                                <p v-if="item.cta" class="mt-2 text-xs font-semibold"
                                                    :class="item.ctaColor">
                                                    {{ item.cta }}
                                                </p>
                                            </div>
                                        </div>
                                    </button>

                                    <Transition name="accordion">
                                        <div v-if="item.children?.length && openSub === item.key"
                                            class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3 pb-2">
                                            <p
                                                class="px-3 pt-1 pb-1 text-[11px] font-medium uppercase tracking-wide text-slate-500">
                                                {{ item.subtitle || "Opciones" }}
                                            </p>

                                            <a v-for="child in item.children" :key="child.key" href="#"
                                                class="flex items-start gap-3 rounded-2xl px-3 py-3 hover:bg-slate-50 transition"
                                                @click.prevent="go(child)">
                                                <span class="mt-0.5 grid h-9 w-9 place-items-center rounded-2xl"
                                                    :class="child.iconBg">
                                                    <i :class="child.icon"></i>
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-900 truncate">
                                                        {{ child.title }}
                                                    </p>
                                                    <p class="text-xs text-slate-600">{{ child.desc }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    </Transition>
                                </div>

                                <!-- <div v-if="menu.footerText"
                                    class="mt-2 flex items-center justify-between rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-600">{{ menu.footerText }}</p>
                                    <a href="#" class="text-xs font-bold text-slate-900 hover:underline"
                                        @click.prevent="closeAll">
                                        Ver todo
                                    </a>
                                </div> -->
                            </div>
                        </Transition>
                    </div>
                </nav>

                <!-- Bottom -->
                <div class="border-t border-slate-200 p-4 sm:px-6">
                    <div class="flex items-center justify-between gap-2">
                        <button
                            class="inline-flex h-10 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 active:scale-[.98] transition">
                            <i class="fa-solid fa-bell"></i>
                        </button>

                        <div class="relative flex-1">
                            <button
                                class="w-full inline-flex h-10 items-center justify-between gap-2 rounded-2xl border border-slate-200 bg-white px-2 pr-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 active:scale-[.98] transition"
                                :aria-expanded="openTop === 'profile' ? 'true' : 'false'" @click="toggleTop('profile')">
                                <span class="flex items-center gap-2">
                                    <span
                                        class="grid h-9 w-9 place-items-center rounded-2xl bg-slate-900 text-white text-xs font-black">
                                        K
                                    </span>
                                    <span class="truncate">Mi cuenta</span>
                                </span>

                                <svg class="h-4 w-4 text-slate-500 transition"
                                    :class="openTop === 'profile' ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                    aria-hidden="true">
                                    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>

                            <Transition name="menu-pop">
                                <div v-if="openTop === 'profile'" class="absolute bottom-full left-0 z-50 mb-2 w-full">
                                    <div class="rounded-3xl border border-slate-200 bg-white p-2 shadow-xl">
                                        <a href="#" class="block rounded-2xl px-3 py-3 hover:bg-slate-50"
                                            @click.prevent="closeAll">
                                            <p class="text-sm font-medium text-slate-900">Perfil</p>
                                            <p class="text-xs text-slate-600">Datos y preferencias.</p>
                                        </a>
                                        <a href="#" class="block rounded-2xl px-3 py-3 hover:bg-slate-50"
                                            @click.prevent="closeAll">
                                            <p class="text-sm font-medium text-slate-900">Configuración</p>
                                            <p class="text-xs text-slate-600">Permisos, seguridad, accesos.</p>
                                        </a>
                                        <div class="my-2 h-px bg-slate-200"></div>
                                        <a href="#" class="block rounded-2xl px-3 py-3 hover:bg-red-50"
                                            @click.prevent="closeAll">
                                            <p class="text-sm font-medium text-red-600">Cerrar sesión</p>
                                            <p class="text-xs text-red-500">Salir de tu cuenta.</p>
                                        </a>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="min-w-0 flex-1 flex flex-col">
            <!-- Mobile topbar -->
            <header
                class="md:hidden sticky top-0 z-50 border-b border-slate-200 bg-white/85 backdrop-blur supports-[backdrop-filter]:bg-white/70">
                <div class="px-4">
                    <div class="flex h-16 items-center justify-between gap-3">
                        <a href="#" class="group flex items-center gap-3 min-w-0">
                            <span
                                class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-400 text-white shadow-sm font-normal">
                                <i class="fa-solid fa-flask-vial"></i>
                            </span>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="truncate text-sm font-semibold tracking-tight text-slate-900">GreenLab</span>
                                </div>
                                <p class="truncate text-[12px] text-slate-500">Sistema LIMS</p>
                            </div>
                        </a>

                        <div class="flex items-center gap-2">
                            <button
                                class="inline-flex h-10 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 active:scale-[.98] transition">
                                <i class="fa-solid fa-bell"></i>
                            </button>

                            <button type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 active:scale-[.98] transition"
                                :aria-expanded="mobileOpen ? 'true' : 'false'" aria-controls="mobile-menu"
                                @click="toggleMobile">
                                <span class="sr-only">Abrir menú</span>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Tu contenido -->
            <main class="flex-1">
                <slot />
            </main>
        </div>

        <!-- MOBILE DRAWER -->
        <Transition name="mobile-drop">
            <div v-if="mobileOpen" class="fixed inset-0 z-[60] md:hidden">
                <div class="absolute inset-0 bg-slate-900/40" @click="closeAllAndMobile"></div>

                <aside id="mobile-menu"
                    class="absolute left-0 top-0 h-full w-[88%] max-w-sm border-r border-slate-200 bg-white shadow-2xl">
                    <div class="flex h-full flex-col">
                        <div class="border-b border-slate-200 px-4 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <a href="#" class="group flex items-center gap-3 min-w-0">
                                    <span
                                        class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-400 text-white shadow-sm font-normal">
                                        <i class="fa-solid fa-flask-vial"></i>
                                    </span>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="truncate text-sm font-semibold tracking-tight text-slate-900">GreenLab</span>
                                        </div>
                                        <p class="truncate text-[12px] text-slate-500">Sistema LIMS</p>
                                    </div>
                                </a>

                                <button
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50"
                                    @click="closeAllAndMobile">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div class="pt-4">
                                <label class="relative block">
                                    <span class="sr-only">Buscar</span>
                                    <span
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
                                        </svg>
                                    </span>

                                    <input v-model="q" type="search" placeholder="Buscar..."
                                        class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-11 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-emerald-300 focus:ring-4 focus:ring-emerald-100" />
                                </label>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2">
                            <a href="#"
                                class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-900 bg-slate-100"
                                @click.prevent="closeAllAndMobile">
                                <i class="fa-solid fa-house"></i>
                                Inicio
                            </a>

                            <div v-for="menu in menus" :key="menu.key"
                                class="rounded-2xl border border-slate-200 bg-white">
                                <button
                                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-extrabold text-slate-900"
                                    :aria-expanded="mobileAcc[menu.key] ? 'true' : 'false'"
                                    @click="mobileAcc[menu.key] = !mobileAcc[menu.key]">
                                    {{ menu.label }}
                                    <svg class="h-4 w-4 text-slate-500 transition"
                                        :class="mobileAcc[menu.key] ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                        aria-hidden="true">
                                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>

                                <Transition name="accordion">
                                    <div v-if="mobileAcc[menu.key]" class="px-2 pb-2">
                                        <div v-for="item in menu.items" :key="item.key" class="rounded-2xl">
                                            <button
                                                class="w-full flex items-center justify-between rounded-2xl px-3 py-3 text-left text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                                                @click="toggleMobileSub(menu.key, item.key)">
                                                <span class="flex items-center gap-2">
                                                    <span class="grid h-9 w-9 place-items-center rounded-2xl"
                                                        :class="item.iconBg">
                                                        <i :class="item.icon"></i>
                                                    </span>
                                                    {{ item.title }}
                                                </span>

                                                <svg v-if="item.children?.length"
                                                    class="h-4 w-4 text-slate-400 transition"
                                                    :class="isMobileSubOpen(menu.key, item.key) ? 'rotate-180' : ''"
                                                    viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>

                                                <span v-else class="text-slate-300">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </span>
                                            </button>

                                            <Transition name="accordion">
                                                <div v-if="item.children?.length && isMobileSubOpen(menu.key, item.key)"
                                                    class="pl-3 pr-1 pb-2">
                                                    <a v-for="child in item.children" :key="child.key" href="#"
                                                        class="block rounded-2xl px-3 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                                                        @click.prevent="go(child, true)">
                                                        <span class="flex items-start gap-3">
                                                            <span
                                                                class="mt-0.5 grid h-9 w-9 place-items-center rounded-2xl"
                                                                :class="child.iconBg">
                                                                <i :class="child.icon"></i>
                                                            </span>
                                                            <span class="min-w-0">
                                                                <span
                                                                    class="block font-extrabold text-slate-900 truncate">
                                                                    {{ child.title }}
                                                                </span>
                                                                <span class="block text-xs text-slate-600">
                                                                    {{ child.desc }}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </Transition>
                                        </div>
                                    </div>
                                </Transition>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";

const q = ref("");
const searchRef = ref(null);

const headerRef = ref(null);

const mobileOpen = ref(false);
const openTop = ref(null);
const openSub = ref(null);

const mobileAcc = reactive({});

const mobileSubs = reactive({});

function focusSearch() {
    searchRef.value?.focus?.();
}

function toggleMobile() {
    mobileOpen.value = !mobileOpen.value;
    if (!mobileOpen.value) closeAll();
}

function toggleTop(key) {
    if (openTop.value === key) {
        closeAll();
        return;
    }

    openTop.value = key;
    openSub.value = null;
}

function toggleSub(key) {
    openSub.value = openSub.value === key ? null : key;
}

function closeAll() {
    openTop.value = null;
    openSub.value = null;
}

function closeAllAndMobile() {
    closeAll();
    mobileOpen.value = false;
}

function isMobileSubOpen(menuKey, itemKey) {
    return !!mobileSubs?.[menuKey]?.[itemKey];
}

function toggleMobileSub(menuKey, itemKey) {
    const menu = menus.value.find((m) => m.key === menuKey);
    const item = menu?.items?.find((i) => i.key === itemKey);

    if (!item?.children?.length) {
        go(item, true);
        return;
    }

    mobileSubs[menuKey] ??= {};
    mobileSubs[menuKey][itemKey] = !mobileSubs[menuKey][itemKey];
}

const router = useRouter()

function go(item, closeMobile = false) {
    router.push({ name: item?.key })

    closeAll();
    if (closeMobile) mobileOpen.value = false;
}

function onKeydown(e) {
    if (e.key === "Escape") closeAllAndMobile();
}

function onPointerDown(e) {
    const el = headerRef.value;
    if (!el) return;
    const target = e.target;
    if (target && !el.contains(target)) {
        closeAll();
    }
}

onMounted(() => {
    window.addEventListener("keydown", onKeydown);
    window.addEventListener("pointerdown", onPointerDown, { capture: true });
});

onBeforeUnmount(() => {
    window.removeEventListener("keydown", onKeydown);
    window.removeEventListener("pointerdown", onPointerDown, { capture: true });
});

const menus = computed(() => {
    const data = [
        {
            key: "admision",
            label: "Admisión",
            icon: "fa-solid fa-laptop-medical",
            variant: "mega",
            footerText: "Accede rápido a los módulos principales de admisión.",
            items: [
                {
                    key: "quotes",
                    title: "Cotizaciones",
                    desc: "Módulo de cotizaciones",
                    cta: "Ir →",
                    ctaColor: "text-emerald-700",
                    iconBg: "bg-emerald-50 text-emerald-700",
                    icon: "fa-solid fa-file-lines",
                    subtitle: "Cotizaciones",
                },
                {
                    key: "orders-services",
                    title: "Órdenes de Servicio",
                    desc: "Módulo de OS",
                    cta: "Ir →",
                    ctaColor: "text-emerald-700",
                    iconBg: "bg-blue-50 text-blue-700",
                    icon: "fa-solid fa-file-export",
                    subtitle: "Órdenes de Servicio",
                },
                {
                    key: "reception",
                    title: "Recepción",
                    desc: "Módulo de recepción",
                    cta: "Ir →",
                    ctaColor: "text-orange-700",
                    iconBg: "bg-orange-50 text-orange-700",
                    icon: "fa-solid fa-satellite-dish",
                    subtitle: "Recepción",
                },
                {
                    key: "information",
                    title: "Informes",
                    desc: "Módulo de informes",
                    cta: "Ir →",
                    ctaColor: "text-red-700",
                    iconBg: "bg-red-50 text-red-700",
                    icon: "fa-solid fa-satellite-dish",
                    subtitle: "Informes",
                },
                {
                    key: "companies",
                    title: "Empresas",
                    desc: "Módulo de empresas",
                    cta: "Ir →",
                    ctaColor: "text-indigo-700",
                    iconBg: "bg-indigo-50 text-indigo-700",
                    icon: "fa-solid fa-building",
                    subtitle: "Empresas",
                },
            ],
        },
        {
            key: "settings",
            label: "Configuración",
            icon: "fa-solid fa-gear",
            variant: "mega",
            footerText: "Configura usuarios, roles, y catálogos del sistema.",
            items: [
                {
                    key: "professionals",
                    title: "Profesionales",
                    desc: "Módulo de profesionales",
                    cta: "Ir →",
                    ctaColor: "text-blue-700",
                    iconBg: "bg-blue-50 text-blue-700",
                    icon: "fa-solid fa-user-graduate",
                    subtitle: "Profesionales",
                },
                {
                    key: "users",
                    title: "Usuarios",
                    desc: "Módulo de usuarios",
                    cta: "Ir →",
                    ctaColor: "text-emerald-700",
                    iconBg: "bg-emerald-50 text-emerald-700",
                    icon: "fa-solid fa-user",
                    subtitle: "Usuarios",
                },
                {
                    key: "laboratory",
                    title: "Laboratorio",
                    desc: "Módulo de Laboratorio",
                    cta: "Ver Más →",
                    ctaColor: "text-amber-700",
                    iconBg: "bg-amber-50 text-amber-700",
                    icon: "fa-solid fa-microscope",
                    subtitle: "Laboratorio",
                    children: [
                        {
                            key: "laboratory-analysis",
                            title: "Analisis de Laboratorio",
                            desc: "Modulo de analisis de laboratorio",
                            iconBg: "bg-purple-50 text-purple-700",
                            icon: "fa-solid fa-flask",
                        },
                        {
                            key: "matriz",
                            title: "Matriz",
                            desc: "Modulo de matrices",
                            iconBg: "bg-orange-50 text-orange-700",
                            icon: "fa-solid fa-qrcode",
                        },
                        {
                            key: "essays",
                            title: "Ensayos",
                            desc: "Modulo de unidad de medida",
                            iconBg: "bg-blue-50 text-blue-700",
                            icon: "fa-solid fa-file-half-dashed",
                        },
                        {
                            key: "methodologies",
                            title: "Metodologias",
                            desc: "Modulo de metodologias",
                            iconBg: "bg-red-50 text-red-700",
                            icon: "fa-solid fa-note-sticky",
                        },
                        {
                            key: "units-measurement",
                            title: "Unidades de medida",
                            desc: "Modulo de unidad de medida",
                            iconBg: "bg-sky-50 text-sky-700",
                            icon: "fa-solid fa-ruler-combined",
                        },
                        {
                            key: "conditions",
                            title: "Condiciones",
                            desc: "Modulo de condiciones",
                            iconBg: "bg-indigo-50 text-indigo-700",
                            icon: "fa-solid fa-compress",
                        },
                    ],
                },
                {
                    key: "data-master",
                    title: "Datos Maestros",
                    desc: "Módulo de datos maestros",
                    cta: "Ver Más →",
                    ctaColor: "text-indigo-700",
                    iconBg: "bg-indigo-50 text-indigo-700",
                    icon: "fa-solid fa-database",
                    subtitle: "Datos Maestros",
                    children: [
                        {
                            key: "establishments",
                            title: "Establecimientos",
                            desc: "Modulo de establecimientos",
                            iconBg: "bg-orange-50 text-orange-700",
                            icon: "fa-solid fa-sign-hanging",
                        },
                        {
                            key: "environments",
                            title: "Ambientes",
                            desc: "Modulo de ambientes",
                            iconBg: "bg-sky-50 text-sky-700",
                            icon: "fa-solid fa-solar-panel",
                        },
                        {
                            key: "services",
                            title: "Servicios",
                            desc: "Modulo de servicios",
                            iconBg: "bg-red-50 text-red-700",
                            icon: "fa-solid fa-clipboard-list",
                        },
                        {
                            key: "logistic-cats",
                            title: "Gastos Logisticos",
                            desc: "Modulo de gastos logisticos",
                            iconBg: "bg-green-50 text-green-700",
                            icon: "fa-solid fa-coins",
                        },
                    ],
                },
            ],
        },
    ];

    // Inicializa keys del mobileAcc dinámicamente para no olvidarte
    for (const m of data) {
        if (mobileAcc[m.key] === undefined) mobileAcc[m.key] = false;
        if (mobileSubs[m.key] === undefined) mobileSubs[m.key] = {};
    }

    return data;
});
</script>

<style scoped>
.menu-pop-enter-active,
.menu-pop-leave-active {
    transition: opacity 160ms ease, transform 160ms ease;
}

.menu-pop-enter-from,
.menu-pop-leave-to {
    opacity: 0;
    transform: translateY(6px) scale(0.98);
}

.submenu-pop-enter-active,
.submenu-pop-leave-active {
    transition: opacity 140ms ease, transform 140ms ease;
}

.submenu-pop-enter-from,
.submenu-pop-leave-to {
    opacity: 0;
    transform: translateX(8px) scale(0.98);
}

.mobile-drop-enter-active,
.mobile-drop-leave-active {
    transition: opacity 180ms ease, transform 180ms ease;
}

.mobile-drop-enter-from,
.mobile-drop-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}

.accordion-enter-active,
.accordion-leave-active {
    transition: max-height 200ms ease, opacity 200ms ease;
}

.accordion-enter-from,
.accordion-leave-to {
    max-height: 0;
    opacity: 0;
}

.accordion-enter-to,
.accordion-leave-from {
    max-height: 900px;
    opacity: 1;
}
</style>