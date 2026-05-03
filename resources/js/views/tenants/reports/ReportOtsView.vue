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

        <div class="overflow-x-auto">
            <el-table :data="reportsOts" v-loading="loading" stripe :header-cell-style="headerStyle"
                :row-class-name="rowClassName" class="custom-table w-full" table-layout="auto">
                <el-table-column prop="id" label="#" width="80">
                    <template #default="{ row }">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-xs font-bold text-slate-700">
                                {{ row.id }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="OS" min-width="160">
                    <template #default="{ row }">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800">
                                {{ row.os || '-' }}
                            </span>
                            <span class="text-xs text-slate-400">
                                Orden ID: {{ row.order_id || '-' }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Cadena" min-width="140">
                    <template #default="{ row }">
                        <el-tag type="success" effect="light" round>
                            {{ getNumberChain(row) }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Código Lab." min-width="150">
                    <template #default="{ row }">
                        <div class="flex flex-wrap gap-1">
                            <el-tag v-for="(item, index) in row.content" :key="index" type="info" effect="plain" round>
                                {{ item.code_lab || item.content?.code_lab || '-' }}
                            </el-tag>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Matriz / Tipo muestra" min-width="240">
                    <template #default="{ row }">
                        <div class="space-y-2">
                            <div v-for="(item, index) in row.content" :key="index"
                                class="rounded-xl border border-slate-100 bg-slate-50/70 px-3 py-2">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ item.content?.matriz || '-' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ item.content?.type_sample || '-' }}
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Parámetros" min-width="230">
                    <template #default="{ row }">
                        <div class="space-y-1">
                            <template v-for="(item, index) in row.content" :key="index">
                                <div v-for="(parameter, pIndex) in splitParameters(item.content?.parameters)"
                                    :key="`${index}-${pIndex}`"
                                    class="inline-flex mr-1 mb-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    {{ parameter }}
                                </div>
                            </template>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="N° Informe" min-width="130">
                    <template #default="{ row }">
                        {{ firstContent(row)?.number_report || '-' }}
                    </template>
                </el-table-column>

                <el-table-column label="N° Ensayos" min-width="120">
                    <template #default="{ row }">
                        {{ firstContent(row)?.number_essays || '-' }}
                    </template>
                </el-table-column>

                <el-table-column label="N° Muestra" min-width="120">
                    <template #default="{ row }">
                        {{ firstContent(row)?.number_sample || '-' }}
                    </template>
                </el-table-column>

                <el-table-column label="Condición" min-width="130">
                    <template #default="{ row }">
                        <el-tag type="success" effect="light">
                            {{ firstContent(row)?.condition_report || '-' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Recepción" min-width="170">
                    <template #default="{ row }">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-700">
                                {{ formatDate(firstContent(row)?.date_reception) }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ formatTime(firstContent(row)?.date_reception) }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Muestreo" min-width="170">
                    <template #default="{ row }">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-700">
                                {{ formatDate(firstContent(row)?.date_sampling_init) }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ formatTime(firstContent(row)?.date_sampling_init) }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Usuario" min-width="180">
                    <template #default="{ row }">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600">
                                {{ getInitials(row.user?.full_name) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ row.user?.full_name || '-' }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ row.user?.document_number || '-' }}
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Generado" min-width="170">
                    <template #default="{ row }">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-700">
                                {{ formatDate(row.created_at) }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ formatTime(row.created_at) }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column fixed="right" width="130" label="Acciones">
                    <template #default="{ row }">
                        <el-button-group>
                            <el-button :loading="row.loading" size="small" type="danger" v-tippy="'Ver pdf'"
                                @click="handleView(row)">
                                <i class="fa-solid fa-file-pdf"></i>
                            </el-button>

                            <el-button :loading="row.loading" size="small" type="success" v-tippy="'Descargar excel'"
                                @click="handleDownload(row)">
                                <i class="fa-solid fa-file-excel"></i>
                            </el-button>
                        </el-button-group>
                    </template>
                </el-table-column>

                <template #empty>
                    <div class="py-10 text-center">
                        <div
                            class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <i class="fa-regular fa-folder-open text-xl"></i>
                        </div>

                        <p class="text-sm font-medium text-slate-500">
                            No hay reportes de OTs disponibles
                        </p>
                    </div>
                </template>
            </el-table>
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
    row.loading = true

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
        row.loading = false
    }
}

const handleDownload = async (row) => {
    row.loading = true

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
        row.loading = false
    }
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
</style>
