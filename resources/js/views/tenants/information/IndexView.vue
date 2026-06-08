<template>
    <custom-header title="Módulo de informes" description="Gestiona informes en base a las órdenes de servicio."
        icon="fa-regular fa-file-lines">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[360px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <Search />
                    </el-icon>
                </template>
            </el-input>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <el-collapse v-model="activeNames" class="filters-collapse mb-5">
            <el-collapse-item name="1">
                <template #title>
                    <div class="flex w-full items-center justify-between pr-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-100 text-teal-600 ring-1 ring-cyan-100">
                                <i class="fa-solid fa-filter text-sm"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Filtros de búsqueda
                                </p>
                                <p class="text-xs text-slate-400">
                                    Refina las cotizaciones por comercial, empresa u orden generada
                                </p>
                            </div>
                        </div>
                    </div>
                </template>

                <template #default>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-4">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                Empresa
                            </label>

                            <el-select :remote-method="listStore.getCompanies" filterable remote reserve-keyword
                                clearable v-model="filters.company_id" placeholder="Seleccionar empresa"
                                class="!w-full">
                                <el-option v-for="row in companies" :key="row.id" :label="row.business_name"
                                    :value="row.id" />
                            </el-select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                Solicitante
                            </label>

                            <el-select :remote-method="listStore.getCompanies" filterable remote reserve-keyword
                                clearable v-model="filters.application_id" placeholder="Seleccionar empresa"
                                class="!w-full">
                                <el-option v-for="row in companies" :key="row.id" :label="row.business_name"
                                    :value="row.id" />
                            </el-select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                OS
                            </label>

                            <el-select v-model="filters.order_id" clearable filterable
                                :remote-method="listStore.getOrdersOptimizate" class="w-full"
                                placeholder="Selecciona una orden" size="large">
                                <el-option v-for="order in ordersOptimizate" :key="order.id" :label="order.code"
                                    :value="order.id" />
                            </el-select>
                        </div>
                    </div>
                </template>
            </el-collapse-item>
        </el-collapse>

        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" stripe :data="orders" v-loading="loading"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center" fixed="left">
                    <template #header>N°</template>
                </el-table-column>
                <el-table-column width="300">
                    <template #header>Empresa</template>
                    <template #default="{ row }">
                        <p class="truncate font-semibold" v-tippy="row?.company?.business_name">{{
                            row?.company?.business_name }}</p>
                        <p>
                            RUC: {{ row?.company?.ruc }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="300">
                    <template #header>Solicitante</template>
                    <template #default="{ row }">
                        <p class="truncate font-semibold" v-tippy="row?.application?.business_name">{{
                            row?.application?.business_name }}</p>
                        <p>
                            RUC: {{ row?.application?.ruc }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>OS</template>
                    <template #default="{ row }">
                        <p class="truncate font-semibold">
                            {{ row.code }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>Fecha de registro</template>
                    <template #default="{ row }">
                        <div class="space-y-1">
                            <p class="text-sm font-semibold">
                                {{ formatDate(row?.created_at) }}
                            </p>

                            <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                                <i class="fa-regular fa-clock text-[11px]"></i>
                                {{ formatTime(row?.created_at) }}
                            </p>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="140" fixed="right">
                    <template #header>Acciones</template>
                    <template #default="{ row }">
                        <div class="flex items-center justify-start gap-2">
                            <el-dropdown trigger="click" placement="bottom-end">
                                <button type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                </button>

                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item divide @click="() => {
                                            visible = true
                                            orderId = row.id
                                        }">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-solid fa-file-circle-plus"></i>
                                                <span>Generar Plantillas</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item divided @click="downloadOrderServicePdf(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-file-pdf"></i>
                                                <span>Descargar PDF</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item @click="downloadOrderServiceExcel(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-file-excel"></i>
                                                <span>Descargar Excel</span>
                                            </div>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </template>
                    <template #empty>
                        <div class="py-16 text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                                <i class="fa-solid fa-flask-vial text-2xl"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-bold text-slate-900">
                                No hay cotizaciones registradas
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Ajusta los filtros o registra una nueva cotización para continuar.
                            </p>

                            <el-button class="mt-4 !rounded-xl !font-semibold" plain>
                                Limpiar filtros
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ orders.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="listStore.getOrderServices()" />
        </div>
    </div>

    <el-dialog v-model="visible" align-center @close="() => {
        visible = false
        orderId = null
    }" width="680px" class="template-dialog !rounded-lg" :show-close="true">
        <template #header>
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-1 pb-4">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700 ring-1 ring-teal-200">
                        <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                        Generación de plantillas
                    </div>

                    <h2 class="mt-3 text-lg font-bold text-slate-900">
                        Generar Plantillas
                    </h2>

                    <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600 sm:grid-cols-3">
                        <div v-loading="loadingModal" class="rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-200">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                OS
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ order?.code || 'No indica' }}
                            </p>
                        </div>

                        <div v-loading="loadingModal" class="rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-200">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                Empresa
                            </p>
                            <p class="mt-1 truncate font-semibold text-slate-800">
                                {{ order?.company.business_name || 'No indica' }}
                            </p>
                        </div>

                        <div v-loading="loadingModal" class="rounded-xl bg-slate-50 px-3 py-2 ring-1 ring-slate-200">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                Solicitante
                            </p>
                            <p class="mt-1 truncate font-semibold text-slate-800">
                                {{ order?.application.business_name || 'No indica' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="pt-2">
            <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Selecciona el tipo de muestra para generar la plantilla correspondiente.
            </div>

            <el-table :data="order?.items ?? []" v-loading="loadingModal" header-cell-class-name="lims-table-header"
                size="small" class="overflow-hidden rounded-2xl border border-slate-200"
                empty-text="No hay tipos de muestra disponibles">
                <el-table-column label="Tipo de muestra" min-width="220">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600 ring-1 ring-teal-100">
                                <i class="fa-solid fa-flask"></i>
                            </div>

                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ row?.type }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    Plantilla disponible
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" width="180" align="center">
                    <template #default="{ row }">
                        <el-button-group>
                            <el-button :loading="loadingPdf === row?.type"
                                @click="viewPdf(row?.type, orderId, 'INACAL')" class="!rounded-l-lg"
                                v-tippy="'Generar PDf'" type="primary" plain>
                                <i class="fa-regular fa-file-pdf"></i>
                            </el-button>
                            <el-button :loading="loadingDownload === row?.type"
                                @click="downloadTest(row.type, orderId, 'INACAL')" class="!rounded-r-lg"
                                v-tippy="'Generar Excel'" type="info" plain>
                                <i class="fa-regular fa-file-excel"></i>
                            </el-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <template #footer>
            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4">
                <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    @click="() => {
                        visible = false
                        orderId = null
                    }">
                    Cancelar
                </button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { Plus, Search } from '@element-plus/icons-vue'
