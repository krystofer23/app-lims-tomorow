<template>
    <div class="">
        <HeaderItem class="fixed left-0" />

        <div class="ms-[320px]">
            <main class="w-full">
                <RouterView />
            </main>
        </div>
    </div>

    <pdf-viewer />
</template>

<script setup>
import HeaderItem from '../components/tenants/HeaderItem.vue';
import PdfViewer from '../components/tenants/PdfViewer.vue';
</script>
