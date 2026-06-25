<template>
    <custom-header title="Condiciones" description="Registro y control de condiciones." icon="fa-solid fa-compress">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="state = true" type="primary"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                Agregar Registro
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="conditions"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" label="#" width="60" />

                <el-table-column label="Condición" min-width="280">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.description ?? '-' }}
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Información" min-width="280">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.info ?? '-' }}
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

                            <!-- <el-tooltip content="Eliminar" placement="top">
                                <el-button circle type="danger" plain class="!rounded-xl !m-0"
                                    @click="handleDestroy(row?.id)">
                                    <i class="fa-regular fa-trash-can"></i>
                                </el-button>
                            </el-tooltip> -->
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
                Mostrando <span class="font-semibold text-slate-700">{{ conditions.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="changePage" />
        </div>
    </div>

    <el-dialog v-model="state" @close="handleClose" :close-on-click-modal="false"
        class="!p-0 overflow-hidden !rounded-xl" width="640px" align-center>
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                                <i class="fa-solid fa-road-barrier text-emerald-500"></i>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 leading-5">
                                    {{ formData.id ? 'Actualizar condición' : 'Registrar condición' }}
                                </h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    Completa los campos y guarda los cambios.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 h-px w-full bg-gradient-to-r from-emerald-400 via-emerald-300 to-emerald-400">
                </div>
            </div>
        </template>

        <div class="px-6 pb-6 pt-5">
            <div class="grid grid-cols-1 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <label class="text-sm font-semibold text-slate-900">Condición</label>

                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.description" type="textarea" :autosize="{ minRows: 3, maxRows: 8 }"
                        placeholder="Ej: Debe cumplir el rango permitido antes de auditar..." class="mt-2" />

                    <p class="mt-2 text-xs text-slate-500 flex items-start gap-2">
                        <i class="fa-solid fa-circle-info mt-[2px] text-emerald-500"></i>
                        Describe la condición de forma clara y accionable.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-100/40 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <label class="text-sm font-semibold text-slate-900">
                            Información (opcional)
                        </label>

                        <span class="text-[11px] text-slate-500 inline-flex items-center gap-2">
                            <i class="fa-regular fa-note-sticky text-emerald-500"></i>
                            Contexto extra
                        </span>
                    </div>

                    <el-input v-model="formData.info" type="textarea" :autosize="{ minRows: 3, maxRows: 8 }"
                        placeholder="Ej: Referencia, nota adicional, link interno, etc." class="mt-2" />

                    <p class="mt-2 text-xs text-slate-500 flex items-start gap-2">
                        <i class="fa-solid fa-link mt-[2px] text-emerald-500"></i>
                        Úsalo para notas, referencias o detalles que ayuden a entender la condición.
                    </p>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="px-6 pb-6">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <el-button class="!rounded-xl !m-0" @click="handleClose">
                        Cerrar
                    </el-button>

                    <el-button type="primary"
                        class="!rounded-xl !bg-emerald-400 !border-emerald-400 hover:!bg-emerald-500 hover:!border-emerald-500"
                        @click="onSubmit()" :loading="loadingSubmit">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                        {{ formData.id ? 'Guardar cambios' : 'Guardar' }}
                    </el-button>
                </div>

                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Los campos se guardan al confirmar.
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-keyboard text-emerald-500"></i>
                        Tip: usa Enter para saltar líneas
                    </span>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue"
import { ElNotification } from "element-plus";
import tenant from "../../../stores/tenant"
import CustomHeader from "../../../components/tenants/CustomHeader.vue";
import { Search } from "@element-plus/icons-vue";

const state = ref(false)

const filters = reactive({
    q: null,
    status: null,
    search: null
});

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

const loading = ref(false)
const conditions = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const formData = ref({
    id: null,
    description: null,
    info: null,
})

const getConditions = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`conditions?page=${page}`, {
            params: {
                search: filters.search
            }
        })

        if (data.data) {
            conditions.value = data.data.data
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

const resetFilters = () => {
    filters.q = "";
    filters.status = "";
    filters.chips.clear();
    pagination.page = 1;
}

const changePage = (p) => {
    getConditions(p)
}

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const loadingSubmit = ref(false)

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (formData.value.id) {
            const { data } = await tenant.put(`conditions/${formData.value.id}`, formData.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`conditions`, formData.value)
            ElNotification.success(data.message)
        }

        getConditions()
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
    formData.value.id = null
    formData.value.description = null
    formData.value.info = null

    state.value = false
}

const handleEdit = (row) => {
    state.value = true

    formData.value.id = row.id
    formData.value.description = row.description
    formData.value.info = row.info
}

const handleDestroy = async (id) => {
    try {
        const { data } = await tenant.delete(`conditions/${id}`)
        await getConditions()
        ElNotification.success(data.message)
    }
    catch (e) {
        console.error(e)
    }
}

watch(() => filters.search, (newVal) => {
    getConditions()
})

onMounted(() => {
    getConditions()
})
</script>

<style scoped>
:deep(.lims-table-header) {
    /* background: #fff ; */
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
