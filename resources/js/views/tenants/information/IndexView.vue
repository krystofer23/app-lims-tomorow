<template>
    <div
        class="h-[70px] flex flex-col gap-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 via-white to-emerald-50/60 px-6 py-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-md shadow-emerald-100">
                    <i class="fa-regular fa-file-lines text-lg"></i>
                </div>

                <div>
                    <h1 class="text-lg font-bold tracking-tight text-slate-800">
                        Módulo de informes
                    </h1>
                    <p class="-mt-1 text-xs text-slate-500">
                        Gestiona informes en base a las órdenes de servicio
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar por razón social, cadena o informe" clearable
                class="!w-full sm:!w-[340px]">
                <template #prefix>
                    <el-icon>
                        <Search />
                    </el-icon>
                </template>
            </el-input>

            <el-button type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90"
                @click="generateInformation">
                <i class="fa-regular fa-file-lines mr-2"></i>
                Generar informe
            </el-button>
        </div>
    </div>

    <div class="bg-white">
        <div class="p-5 md:p-6">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-1">
                <el-collapse v-model="activeNames" class="custom-collapse">
                    <el-collapse-item name="1">
                        <template #title>
                            <div class="flex items-center gap-2 text-slate-700">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                                    <i class="fa-solid fa-filter text-sm text-emerald-500"></i>
                                </div>
                                <span class="font-semibold">Filtros</span>
                            </div>
                        </template>

                        <template #default>
                            <div class="mt-2 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                                            Empresa
                                        </label>
                                        <el-select placeholder="Seleccionar empresa" class="!w-full" />
                                    </div>

                                    <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                                            Orden de servicio
                                        </label>
                                        <el-select placeholder="Seleccionar orden" class="!w-full" />
                                    </div>

                                    <div class="col-span-12 xl:col-span-4 flex flex-col sm:flex-row sm:items-end">
                                        <el-button v-tippy="'Filtrar'"
                                            class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                                            <i class="fa-solid fa-magnifying-glass mr-2"></i>
                                            Filtrar
                                        </el-button>

                                        <el-button v-tippy="'Limpiar filtros'"
                                            class="!h-9 !rounded-xl !border-slate-200 !bg-white !px-5 !font-medium !text-slate-600 hover:!border-slate-300 hover:!text-slate-800">
                                            <i class="fa-solid fa-filter-circle-xmark mr-2"></i>
                                            Limpiar
                                        </el-button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </div>
    </div>

    <el-dialog v-model="state" width="820px" class="search-os-dialog !rounded-2xl" align-center destroy-on-close>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Buscar Orden de Servicio</h2>
                    <p class="text-sm text-slate-500">
                        Busca una OS por código y selecciónala de la lista.
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <el-input placeholder="Ejemplo: OS-0003" clearable class="custom-input flex-1">
                        <template #prefix>
                            <i class="fa-solid fa-hashtag text-slate-400"></i>
                        </template>
                    </el-input>

                    <el-button type="primary"
                        class="custom-search-btn !h-[44px] !rounded-xl !border-0 !bg-emerald-400 !px-5 hover:!bg-emerald-500"
                        v-tippy="'Buscar'">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        Buscar
                    </el-button>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-4 py-3">
                    <h3 class="text-sm font-semibold text-slate-700">Resultados encontrados</h3>
                </div>

                <el-table v-loading="loadingOrderService" :data="orderServices" stripe class="custom-table"
                    empty-text="No se encontraron órdenes de servicio">
                    <el-table-column label="Empresa" min-width="260">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-slate-800">
                                        {{ row?.company?.business_name || '---' }}
                                    </p>
                                    <p class="truncate text-xs text-slate-500">
                                        Cliente / Empresa
                                    </p>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="OS" min-width="180" align="center">
                        <template #default="{ row }">
                            <span class="inline-flex rounded-lg bg-teal-400 text-white px-3 py-1 text-xs font-semibold">
                                {{ row.code || 'OS-0000' }}
                            </span>
                        </template>
                    </el-table-column>

                    <el-table-column label="Acciones" min-width="160" align="center">
                        <template #default="{ row }">
                            <el-button-group>
                                <el-button @click="handleOs(row.id)" v-tippy="'Ver OS'" size="small">
                                    <i class="fa-solid fa-eye"></i>
                                </el-button>
                                <el-button v-tippy="'Ver cadena de custodia'" size="small" type="warning">
                                    <i class="fa-solid fa-file-circle-check"></i>
                                </el-button>
                                <el-button @click="handleFormat" v-tippy="'Generar informe'" type="primary"
                                    size="small">
                                    <i class="fa-solid fa-file-import"></i>
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Mostrando <span class="font-semibold text-slate-700">{{ orderServices.length }}</span> de
                    <span class="font-semibold text-slate-700">{{ paginationOrderService.total }}</span> registros
                </p>

                <el-pagination background layout="prev, pager, next, sizes" :total="paginationOrderService.total"
                    v-model:page-size="paginationOrderService.per_page"
                    v-model:current-page="paginationOrderService.current_page" :page-sizes="[10, 20, 50, 100]"
                    @change="getOrderServices" />
            </div>
        </div>
    </el-dialog>

    <el-dialog v-model="stateFormat" width="520px" class="search-os-dialog !rounded-2xl" align-center destroy-on-close>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-list-check text-lg"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Seleccionar Formato</h2>
                    <p class="text-sm text-slate-500">
                        Selecciona un formato a generar
                    </p>
                </div>
            </div>
        </template>

        <el-table>
            <el-table-column label="Formato">

            </el-table-column>
            <el-table-column label="Acciones">
                <template #default="{ row }">
                    <el-button-group>
                        <el-button>Seleccionar</el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>

    <OSViewModal />
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { Plus, Search } from '@element-plus/icons-vue'
import { useListStore } from '../../../stores/list';
import OSViewModal from '../../../components/tenants/OSViewModal.vue';
import { useOsViewModalStore } from '../../../stores/os-view-modal';