import { useListStore } from '../../../stores/list';
import OSViewModal from '../../../components/tenants/OSViewModal.vue';
import { useOsViewModalStore } from '../../../stores/os-view-modal';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import tenant from '../../../stores/tenant';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { usePdfViewerStore } from '../../../stores/pdf-viewer.js';

const listStore = useListStore()
const orders = computed(() => listStore.ordersServices)
const loading = computed(() => listStore.loadingOrderService)
const pagination = computed(() => listStore.paginationOrderService)

const visible = ref(false)
const orderId = ref(null)
const order = ref(null)

const ordersOptimizate = computed(() => listStore.ordersOptimizate)

const activeNames = ref(['1'])

watch(() => orderId.value, (newVal) => {
    if (newVal) {
        getInformationOrder()
    }
})

const loadingModal = ref(false)

const getInformationOrder = async () => {
    loadingModal.value = true

    try {
        const { data } = await tenant.get(`information/${orderId.value}`)

        if (data.data) {
            order.value = data.data
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingModal.value = false
    }
}

const activeName = ref(['1'])
const filters = reactive({
    search: '',
    application_id: null,
    company_id: null,
    order_id: null
})

const companies = computed(() => listStore.companies)

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const loadingDownload = ref(null)
const loadingPdf = ref(null)

const pdfViewerStore = usePdfViewerStore()

const viewPdf = async (type, order_id, condition) => {
    loadingPdf.value = type

    try {
        const response = await tenant.get(`information/view-inform-report-pdf/${order_id}`, {
            responseType: 'blob',
            params: {
                type: type,
                condition: condition,
            }
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
        loadingPdf.value = null
    }
}

const downloadTest = async (type, order_id, condition) => {
    loadingDownload.value = type

    try {
        const response = await tenant.post(`information/download-inform-report-excel/${order_id}`, {
            type: type,
            condition: condition
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
        loadingDownload.value = null
    }
}

watch(() => filters, () => {
    listStore.getOrderServices(null, 1, filters)
}, { deep: true })

onMounted(() => {
    listStore.getCompanies()
    listStore.getOrderServices()
    listStore.getOrdersOptimizate()
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

.template-dialog {
    border-radius: 18px !important;
    overflow: hidden;
}

.template-dialog .el-dialog__header {
    margin-right: 0 !important;
    padding: 24px 24px 12px 24px !important;
}

.template-dialog .el-dialog__body {
    padding: 12px 24px 20px 24px !important;
}

.template-dialog .el-dialog__footer {
    padding: 0 24px 20px 24px !important;
}
</style>
