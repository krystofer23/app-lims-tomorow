<template>
    <HeaderItem />

    <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
        <div
            class="absolute -top-40 -right-40 h-[320px] w-[320px] rounded-full bg-gradient-to-br from-emerald-200/55 via-teal-200/35 to-sky-200/35 blur-3xl">
        </div>
        <div
            class="absolute -bottom-40 -left-40 h-[360px] w-[360px] rounded-full bg-gradient-to-tr from-indigo-200/35 via-fuchsia-200/25 to-rose-200/25 blur-3xl">
        </div>
    </div>

    <main class="max-w-7xl mx-auto p-5">
        <RouterView />
    </main>
</template>

<script setup>
import HeaderItem from '../components/tenants/HeaderItem.vue';
</script>