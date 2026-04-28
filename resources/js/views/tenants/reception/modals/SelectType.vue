<template>
    <el-dialog :model-value="props.visible" @close="emits('close')" header="Descargar Orden de Trabajo" modal
        :style="{ width: '400px' }">
        <div class="flex flex-col gap-4">
            <p class="text-gray-600">
                Selecciona el formato que deseas descargar:
            </p>

            <div class="flex gap-3 justify-end">
                <el-button type="danger" :loading="loadingPdf" @click="downloadFile('pdf')">
                    PDF
                </el-button>

                <el-button type="success" :loading="loadingExcel" @click="downloadFile('excel')">
                    Excel
                </el-button>
            </div>
        </div>
    </el-dialog>
</template>

<script setup>
import { ref, watch } from 'vue'
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption'
import tenant from '../../../../stores/tenant'
import { ElMessage, ElNotification } from 'element-plus'

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
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const downloadFile = async (type) => {
    try {
        if (type === 'pdf') loadingPdf.value = true
        if (type === 'excel') loadingExcel.value = true

        const url =
            type === 'excel'
                ? `reception/download-excel/${chainId.value}`
                : `reception/download-pdf/${chainId.value}`

        const response = await tenant.get(url, {
            responseType: 'blob'
        })

        const blob = new Blob([response.data], {
            type:
                type === 'pdf'
                    ? 'application/pdf'
                    : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        })

        const downloadUrl = window.URL.createObjectURL(blob)
        const link = document.createElement('a')

        link.href = downloadUrl
        link.download =
            type === 'pdf'
                ? `orden_trabajo_${props.code}.pdf`
                : `orden_trabajo_${props.code}.xlsx`

        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(downloadUrl)

        emits('close')
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
        onSubmit()
    }
})
</script>
