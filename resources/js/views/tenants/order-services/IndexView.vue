<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4 shadow-sm lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-clipboard-list text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            Ordenes de Servicio
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Registro y control de ordenes de servicio.
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

            <el-button @click="$router.push({ name: 'orders-services-create' })" type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <i class="fa-solid fa-clipboard-list mr-2"></i>
                Generar OS
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

        <div class="overflow-x-auto">
            <el-table :data="orders" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                :row-class-name="rowClassName" stripe>
                <el-table-column type="index" label="#" width="60" />

                <el-table-column label="Empresa">
                    <template #default="{ row }">
                        <p>{{ row.company?.business_name }}</p>
                        <span class="block text-xs font-medium">
                            RUC: {{ row.company?.ruc }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Elaborado por">
                    <template #default="{ row }">
                        {{ row?.user?.full_name }}
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

                <el-table-column label="Acciones" width="190" fixed="right">
                    <template #default="{ row }">
                        <el-button-group>
                            <el-button :loading="row?.loadingPdf" type="primary" size="small" v-tippy="'Generar PDF'"
                                @click="downloadOrderServicePdf(row)">
                                <i class="fa-regular fa-file-pdf"></i>
                            </el-button>
                            <el-button :loading="row?.loadingExcel" type="success" size="small"
                                v-tippy="'Generar Excel'" @click="downloadOrderServiceExcel(row)">
                                <i class="fa-regular fa-file-excel"></i>
                            </el-button>
                            <el-button type="warning" size="small" v-tippy="'Editar'" @click="onEdit(row)">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </el-button>
                            <el-button type="danger" size="small" v-tippy="'Eliminar'" @click="onDelete(row)">
                                <i class="fa-regular fa-trash-can"></i>
                            </el-button>
                        </el-button-group>
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

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ orders.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @update:current-page="getOrders" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { ElMessage, ElMessageBox } from 'element-plus';
import tenant from '../../../stores/tenant';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import { Search } from '@element-plus/icons-vue';

const router = useRouter()
const orders = ref([])
const loading = ref(false)
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const filters = reactive({
    q: null,
});

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

const onEdit = (row) => {
    router.push({
        name: 'orders-services-update',
        params: { id: row.id }
    })
}

const downloadOrderServiceExcel = async (row) => {
    row.loadingExcel = true

    try {
        const response = await tenant.post(`/order-service/export/${row.id}`, {}, {
            responseType: 'blob',
        })

        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', `orden-servicio-${row.code ?? row.id}.xlsx`)
        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(url)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        row.loadingExcel = false
    }
}

const downloadOrderServicePdf = async (row) => {
    row.loadingPdf = true

    try {
        const response = await tenant.post(`/order-service/pdf/${row.id}`, {}, {
            responseType: 'blob',
        })

        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', `orden-servicio-${row.code ?? row.id}.pdf`)
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

const onDelete = async (row) => {
    try {
        await ElMessageBox.confirm(
            `¿Deseas eliminar la orden de servicio${row?.code ? ` ${row.code}` : ''}?`,
            'Confirmación',
            {
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
            }
        )

        loading.value = true
        const { data } = await tenant.delete(`order-service/${row.id}`)
        ElMessage.success(data.message || 'Orden de servicio eliminada correctamente')
        await getOrders()
    }
    catch (e) {
        if (e === 'cancel' || e === 'close') return

        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const getOrders = async () => {
    loading.value = true

    try {
        const { data } = await tenant.get(`order-service`)

        if (data.data) {
            orders.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total,
            }
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

onMounted(() => {
    getOrders()
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
