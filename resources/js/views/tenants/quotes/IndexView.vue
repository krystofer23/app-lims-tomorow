<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Cotizaciones</h2>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end w-full lg:w-auto">
                        <el-input v-model="filters.q" clearable size="default"
                            placeholder="Buscar por nombre, código, correo..." class="w-full sm:w-[320px]">
                            <template #prefix>
                                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                            </template>
                        </el-input>

                        <div class="flex gap-3">
                            <el-button type="primary" size="default" class="!rounded-xl"
                                @click="$router.push('/quote-create')">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Nuevo
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
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

                <el-table stripe :data="quotes" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName">
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
                            <!-- <span class="rounded-md py-0.5 px-1.5 text-xs font-medium text-white bg-[#1abc9c]">Si</span> -->
                            <span class="rounded-md py-0.5 px-1.5 text-xs font-medium text-white bg-[#e7515a]">No</span>
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
                                    <el-button size="small" type="primary" plain
                                        v-tippy="'Ver información del contacto'"
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

                    <el-table-column prop="created_at" label="Creado" min-width="170" sortable="custom">
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

                                    <el-button @click="$router.push({
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

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Mostrando <span class="font-semibold text-slate-700">{{ quotes.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @change="getQuotes" />
                </div>
            </div>
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

const activeNames = ref(['1'])
const listStore = useListStore()

const confirmRef = ref(null)
const companies = computed(() => listStore.companies)
const comerciales = computed(() => listStore.comerciales)

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

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

<style scoped></style>