<template>
    <el-dialog v-model="props.state" top="5vh" :style="{ width: computedDialogWidth }"
        style="border-radius: 6px !important;" @close="close()">
        <template #default>
            <el-alert type="primary" class="m-0 p-0">
                <template #default>
                    <div class="text-sm font-medium" style="color: #1aa3c8; font-size: 13px;">Importante!</div>
                    <div class="text-sm text-gray-700" style="text-transform: capitalize; font-size: 13px;">
                        Para facilitar la carga masiva de datos, primero debes generar la plantilla correspondiente. Haz
                        clic en
                        el botón "Plantilla de Importación" a continuación. Esto te proporcionará un archivo que podrás
                        completar con la información necesaria. Una vez que hayas llenado la plantilla, podrás cargarla
                        fácilmente en el sistema.
                    </div>
                </template>
            </el-alert>
            <div class="mt-5.5 flex flex-col justify-center items-center gap-3">
                <el-button @click="downloadTemplate()" class="m-0 p-0" type="warning" size="small">
                    <i class="fa-solid fa-download me-2"></i>
                    Plantilla de Importación
                </el-button>
                <el-upload ref="uploadRef" :auto-upload="false" :limit="1" :multiple="false" @change="handleFileChange"
                    :show-file-list="true" accept=".xlsx">
                    <el-button slot="trigger" type="primary" size="small">
                        <i class="fa fa-cloud-upload me-2" aria-hidden="true"></i>
                        Seleccione un archivo (xlsx)
                    </el-button>
                </el-upload>
            </div>
            <div class="mt-4">
                <el-button size="small" @click="close()">Cancelar</el-button>
                <el-button :loading="loader" size="small" @click="onsubmit()">Procesar</el-button>

                <div v-if="hasResult" class="mt-4">
                    <el-alert type="success" show-icon :closable="false">
                        <template #title>Resultado de importación</template>

                        <div class="mt-2">
                            <el-descriptions size="small" :column="3" border>
                                <el-descriptions-item label="Registrados">
                                    {{ result.summary.created }}
                                </el-descriptions-item>
                                <el-descriptions-item label="Omitidos (duplicados)">
                                    {{ result.summary.skipped }}
                                </el-descriptions-item>
                                <el-descriptions-item label="Fallidos">
                                    {{ result.summary.failed }}
                                </el-descriptions-item>
                            </el-descriptions>
                        </div>
                    </el-alert>

                    <div v-if="(result.skipped_rows?.length ?? 0) > 0" class="mt-3">
                        <div class="text-xs font-semibold text-slate-700 mb-2">
                            Omitidos ({{ result.skipped_rows.length }})
                        </div>

                        <el-table :data="result.skipped_rows" size="small" height="260" style="width: 100%">
                            <el-table-column prop="row" label="Fila" width="70" />
                            <el-table-column prop="code" label="Código" min-width="160" />
                            <el-table-column prop="serie" label="Serie" min-width="130" />
                            <el-table-column prop="model" label="Modelo" min-width="140" />
                            <el-table-column prop="brand" label="Marca" min-width="140" />
                            <el-table-column prop="reason" label="Motivo" min-width="180" />
                        </el-table>
                    </div>

                    <div v-if="(result.failed_rows?.length ?? 0) > 0" class="mt-3">
                        <div class="text-xs font-semibold text-red-700 mb-2">
                            Fallidos ({{ result.failed_rows.length }})
                        </div>

                        <el-table :data="result.failed_rows" size="small" height="220" style="width: 100%">
                            <el-table-column prop="row" label="Fila" width="70" />
                            <el-table-column prop="code" label="Código" min-width="160" />
                            <el-table-column prop="serie" label="Serie" min-width="130" />
                            <el-table-column prop="error" label="Error" min-width="240" />
                        </el-table>
                    </div>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import api from '@/stores/api';
import { ElMessage, ElNotification } from 'element-plus';
import { useWindowSize } from '@vueuse/core';
import { computed, ref } from 'vue';
// import { useLoaderStore } from '@/stores/loader';
import { handleErrorsExeption } from '../../stores/handleErrorsExeption';
import tenant from '../../stores/tenant';

// const loaderStore = useLoaderStore();

const props = defineProps({
    state: {
        type: Boolean,
        default: true
    },
    route: {
        type: String,
        default: 'import'
    },
    url: {
        type: String,
        default: 'import'
    },
    title: {
        type: String,
        default: 'Importe'
    },
});

const emits = defineEmits(['close']);

const loader = ref(false);

const formData = ref({
    file: null,
});

const handleFileChange = (file) => {
    formData.value.file = file.raw;
};

const result = ref(null);
const hasResult = computed(() => !!result.value?.summary);

const onsubmit = async () => {
    loader.value = true;

    try {
        if (!formData.value.file) {
            ElNotification.warning('Selecciona un archivo primero.');
            return;
        }

        const form = new FormData();
        form.append("file", formData.value.file);

        const { data } = await tenant.post(`${props.route}`, form, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (data.message === 'Importación finalizada') {
            ElMessage.success(data.message)
            return
        }

        result.value = data.data;

        ElNotification.success(
            `Importación lista. Registrados: ${data.data.summary?.created ?? 0}, Omitidos: ${data.data.summary?.skipped ?? 0}, Fallidos: ${data.data.summary?.failed ?? 0}`
        );
    } catch (e) {
        handleErrors(e);
    } finally {
        loader.value = false;
    }
};


const downloadTemplate = async () => {
    // loaderStore.state = true;

    try {
        const { data } = await api.get(`${props.url}`, {
            responseType: 'blob'
        });

        const blobUrl = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement("a");
        link.href = blobUrl;
        link.setAttribute("download", `template_${props.title}.xlsx`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    catch (e) {
        handleErrorsExeption(e);
    }
    finally {
        // loaderStore.state = false;
    }
}

const close = (get = false) => {
    formData.value = { file: null };
    result.value = null;

    emits('close', get);
}

const { width: windowWidth } = useWindowSize();

const computedDialogWidth = computed(() => {
    if (windowWidth.value <= 576) {
        return "90%";
    } else if (windowWidth.value <= 768) {
        return "80%";
    } else if (windowWidth.value <= 992) {
        return "70%";
    } else if (windowWidth.value <= 1200) {
        return "40%";
    } else {
        return "30%";
    }
});
</script>

<style scoped></style>