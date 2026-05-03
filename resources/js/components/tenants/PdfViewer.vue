<template>
    <el-dialog v-model="state" width="90%" top="4vh" class="pdf-viewer-dialog !rounded-lg" :close-on-click-modal="false"
        destroy-on-close>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">
                        Vista previa del documento
                    </h2>
                    <p class="text-xs text-gray-500">
                        Visualizador PDF
                    </p>
                </div>
            </div>
        </template>

        <div class="h-[80vh] overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
            <iframe v-if="url" :src="url" class="h-full w-full bg-white" frameborder="0" />

            <div v-else class="flex h-full items-center justify-center text-sm text-gray-500">
                No hay documento para visualizar.
            </div>
        </div>
    </el-dialog>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { usePdfViewerStore } from '../../stores/pdf-viewer'

const pdfViewerStore = usePdfViewerStore()
const { url, state } = storeToRefs(pdfViewerStore)
</script>

<style scoped>
:deep(.pdf-viewer-dialog) {
    border-radius: 18px;
    overflow: hidden;
}

:deep(.pdf-viewer-dialog .el-dialog__body) {
    padding: 12px 20px 20px;
}

:deep(.pdf-viewer-dialog .el-dialog__header) {
    padding: 18px 20px 10px;
    margin-right: 0;
}
</style>
