<template>
    <custom-header title="Matrices" description="Registro y control de matrices." icon="fa-solid fa-receipt">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.q" placeholder="Buscar..." clearable class="!w-full sm:!w-[260px]">
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
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="matrices"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column prop="id" label="#" width="60" />

                <el-table-column label="Matriz" min-width="160">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.description }}
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Tipo de muestra" min-width="160">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.type_of_sample?.description }}
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" label="Creado" min-width="170" sortable="custom">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <p class="text-slate-900 font-medium">{{ formatDate(row?.created_at) }}</p>
                            <p class="text-slate-500 text-xs">{{ formatTime(row?.created_at) }}</p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" width="160" fixed="right">
                    <template #default="{ row }">
                        <div class="flex justify-end gap-2">
                            <el-tooltip content="Editar" placement="top">
                                <el-button circle class="!rounded-xl !m-0" @click="handleEdit(row)">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </el-button>
                            </el-tooltip>

                            <el-tooltip content="Eliminar" placement="top">
                                <el-button circle type="danger" plain class="!rounded-xl !m-0"
                                    @click="handleDestroy(row?.id)">
                                    <i class="fa-regular fa-trash-can"></i>
                                </el-button>
                            </el-tooltip>
                        </div>
                    </template>
                </el-table-column>

                <template #empty>
                    <div class="py-16 text-center">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <i class="fa-regular fa-folder-open text-slate-500 text-lg"></i>
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-slate-900">No hay resultados</h3>
                        <p class="mt-1 text-sm text-slate-500">Prueba cambiando filtros o el texto de búsqueda.</p>
                        <el-button class="mt-4 !rounded-xl" @click="resetFilters">Limpiar filtros</el-button>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ matrices.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="changePage" />
        </div>
    </div>

    <el-dialog v-model="visible" width="520px" align-center :show-close="false" class="custom-dialog !rounded-lg">
        <template #header>
            <div class="flex items-center justify-between border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Registrar Matriz
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Completa los datos para asociarlo.
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

                <el-input v-model="formData.description" size="large" placeholder="Ej. ..." clearable />
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Tipo de muestra
                </label>

                <el-select :remote-method="listStore.getTypesSampling" filterable remote reserve-keyword
                    v-model="formData.type_of_sample_id" size="large" clearable>
                    <el-option v-for="row in typeOfSamples" :value="row.id" :label="row.description"></el-option>
                </el-select>
            </div>

            <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                Este tipo de muestra podrá ser usado para generar conexiones o agrupaciones dentro del sistema.
            </div>

            <div class="flex justify-end border-t pt-4">
                <el-button @click="visible = false" class="!rounded-lg">
                    Cancelar
                </el-button>

                <el-button @click="onSubmit" :loading="loadingSubmit" type="primary" native-type="submit"
                    class="!rounded-lg">
                    Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>

    <confirm-dialog ref="confirmRef" />
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import tenant from '../../../stores/tenant';
import { ElMessage, ElNotification } from 'element-plus';
import { useListStore } from '../../../stores/list';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';

const visible = ref(false)
const confirmRef = ref(null)
const listStore = useListStore()
const typeOfSamples = computed(() => listStore.typesSampling)

const filters = ref({
    q: "",
})

const loadingSubmit = ref(false);
const loading = ref(false)
const matrices = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const formData = ref({
    id: null,
    description: null,
    type_of_sample_id: null,
})

const addEssays = (row) => {
    const index = formData.value.essays.findIndex(e => e.id === row.id)

    if (index !== -1) {
        ElMessage.error("El ensayo ya fue agregado")
        return
    }

    formData.value.essays.push(row)
}

const getMatriz = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`matrix?page=${page}`, {
            params: {
                search: filters.value.q
            }
        })

        if (data.data) {
            matrices.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total,
            }
        }
    }
    catch (e) {
        console.error(e)
    }
    finally {
        loading.value = false
    }
}

const changePage = (p) => {
    getMatriz(p)
}

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (formData.value.id) {
            const { data } = await tenant.put(`matrix/${formData.value.id}`, formData.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`matrix`, formData.value)
            ElNotification.success(data.message)
        }

        getMatriz()
        handleClose()
    }
    catch (e) {
        console.error(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const handleClose = () => {
    formData.value = {
        id: null,
        description: null,
        type_of_sample_id: null,
    }

    visible.value = false
}

const handleEdit = (row) => {
    visible.value = true

    formData.value = {
        id: row.id,
        description: row.description,
        type_of_sample_id: row.type_of_sample_id,
    }
}

const handleDestroy = async (id) => {
    const ok = await confirmRef.value?.open({
        title: 'Eliminar matriz',
        message: '¿Seguro que deseas eliminar la matriz?',
        confirmText: 'Sí, aceptar',
        cancelText: 'Cancelar',
    })

    if (ok) {
        try {
            const { data } = await tenant.delete(`matrix/${id}`)
            await getMatriz()
            ElNotification.success(data.message)
        }
        catch (e) {
            console.error(e)
        }
    }
}

watch(() => filters.value.q, (newVal) => {
    getMatriz()
})

onMounted(async () => {
    await getMatriz()
    await listStore.getTypesSampling()
})
</script>

<style scoped>
:deep(.lims-table-header) {
    color: #64748a !important;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    background: linear-gradient(180deg, #f8fafc 0%, #f8fafc 100%) !important;
    height: 42px;
}

:deep(.filters-collapse) {
    border: 0;
}

:deep(.filters-collapse .el-collapse-item) {
    overflow: hidden;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: #ffffff;
    /* box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05); */
}

:deep(.filters-collapse .el-collapse-item__header) {
    height: auto;
    min-height: 52px;
    border-bottom: 1px solid #f1f5f9;
    padding: 0 12px;
    background: linear-gradient(180deg, #f8fafc 0%, #f8fafc 100%);
}

:deep(.filters-collapse .el-collapse-item__wrap) {
    border-bottom: 0;
}

:deep(.filters-collapse .el-collapse-item__content) {
    padding: 18px;
}

:deep(.filters-collapse .el-collapse-item__arrow) {
    color: #64748b;
}

:deep(.filters-collapse .el-input__wrapper),
:deep(.filters-collapse .el-select__wrapper) {
    min-height: 40px;
    border-radius: 12px;
}
</style>