const listStore = useListStore()
const osViewModalStore = useOsViewModalStore()

const state = ref(false)
const orderServices = computed(() => listStore.ordersServices)
const paginationOrderService = computed(() => listStore.paginationOrderService)
const loadingOrderService = computed(() => listStore.loadingOrderService)

const stateFormat = ref(false)

const filters = reactive({
    search: '',
    tipoMuestra: '',
    matriz: '',
    condicion: '',
    fechaRecepcion: ''
})

const activeNames = ref(['1'])

const generateInformation = () => {
    state.value = true
}

const getOrderServices = async (p) => {
    listStore.getOrderServices(null, p)
}

const handleFormat = () => {
    stateFormat.value = true
}

const handleOs = (orderId) => {
    osViewModalStore.state = true
    osViewModalStore.orderId = orderId
}

onMounted(() => {
    listStore.getOrderServices()
})
</script>

<style scoped>
.search-os-dialog :deep(.el-dialog) {
    border-radius: 1rem;
    padding: 0.25rem;
}

.search-os-dialog :deep(.el-dialog__header) {
    margin-right: 0;
    padding: 1.25rem 1.25rem 0.5rem 1.25rem;
}

.search-os-dialog :deep(.el-dialog__body) {
    padding: 1rem 1.25rem 1.25rem 1.25rem;
}

.search-os-dialog :deep(.el-dialog__headerbtn) {
    top: 1.1rem;
    right: 1rem;
}

.search-os-dialog :deep(.el-dialog__headerbtn .el-dialog__close) {
    color: #64748b;
    font-size: 18px;
}

.search-os-dialog :deep(.el-dialog__headerbtn:hover .el-dialog__close) {
    color: #10b981;
}

.custom-input :deep(.el-input__wrapper) {
    min-height: 44px;
    border-radius: 0.9rem;
    box-shadow: none;
    border: 1px solid #e2e8f0;
    background-color: white;
}

.custom-input :deep(.el-input__wrapper:hover) {
    border-color: #cbd5e1;
}

.custom-input :deep(.is-focus) {
    border-color: #34d399 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12) !important;
}

.custom-search-btn {
    font-weight: 600;
    box-shadow: 0 10px 20px -12px rgba(16, 185, 129, 0.9);
}

.custom-table :deep(.el-table__header th) {
    background-color: #f8fafc !important;
    color: #334155;
    font-weight: 700;
}

.custom-table :deep(.el-table__row td) {
    padding-top: 14px;
    padding-bottom: 14px;
}

.custom-table :deep(.el-table__empty-block) {
    min-height: 180px;
}

.custom-table :deep(.el-table__empty-text) {
    color: #94a3b8;
    font-size: 14px;
}

:deep(.custom-collapse) {
    border: none;
    background: transparent;
}

:deep(.custom-collapse .el-collapse-item__header) {
    border: none;
    background: transparent;
    height: auto;
    line-height: normal;
    padding: 0.5rem 0.75rem;
}

:deep(.custom-collapse .el-collapse-item__wrap) {
    border: none;
    background: transparent;
}

:deep(.custom-collapse .el-collapse-item__content) {
    padding-bottom: 0;
}
</style>