<template>
    <custom-header title="Cotizaciones" description="Registro y control de cotizaciones." icon="fa-solid fa-receipt">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="$router.push('/quote-create')" type="primary"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <!-- <i class="fa-solid fa-file-invoice-dollar mr-2"></i> -->
                Agregar Registro
            </el-button>
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
                                Comercial
                            </label>

                            <el-select :remote-method="listStore.getUsers" filterable remote reserve-keyword clearable
                                v-model="filters.comercial_id" placeholder="Seleccionar comercial" class="!w-full">
                                <el-option v-for="row in users" :key="row.id" :label="row.full_name" :value="row.id" />
                            </el-select>
                        </div>

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
                                Orden de servicio
                            </label>

                            <div
                                class="flex h-10 items-center justify-between rounded-xl border border-slate-200 bg-white px-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-500">
                                        <i class="fa-solid fa-flask-vial text-xs"></i>
                                    </span>

                                    <span class="text-sm font-medium text-slate-600">
                                        OS generada
                                    </span>
                                </div>

                                <el-switch v-model="filters.is_os" />
                            </div>
                        </div>
                    </div>
                </template>
            </el-collapse-item>
        </el-collapse>

        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="quotes"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center" fixed="left">
                    <template #header>N°</template>
                </el-table-column>
                <el-table-column width="300">
                    <template #header>Cliente</template>
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
                        <p class="truncate font-semibold" v-tippy="row?.applicant?.business_name">{{
                            row?.applicant?.business_name }}</p>
                        <p>
                            RUC: {{ row?.applicant?.ruc }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>Comercial</template>
                    <template #default="{ row }">
                        <div class="flex items-center gap-3 py-1">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                                <i class="fa-solid fa-user-tie text-[10px]"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="line-clamp-2 text-xs font-semibold">
                                    {{ row.user?.full_name ?? '-' }}
                                </p>
                                <p class="text-[11px] font-medium text-slate-400">
                                    Comercial asignado
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>Orden de servicio</template>
                    <template #default="{ row }">
                        <div class="py-1">
                            <span v-if="row?.order_service"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                <span
                                    class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-white">
                                    <i class="!m-0 !p-0 fa-solid fa-check text-[9px]"></i>
                                </span>
                                Generada
                            </span>

                            <span v-else
                                class="inline-flex items-center gap-1.5 rounded-xl bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-700">
                                <span
                                    class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-white">
                                    <i class="!m-0 !p-0 fa-regular fa-alarm-clock text-[9px]"></i>
                                </span>
                                Pendiente
                            </span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="220">
                    <template #header>Contacto</template>
                    <template #default="{ row }">
                        <el-popover placement="top" :width="360" trigger="hover">
                            <template #default>
                                <div class="rounded-2xl bg-white p-3">
                                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                                            <i class="fa-solid fa-address-book text-base"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h4 class="truncate text-sm font-bold text-slate-800">
                                                {{ row.contact?.user?.full_name || 'Sin nombre' }}
                                            </h4>

                                            <span
                                                class="mt-1 inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">
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
                                <el-button type="primary" plain v-tippy="'Ver información del contacto'"
                                    class="!rounded-xl font-semibold">
                                    <i class="fa-solid fa-user-check text-[11px] me-2"></i>

                                    <span class="max-w-[160px] truncate !text-xs">
                                        {{ row.contact?.user?.full_name || 'Sin contacto' }}
                                    </span>
                                </el-button>
                            </template>
                        </el-popover>
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
                            <button v-if="!row?.order_service" type="button" @click="$router.push({
                                name: 'orders-services-create',
                                query: {
                                    quoteId: row.id
                                }
                            })" v-tippy="'Generar orden de servicio'"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-cyan-500 text-white shadow-sm shadow-cyan-600/20 transition hover:bg-cyan-700 active:scale-95">
                                <i class="fa-solid fa-flask-vial text-sm"></i>
                            </button>

                            <el-dropdown trigger="click" placement="bottom-end">
                                <button type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                </button>

                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item @click="downloadQuotePdf(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-file-pdf"></i>
                                                <span>Descargar PDF</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item @click="downloadQuoteExcel(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-file-excel"></i>
                                                <span>Descargar Excel</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item @click="$router.push({
                                            name: 'quote-update',
                                            params: {
                                                id: row.id
                                            }
                                        })">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                                <span>Editar cotización</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item divided @click="handleDelete(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-trash-can"></i>
                                                <span>Eliminar cotización</span>
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
import CustomHeader from '../../../components/tenants/CustomHeader.vue';

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
    is_os: null,
    application_id: null
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

<style>
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
