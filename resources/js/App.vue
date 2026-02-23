<script setup>
import { computed } from 'vue'
import CentralLayout from './layouts/CentralLayout .vue';
import TenantLayout from './layouts/TenantLayout.vue';
import { isCentralHost } from './tenancy/is-central';

const central = computed(() => isCentralHost())
</script>

<template>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <CentralLayout v-if="central" />
    <TenantLayout v-else />
</template>
