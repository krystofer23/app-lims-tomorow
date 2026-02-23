<template>
    <HeaderItem />
    <main class="max-w-7xl mx-auto p-5">
        <RouterView />
    </main>
</template>

<script setup>
import HeaderItem from '../components/tenants/HeaderItem.vue';
</script>