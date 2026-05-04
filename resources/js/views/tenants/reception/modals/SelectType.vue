<template>
    <el-dialog :model-value="props.visible" @close="emits('close')" modal :show-close="!loading"
        :close-on-click-modal="!loading" :close-on-press-escape="!loading"
        :style="{ width: '430px', borderRadius: '22px' }" class="download-ot-dialog">
        <template #header>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-file-arrow-down text-lg"></i>
                </div>

                <div>
                    <h2 class="m-0 text-base font-bold text-slate-900">
                        Descargar Orden de Trabajo
                    </h2>
                    <p class="m-0 mt-0.5 text-xs text-slate-500">
                        Genera o descarga el archivo según el formato requerido.
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-5">
            <div v-if="loading" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                        <i class="fa-solid fa-spinner animate-spin"></i>
                    </div>

                    <div>
                        <p class="m-0 text-sm font-semibold text-slate-800">
                            Preparando orden de trabajo
                        </p>
                        <p class="m-0 mt-0.5 text-xs text-slate-500">
                            Estamos generando la información necesaria para la descarga.
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="m-0 text-sm font-semibold text-slate-800">
                    Selecciona el formato
                </p>

                <p class="m-0 mt-1 text-xs leading-5 text-slate-500">
                    Puedes visualizar la orden en PDF o descargarla como archivo Excel.
                </p>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button type="button" :disabled="!chainId || loadingPdf || loadingExcel"
                        @click="downloadFile('pdf')"
                        class="group rounded-2xl border border-red-100 bg-red-50 px-4 py-4 text-left transition hover:border-red-200 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-60">
                        <div class="flex items-center justify-between gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-red-500 shadow-sm">
                                <i v-if="loadingPdf" class="fa-solid fa-spinner animate-spin"></i>
                                <i v-else class="fa-regular fa-file-pdf"></i>
                            </div>

                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-red-400"></i>
                        </div>

                        <p class="m-0 mt-3 text-sm font-bold text-red-600">
                            PDF
                        </p>
                        <p class="m-0 mt-0.5 text-xs text-red-500/80">
                            Visualizar documento
                        </p>
                    </button>

                    <button type="button" :disabled="!chainId || loadingPdf || loadingExcel"
                        @click="downloadFile('excel')"
                        class="group rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-4 text-left transition hover:border-emerald-200 hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-60">
                        <div class="flex items-center justify-between gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                <i v-if="loadingExcel" class="fa-solid fa-spinner animate-spin"></i>
                                <i v-else class="fa-regular fa-file-excel"></i>
                            </div>

                            <i class="fa-solid fa-download text-xs text-emerald-500"></i>
                        </div>

                        <p class="m-0 mt-3 text-sm font-bold text-emerald-700">
                            Excel
                        </p>
                        <p class="m-0 mt-0.5 text-xs text-emerald-600/80">
                            Descargar archivo
                        </p>
                    </button>
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-100 pt-4">
                <el-button :disabled="loadingPdf || loadingExcel" @click="emits('close')">
                    Cerrar
                </el-button>
            </div>
        </div>
    </el-dialog>
</template>

<script setup>
import { ref, watch } from 'vue'
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption'
import tenant from '../../../../stores/tenant'
import { usePdfViewerStore } from '../../../../stores/pdf-viewer'

const pdfViewerStore = usePdfViewerStore()

const props = defineProps({
    number_chain: {
        default: null
    },
    visible: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits(['close'])

const loadingExcel = ref(false)
const loadingPdf = ref(false)
const loading = ref(false)
const chainId = ref(null)

const onSubmit = async () => {
    loading.value = true

    try {
        const { data } = await tenant.post(`reception/generate-ot`, {
            number_chain: props.number_chain
        })

        if (data.data) {
            chainId.value = data.data.id
        }
    } catch (e) {
        handleErrorsExeption(e)
    } finally {
        loading.value = false
    }
}

const downloadFile = async (type) => {
    try {
        if (!chainId.value) return

        if (type === 'pdf') {
            loadingPdf.value = true

            try {
                const response = await tenant.get(`reception/view-pdf-ot/${chainId.value}`, {
                    responseType: 'blob'
                })

                const blob = new Blob([response.data], {
                    type: 'application/pdf'
                })

                const pdfUrl = window.URL.createObjectURL(blob)

                pdfViewerStore.url = pdfUrl
                pdfViewerStore.state = true
            }
            catch (e) {
                handleErrorsExeption(e)
            }
            finally {
                loadingPdf.value = false
            }
        }
        else {
            loadingExcel.value = true

            const response = await tenant.get(`reception/download-excel/${chainId.value}`, {
                responseType: 'blob'
            })

            const blob = new Blob([response.data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            })

            const downloadUrl = window.URL.createObjectURL(blob)
            const link = document.createElement('a')

            link.href = downloadUrl
            link.download = `orden_trabajo_${chainId.value}.xlsx`

            document.body.appendChild(link)
            link.click()
            link.remove()

            window.URL.revokeObjectURL(downloadUrl)
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingPdf.value = false
        loadingExcel.value = false
    }
}

watch(() => props.visible, (newVal) => {
    if (newVal && props.number_chain) {
        chainId.value = null
        onSubmit()
    }

    if (!newVal) {
        chainId.value = null
        loading.value = false
        loadingPdf.value = false
        loadingExcel.value = false
    }
})
</script>

<style scoped>
:deep(.download-ot-dialog .el-dialog) {
    border-radius: 16px;
    overflow: hidden;
}

:deep(.download-ot-dialog .el-dialog__header) {
    margin-right: 0;
    padding: 22px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
}

:deep(.download-ot-dialog .el-dialog__body) {
    padding: 20px 24px 24px;
}
</style>
