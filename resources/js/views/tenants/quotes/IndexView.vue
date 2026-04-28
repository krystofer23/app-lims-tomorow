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
                <i class="fa-regular fa-file-lines mr-2"></i>
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
                    </div>
                </template>
            </el-collapse-item>
        </el-collapse>

        <div class="overflow-x-auto">
            <el-table stripe :data="quotes" v-loading="loading" class="w-full">
                <el-table-column type="index" label="#" width="60" />

                <el-table-column label="Empresa">
                    <template #default="{ row }">
                        <p>{{ row.company?.business_name }}</p>
                        <span class="block text-xs font-medium">
                            RUC: {{ row.company?.ruc }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Comercial">
                    <template #default="{ row }">
                        <p>{{ row.user?.full_name }}</p>
                    </template>
                </el-table-column>

                <el-table-column label="OS Generada">
                    <template #default="{ row }">
                        <span v-if="row?.order_service"
                            class="rounded-md py-0.5 px-1.5 text-xs font-medium text-white bg-[#1abc9c]">Si</span>
                        <span v-else
                            class="rounded-md py-0.5 px-1.5 text-xs font-medium text-white bg-[#e7515a]">No</span>
                    </template>
                </el-table-column>

                <el-table-column label="Contacto">
                    <template #default="{ row }">
                        <el-popover placement="top" :width="320" trigger="hover">
                            <template #default>
                                <div class="p-1">
                                    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <i class="fa-solid fa-user text-sm"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h4 class="truncate text-sm font-semibold text-slate-800">
                                                {{ row.contact?.user?.full_name || 'Sin nombre' }}
                                            </h4>

                                            <span
                                                class="mt-1 inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                                {{ row.contact?.type || 'Sin tipo' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-start gap-2 rounded-lg bg-slate-50 px-3 py-2">
                                            <i class="fa-solid fa-envelope mt-0.5 text-xs text-slate-500"></i>
                                            <div class="min-w-0">
                                                <p
                                                    class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                                    Correo
                                                </p>
                                                <p class="break-all text-sm text-slate-700">
                                                    {{ row.contact?.email || 'No registrado' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-2 rounded-lg bg-slate-50 px-3 py-2">
                                            <i class="fa-solid fa-phone mt-0.5 text-xs text-slate-500"></i>
                                            <div class="min-w-0">
                                                <p
                                                    class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                                    Teléfono
                                                </p>
                                                <p class="text-sm text-slate-700">
                                                    {{ row.contact?.phone || 'No registrado' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template #reference>
                                <el-button size="small" type="primary" plain v-tippy="'Ver información del contacto'"
                                    class="inline-flex max-w-full items-center gap-2 rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md">
                                    <i class="fa-solid fa-address-book text-xs me-2"></i>
                                    <span class="max-w-[150px] truncate">
                                        {{ row.contact?.user?.full_name || 'Sin contacto' }}
                                    </span>
                                </el-button>
                            </template>
                        </el-popover>
                    </template>
                </el-table-column>

                <el-table-column prop="created_at" label="Creado" sortable="custom">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <p class="text-slate-900 font-medium">{{ formatDate(row?.created_at) }}</p>
                            <p class="text-slate-500 text-xs">{{ formatTime(row?.created_at) }}</p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" width="200" fixed="right">
                    <template #default="{ row }">
                        <div class="flex justify-start gap-2">
                            <el-button-group>
                                <el-button :loading="row?.loadingPdf" @click="downloadQuotePdf(row)"
                                    v-tippy="'Generar PDF'" size="small" type="primary">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </el-button>

                                <el-button :loading="row?.loading" @click="downloadQuoteExcel(row)"
                                    v-tippy="'Generar Excel'" size="small" type="success">
                                    <i class="fa-regular fa-file-excel"></i>
                                </el-button>

                                <el-button v-if="!row?.order_service" @click="$router.push({
                                    name: 'orders-services-create', query: {
                                        quoteId: row.id
                                    }
                                })" v-tippy="'Generar OS'" size="small" type="info">
                                    <i class="fa-regular fa-file-zipper"></i>
                                </el-button>

                                <el-button @click="() => {
                                    $router.push({
                                        name: 'quote-update',
                                        params: {
                                            id: row.id
                                        },
                                    })
                                }" v-tippy="'Editar'" size="small" type="warning">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </el-button>

                                <el-button @click="handleDelete" v-tippy="'Eliminar'" size="small" type="danger">
                                    <i class="fa-regular fa-trash-can"></i>
                                </el-button>
                            </el-button-group>
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
                        <el-button class="mt-4 !rounded-xl">Limpiar filtros</el-button>
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
import { computed, onMounted, ref } from 'vue';
import tenant from '../../../stores/tenant';
import { useListStore } from '../../../stores/list';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';
import ImportItem from '../../../components/tenants/ImportItem.vue';
import { Search } from '@element-plus/icons-vue';

const activeNames = ref(['1'])
const listStore = useListStore()

const confirmRef = ref(null)
const companies = computed(() => listStore.companies)
const comerciales = computed(() => listStore.comerciales)

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const filters = ref({
    q: null,
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
        const { data } = await tenant.get(`quote?page=${page}`)

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

onMounted(async () => {
    await getQuotes()
    await listStore.getCompanies()
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
