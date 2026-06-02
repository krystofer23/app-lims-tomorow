<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4 shadow-sm lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-file-shield text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            Módulo de informes
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Gestiona informes en base a las órdenes de servicio
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[360px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="generateInformation" type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <i class="fa-solid fa-file-shield mr-2"></i>
                Generar Informe
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <el-collapse class="mb-5">
            <el-collapse-item name="1">
                <template #title>
                    <i class="fa-solid fa-filter"></i> Filtros
                </template>
                <template #default>
                    <!-- <div class="grid grid-cols-12 w-full gap-3">
                        <div class="col-span-3">
                            <p class="font-medium">Comercial</p>
                            <el-select v-model="filters.comercial_id" placeholder="Seleccionar" class="!w-full"
                                size="small" clearable>
                                <el-option v-for="row in comerciales"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-3">
                            <p class="font-medium">Empresa</p>
                            <el-select v-model="filters.company_id" placeholder="Seleccionar" class="!w-full"
                                size="small" clearable>
                                <el-option v-for="row in companies" :label="row.business_name"
                                    :value="row.id"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-3">
                            <p class="font-medium">OS Generada</p>
                            <el-switch v-model="filters.is_os" size="small"></el-switch>
                        </div>
                    </div> -->
                </template>
            </el-collapse-item>
        </el-collapse>
    </div>

    <el-button :loading="loadingDownload" @click="downloadTest">Download Test</el-button>

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
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import tenant from '../../../stores/tenant';

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

const loadingDownload = ref(false)

const downloadTest = async () => {
    loadingDownload.value = true

    try {
        const response = await tenant.post(`/download-inform-report/9`, {
            type: 'AGUA'
        }, {
            responseType: 'blob',
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'download-inform-report.xlsx')
        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(url)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingDownload.value = false
    }
}

onMounted(() => {
    listStore.getOrderServices()
})
</script>

<style scoped>
:deep(.el-table th.el-table__cell) {
    background: #f8fafc;
    color: #334155;
    font-weight: 700;
}

:deep(.el-table td.el-table__cell),
:deep(.el-table th.el-table__cell) {
    padding: 14px 0;
}

:deep(.el-popover) {
    border-radius: 10px !important;
}

:deep(.custom-table .el-table__cell) {
    padding-top: 14px;
    padding-bottom: 14px;
    vertical-align: middle;
}

:deep(.custom-table .el-table__row:hover > td.el-table__cell) {
    background-color: #f8fafc !important;
}

:deep(.custom-table .el-table__inner-wrapper::before) {
    display: none;
}

:deep(.custom-table th.el-table__cell) {
    border-bottom: 1px solid #e2e8f0 !important;
}

:deep(.custom-table td.el-table__cell) {
    border-bottom: 1px solid #f1f5f9 !important;
}

:deep(.el-input__wrapper) {
    border-radius: 10px !important;
}
</style>
