<template>
    <custom-header title="Parametros" description="Registro y control de parametros." icon="fa-solid fa-receipt">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button type="primary" @click="visible = true"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                Agregar Registro
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="parameters"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="description" label="Descripción"></el-table-column>
                <el-table-column label="Tipo de análisis">
                    <template #default="{ row }">
                        <p v-if="row.type_of_analysis">
                            {{ row.type_of_analysis.description ?? '-' }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column label="Acciones">
                    <template #default="{ row }">
                        <el-dropdown trigger="click" placement="bottom-end">
                            <button type="button"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>

                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item @click="handleEdit(row)">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Editar</span>
                                        </div>
                                    </el-dropdown-item>

                                    <el-dropdown-item divided @click="onDelete(row)">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-regular fa-trash-can"></i>
                                            <span>Eliminar</span>
                                        </div>
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </template>
                </el-table-column>
                <template #empty>
                    <div class="py-16 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                            <i class="fa-solid fa-flask-vial text-2xl"></i>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-900">
                            No hay parametros registrados
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Ajusta los filtros o registra una nuevo parametro para continuar.
                        </p>

                        <el-button class="mt-4 !rounded-xl !font-semibold" plain>
                            Limpiar filtros
                        </el-button>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ parameters.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getParameters" />
        </div>
    </div>

    <el-dialog v-model="visible" width="520px" align-center :show-close="false" class="custom-dialog !rounded-lg">
        <template #header>
            <div class="flex items-center justify-between border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Registrar parámetro
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Completa los datos para asociarlo al análisis correspondiente.
                    </p>
                </div>

                <button type="button"
                    class="rounded-full p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                    @click="visible = false">
                    ✕
                </button>
            </div>
        </template>

        <form class="space-y-5 pt-2" @submit.prevent="onSubmit">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Descripción
                </label>

                <el-input v-model="form.description" size="large" placeholder="Ej. Metales Totales" clearable />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Tipo de análisis
                </label>

                <el-select v-model="form.type_of_analysis_id" size="large" placeholder="Seleccione el tipo de análisis"
                    filterable remote reserve-keyword clearable class="w-full" :remote-method="handleGetTypesAnalysis">
                    <el-option v-for="item in typeOfAnalysis" :key="item.id" :label="item.description"
                        :value="item.id" />
                </el-select>
            </div>

            <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                Este parámetro podrá ser usado para generar conexiones o agrupaciones dentro del sistema.
            </div>

            <div class="flex justify-end border-t pt-4">
                <el-button @click="visible = false" class="!rounded-lg">
                    Cancelar
                </el-button>

                <el-button @click="onSubmit" :loading="loadingSubmit" type="primary" native-type="submit"
                    class="!rounded-lg">
                    Guardar parámetro
                </el-button>
            </div>
        </form>
    </el-dialog>

    <confirm-dialog ref="confirmRef" />
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import { handleErrorsExeption } from "../../../stores/handleErrorsExeption"
import tenant from "../../../stores/tenant"
import { ElMessage, ElNotification } from 'element-plus';
import { useListStore } from '../../../stores/list'
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';

const listStore = useListStore()
const typeOfAnalysis = computed(() => listStore.typesAnalysis)

const confirmRef = ref(null)
const visible = ref(false)

const filters = reactive({
    search: null,
})

const loading = ref(false)
const parameters = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0
})

const loadingSubmit = ref(false)

const form = ref({
    id: null,
    description: null,
    type_of_analysis_id: null,
})

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (form.value.id) {
            const { data } = await tenant.put(`parameters/${form.value.id}`, form.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`parameters`, form.value)
            ElNotification.success(data.message)
        }

        form.value = {
            id: null,
            description: null,
            type_of_analysis_id: null,
        }

        visible.value = false
        getParameters()
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const handleEdit = (row) => {
    visible.value = true

    form.value = {
        id: row.id,
        description: row.description,
        type_of_analysis_id: row.type_of_analysis_id,
    }
}

const onDelete = async (row) => {
    const ok = await confirmRef.value?.open({
        title: 'Eliminar parametro',
        message: '¿Seguro que deseas eliminar el parametro?',
        confirmText: 'Sí, aceptar',
        cancelText: 'Cancelar',
    })

    if (ok) {
        row.loading = true

        try {
            const { data } = await tenant.delete(`parameters/${row.id}`)
            ElMessage.success(data.message)
            getParameters()
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            row.loading = false
        }
    }
}

const getParameters = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`parameters?page=${page}`, {
            params: {
                ...filters
            }
        })

        if (data.data) {
            parameters.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total
            }
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const handleGetTypesAnalysis = (q) => {
    listStore.getTypesAnalysis(null, null, null, q)
}

watch(() => filters, () => {
    getParameters()
}, { deep: true })

onMounted(async () => {
    await getParameters()
    await listStore.getTypesAnalysis()
})
</script>

<style scoped>
.custom-dialog :deep(.el-dialog) {
    border-radius: 16px;
}

.custom-dialog :deep(.el-dialog__header) {
    margin-right: 0;
    padding-bottom: 0;
}

.custom-dialog :deep(.el-dialog__body) {
    padding-top: 12px;
}
</style>
