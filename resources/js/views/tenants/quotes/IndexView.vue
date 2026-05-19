<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4 shadow-sm lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            Cotizaciones
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Registro y control de cotizaciones.
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

            <el-button @click="$router.push('/quote-create')" type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <i class="fa-solid fa-file-invoice-dollar mr-2"></i>
                Agregar Registro
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <el-collapse v-model="activeNames" class="mb-5">
            <el-collapse-item name="1">
                <template #title>
                    <i class="fa-solid fa-filter"></i> Filtros
                </template>
                <template #default>
                    <div class="grid grid-cols-12 w-full gap-3">
                        <div class="col-span-3">
                            <p class="font-medium">Comercial</p>
                            <el-select :remote-method="listStore.getUsers" filterable remote reserve-keyword clearable
                                v-model="filters.comercial_id" placeholder="Seleccionar" class="!w-full" size="small">
                                <el-option :label="row.full_name" :value="row.id" v-for="row in users"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-3">
                            <p class="font-medium">Empresa</p>
                            <el-select :remote-method="listStore.getCompanies" filterable remote reserve-keyword
                                clearable v-model="filters.company_id" placeholder="Seleccionar" class="!w-full"
                                size="small">
                                <el-option v-for="row in companies" :label="row.business_name"
                                    :value="row.id"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-3">
                            <p class="font-medium">OS Generada</p>
                            <el-switch v-model="filters.is_os" size="small"></el-switch>
                        </div>
                    </div>
                </template>
            </el-collapse-item>
        </el-collapse>

        <div class="overflow-x-auto">
            <el-table stripe :data="quotes" v-loading="loading" class="lims-quotes-table w-full">
                <el-table-column type="index" label="#" width="60" align="center" />

                <el-table-column label="Cliente" min-width="260">
                    <template #default="{ row }">
                        <div class="flex items-start gap-3">
                            <!-- <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                                <i class="fa-regular fa-building text-sm"></i>
                            </div> -->

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-800">
                                    {{ row.company?.business_name ?? '-' }}
                                </p>

                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        RUC: {{ row.company?.ruc ?? '-' }}
                                    </span>

                                    <span
                                        class="inline-flex items-center rounded-full bg-cyan-50 px-2.5 py-1 text-[11px] font-semibold text-cyan-700">
                                        Cliente
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Solicitante" min-width="260">
                    <template #default="{ row }">
                        <div class="flex items-start gap-3">
                            <!-- <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-700 ring-1 ring-teal-100">
                                <i class="fa-regular fa-id-badge text-sm"></i>
                            </div> -->

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-800">
                                    {{ row.applicant?.business_name ?? '-' }}
                                </p>

                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        RUC: {{ row.applicant?.ruc ?? '-' }}
                                    </span>

                                    <span
                                        class="inline-flex items-center rounded-full bg-teal-50 px-2.5 py-1 text-[11px] font-semibold text-teal-700">
                                        Solicitante
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Responsable comercial" min-width="210">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3 py-1">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                                <i class="fa-solid fa-user-tie text-xs"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="line-clamp-2 text-xs font-bold leading-5 text-slate-700">
                                    {{ row.user?.full_name ?? '-' }}
                                </p>
                                <p class="text-[11px] font-medium text-slate-400">
                                    Comercial asignado
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Orden de servicio" width="170" align="center">
                    <template #default="{ row }">
                        <div class="py-1">
                            <span v-if="row?.order_service"
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                <span
                                    class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-white">
                                    <i class="!m-0 !p-0 fa-solid fa-check text-[9px]"></i>
                                </span>
                                Generada
                            </span>

                            <span v-else
                                class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                <span
                                    class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-white">
                                    <i class="!m-0 !p-0 fa-regular fa-alarm-clock text-[9px]"></i>
                                </span>
                                Pendiente
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Contacto técnico" min-width="230">
                    <template #default="{ row }">
                        <el-popover placement="top" :width="360" trigger="hover">
                            <template #default>
                                <div class="rounded-2xl bg-white p-3">
                                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                                            <i class="fa-solid fa-address-book text-sm"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h4 class="truncate text-sm font-bold text-slate-800">
                                                {{ row.contact?.user?.full_name || 'Sin nombre' }}
                                            </h4>

                                            <span
                                                class="mt-1 inline-flex rounded-full bg-cyan-50 px-2.5 py-1 text-[11px] font-bold text-cyan-700">
                                                {{ row.contact?.type || 'Sin tipo' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-start gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                                            <i class="fa-solid fa-envelope mt-1 text-xs text-slate-500"></i>

                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                                    Correo
                                                </p>
                                                <p class="break-all text-xs font-semibold text-slate-700">
                                                    {{ row.contact?.email || 'No registrado' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3 rounded-xl bg-slate-50 px-3 py-2.5">
                                            <i class="fa-solid fa-phone mt-1 text-xs text-slate-500"></i>

                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                                    Teléfono
                                                </p>
                                                <p class="text-xs font-semibold text-slate-700">
                                                    {{ row.contact?.phone || 'No registrado' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template #reference>
                                <button type="button" v-tippy="'Ver información del contacto'"
                                    class="inline-flex max-w-full items-center gap-2 rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-xs font-bold text-cyan-700 transition hover:border-cyan-300 hover:bg-cyan-100 active:scale-[0.98]">
                                    <i class="fa-solid fa-user-check text-[11px]"></i>

                                    <span class="max-w-[160px] truncate">
                                        {{ row.contact?.user?.full_name || 'Sin contacto' }}
                                    </span>
                                </button>
                            </template>
                        </el-popover>
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" label="Fecha de registro" min-width="170" sortable="custom">
                    <template #default="{ row }">
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-slate-800">
                                {{ formatDate(row?.created_at) }}
                            </p>

                            <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                                <i class="fa-regular fa-clock text-[11px]"></i>
                                {{ formatTime(row?.created_at) }}
                            </p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" width="245" fixed="right" align="center">
                    <template #default="{ row }">
                        <div class="flex items-center justify-center gap-2">
                            <el-button circle plain :loading="row?.loadingPdf" @click="downloadQuotePdf(row)"
                                v-tippy="'Descargar cotización PDF'" size="small" type="danger" class="!m-0">
                                <i class="fa-regular fa-file-pdf"></i>
                            </el-button>

                            <el-button circle plain :loading="row?.loading" @click="downloadQuoteExcel(row)"
                                v-tippy="'Descargar cotización Excel'" size="small" type="success" class="!m-0">
                                <i class="fa-regular fa-file-excel"></i>
                            </el-button>

                            <el-button v-if="!row?.order_service" circle plain @click="$router.push({
                                name: 'orders-services-create',
                                query: {
                                    quoteId: row.id
                                }
                            })" v-tippy="'Generar orden de servicio'" size="small" type="primary" class="!m-0">
                                <i class="fa-solid fa-flask-vial"></i>
                            </el-button>

                            <el-button circle plain @click="$router.push({
                                name: 'quote-update',
                                params: {
                                    id: row.id
                                }
                            })" v-tippy="'Editar cotización'" size="small" type="warning" class="!m-0">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </el-button>

                            <el-button circle plain @click="handleDelete(row)" v-tippy="'Eliminar cotización'"
                                size="small" type="danger" class="!m-0">
                                <i class="fa-regular fa-trash-can"></i>
                            </el-button>
                        </div>
                    </template>
                </el-table-column>

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
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ quotes.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getQuotes" />
        </div>
    </div>

    <confirm-dialog ref="confirmRef" />
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import tenant from '../../../stores/tenant';
import { useListStore } from '../../../stores/list';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';
import ImportItem from '../../../components/tenants/ImportItem.vue';
import { Search } from '@element-plus/icons-vue';
import { ElNotification } from 'element-plus';

const activeNames = ref(['1'])
const listStore = useListStore()

const confirmRef = ref(null)
const companies = computed(() => listStore.companies)
const comerciales = computed(() => listStore.comerciales)
const users = computed(() => listStore.users)

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const filters = ref({
    search: null,
    comercial_id: null,
    company_id: null,
    is_os: null
})

const loading = ref(false)
const quotes = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const getQuotes = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`quote?page=${page}`, {
            params: filters.value
        })

        if (data.data) {
            quotes.value = data.data.data
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

const downloadQuoteExcel = async (row) => {
    row.loading = true

    try {
        const response = await tenant.post(`/quote/export/${row.id}`, {}, {
            responseType: 'blob',
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'cotizacion.xlsx')
        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(url)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        row.loading = false
    }
}

const downloadQuotePdf = async (row) => {
    row.loadingPdf = true

    try {
        const response = await tenant.post(`/quote/pdf/${row.id}`, {}, {
            responseType: 'blob',
        })

        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', `cotizacion-${row.id}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(url)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        row.loadingPdf = false
    }
}

async function handleDelete(row) {
    const ok = await confirmRef.value?.open({
        title: 'Eliminar cotización',
        message: '¿Seguro que deseas eliminar la cotización?',
        confirmText: 'Sí, aceptar',
        cancelText: 'Cancelar',
    })
    if (ok) {
        row.loading = true

        try {
            const { data } = await tenant.delete(`quote/${row.id}`)
            ElNotification.success(data.message)
            getQuotes(pagination.value.current_page)
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            row.loading = false
        }
    }
}

watch(() => filters.value, (newVal) => {
    getQuotes()
}, { deep: true })

onMounted(async () => {
    await getQuotes()
    await listStore.getCompanies()
    await listStore.getUsers()
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

.lims-quotes-table :deep(.el-table__header th) {
    background: #f8fafc !important;
    color: #334155;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.045em;
}

.lims-quotes-table :deep(.el-table__cell) {
    padding: 15px 0;
}

.lims-quotes-table :deep(.el-table__row) {
    transition: background-color 0.2s ease;
}

.lims-quotes-table :deep(.el-table__row:hover > td) {
    background-color: #f0fdfa !important;
}

.lims-quotes-table :deep(.el-table--striped .el-table__body tr.el-table__row--striped td) {
    background-color: #fbfdff;
}

.lims-quotes-table :deep(.el-table__fixed-right) {
    box-shadow: -10px 0 22px rgba(15, 23, 42, 0.07);
}

.lims-quotes-table :deep(.el-button.is-circle) {
    width: 32px;
    height: 32px;
    border-radius: 11px;
}

.lims-quotes-table :deep(.el-loading-mask) {
    border-radius: 16px;
}

.lims-quotes-table :deep(.cell) {
    line-height: 1.35;
}
</style>
