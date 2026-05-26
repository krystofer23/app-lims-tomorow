<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4 shadow-sm lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-regular fa-file-lines text-lg"></i>
                </div>

                <div class="min-w-0">
                    <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                        Report OTs
                    </h1>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Registro y control de OTs generadas.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="search" placeholder="Buscar OS, cadena, código lab..." clearable
                class="!w-full sm:!w-[360px]" @keyup.enter="getReportsOts(1)" @clear="getReportsOts(1)">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <Search />
                    </el-icon>
                </template>
            </el-input>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <el-collapse>
            <el-collapse-item>
                <template #title>
                    <i class="fa-solid fa-filter"></i>
                    Filtros
                </template>
                <div class="grid grid-cols-12">

                </div>
            </el-collapse-item>
        </el-collapse>

        <div class="rounded-2xl border bbg-white overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b bg-slate-50/80 px-5 py-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">
                        Reportes de Órdenes de Trabajo
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Visualiza el PDF o descarga el Excel de cada OT generada.
                    </p>
                </div>

                <div
                    class="hidden sm:flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-500 shadow-sm ring-1 ring-slate-200">
                    <i class="fa-solid fa-file-lines text-emerald-600"></i>
                    {{ reportsOts?.length || 0 }} reportes
                </div>
            </div>

            <div class="overflow-x-auto">
                <el-table :data="reportsOts" v-loading="loading" stripe :header-cell-style="headerStyle"
                    :row-class-name="rowClassName" class="custom-table w-full" table-layout="auto">
                    <el-table-column label="Orden de Servicio" min-width="210">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="truncate text-sm font-bold text-slate-800">
                                            {{ row?.os || '-' }}
                                        </span>
                                    </div>

                                    <div class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-400">
                                        <i class="fa-regular fa-hashtag text-[10px]"></i>
                                        <span>Orden ID: {{ row?.order_id || '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Cadena de Custodia" min-width="190">
                        <template #default="{ row }">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 my-1 text-xs font-semibold text-green-700 ring-1 ring-green-100">
                                <i class="fa-solid fa-link text-[11px]"></i>
                                <span>{{ row?.number_chain || '-' }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Informe de Ensayo" min-width="200">
                        <template #default="{ row }">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1.5 my-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                <i class="fa-solid fa-flask-vial text-[11px]"></i>
                                <span>{{ row?.number_report || '-' }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column fixed="right" width="170" label="Acciones" align="center">
                        <template #default="{ row }">
                            <div class="flex items-center justify-center gap-2">
                                <el-button-group>
                                    <el-button :loading="row.loadingPdf" size="small" type="danger" plain
                                        class="!rounded-l-xl !px-3" v-tippy="'Ver PDF'" @click="handleView(row)">
                                        <i class="fa-solid fa-file-pdf mr-1"></i>
                                        PDF
                                    </el-button>

                                    <el-button :loading="row.loadingExcel" size="small" type="success" plain
                                        class="!rounded-r-xl !px-3" v-tippy="'Descargar Excel'"
                                        @click="handleDownload(row)">
                                        <i class="fa-solid fa-file-excel mr-1"></i>
                                        Excel
                                    </el-button>
                                </el-button-group>
                            </div>
                        </template>
                    </el-table-column>

                    <template #empty>
                        <div class="py-14 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400 ring-1 ring-slate-200">
                                <i class="fa-regular fa-folder-open text-2xl"></i>
                            </div>

                            <p class="text-sm font-bold text-slate-600">
                                No hay reportes de OTs disponibles
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Cuando generes una orden de trabajo, aparecerá en esta tabla.
                            </p>
                        </div>
                    </template>
                </el-table>
            </div>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ reportsOts.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getReportsOts" />
        </div>
    </div>
</template>

<script setup>
import { Search } from '@element-plus/icons-vue'
import tenant from '../../../stores/tenant'
import { onMounted, ref } from 'vue'
import { usePdfViewerStore } from '../../../stores/pdf-viewer'
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption'

const pdfViewerStore = usePdfViewerStore()

const headerStyle = () => {
    return {
        background: '#f8fafc',
        color: '#334155',
        fontWeight: '700',
        fontSize: '13px',
        borderBottom: '1px solid #e2e8f0',
        height: '52px'
    }
}

const rowClassName = ({ rowIndex }) => {
    return rowIndex % 2 === 0 ? 'bg-white' : 'bg-slate-50/40'
}

const loading = ref(false)
const reportsOts = ref([])
const search = ref('')

const filters = ref({
    date_from: null,
    date_to: null,
})

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
})

const getReportsOts = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`report-ots?page=${page}`)

        if (data.data) {
            reportsOts.value = data.data.data || []

            pagination.value = {
                current_page: data.data.current_page || 1,
                last_page: data.data.last_page || 1,
                per_page: data.data.per_page || 15,
                total: data.data.total || 0,
            }
        }
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

const clearFilters = () => {
    search.value = ''

    filters.value = {
        date_from: null,
        date_to: null,
    }

    getReportsOts(1)
}

const firstContent = (row) => {
    return row?.content?.[0]?.content || {}
}

const getNumberChain = (row) => {
    return row?.number_chain || firstContent(row)?.number_chain || '-'
}

const splitParameters = (parameters) => {
    if (!parameters) return []

    return String(parameters)
        .split('\n')
        .map(item => item.trim())
        .filter(Boolean)
}

const formatDate = (date) => {
    if (!date) return '-'

    return String(date).split(' ')[0] || '-'
}

const formatTime = (date) => {
    if (!date) return '-'

    const parts = String(date).split(' ')
    return parts[1]?.slice(0, 5) || '-'
}

const getInitials = (name) => {
    if (!name) return '-'

    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map(item => item[0])
        .join('')
        .toUpperCase()
}

const handleView = async (row) => {
    row.loadingPdf = true

    try {
        const response = await tenant.get(`reception/view-pdf-ot/${row.id}`, {
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
        row.loadingPdf = false
    }
}

const handleDownload = async (row) => {
    row.loadingExcel = true

    try {
        const response = await tenant.get(`reception/download-excel/${row.id}`, {
            responseType: 'blob'
        })

        const blob = new Blob([response.data], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        })

        const downloadUrl = window.URL.createObjectURL(blob)
        const link = document.createElement('a')

        link.href = downloadUrl
        link.download = `orden_trabajo_${row.id}.xlsx`

        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(downloadUrl)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        row.loadingExcel = false
    }
}

const getParameters = (item) => {
    const parameters = item?.content?.parameters

    if (!parameters) return []

    if (Array.isArray(parameters)) {
        return parameters
            .map(parameter => {
                if (typeof parameter === 'string') return parameter.trim()

                return parameter?.name ||
                    parameter?.description ||
                    parameter?.parameter ||
                    parameter?.label ||
                    ''
            })
            .filter(Boolean)
    }

    return String(parameters)
        .split(/\r?\n|,/)
        .map(parameter => parameter.trim())
        .filter(Boolean)
}

onMounted(() => {
    getReportsOts()
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

.custom-parameter-table :deep(.el-table__header th) {
    background: #f8fafc !important;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
}

.parameter-input :deep(.el-input__wrapper) {
    box-shadow: none !important;
    background: transparent !important;
    padding-left: 0;
}

.parameter-input :deep(.el-input__inner) {
    color: #334155;
    font-weight: 600;
}

.custom-textarea :deep(.el-textarea__inner) {
    border-radius: 14px;
    border-color: #e2e8f0;
    background: #f8fafc;
    font-size: 13px;
}

.custom-textarea :deep(.el-textarea__inner:focus) {
    border-color: #f59e0b;
    box-shadow: 0 0 0 4px #fef3c7;
}

.custom-table :deep(.el-table__header th) {
    background: #f8fafc !important;
    color: #475569;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.custom-table :deep(.el-table__row) {
    transition: background-color 0.2s ease;
}

.custom-table :deep(.el-table__row:hover > td) {
    background-color: #f8fafc !important;
}

.custom-table :deep(.el-table__cell) {
    padding-top: 14px;
    padding-bottom: 14px;
}

.custom-table :deep(.el-button + .el-button) {
    margin-left: 0;
}
</style>
