<template>
    <custom-header title="Ordenes de Servicio" description="Registro y control de ordenes de servicio."
        icon="fa-solid fa-clipboard-list">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[360px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="$router.push({ name: 'orders-services-create' })" type="primary"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <!-- <i class="fa-solid fa-clipboard-list mr-2"></i> -->
                Generar OS
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
                    <template #header>Elaborado por</template>
                    <template #default="{ row }">
                        <div class="flex items-center gap-3 py-1">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                                <i class="fa-solid fa-user-tie text-[10px]"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="line-clamp-2 text-xs font-semibold">
                                    {{ row?.user?.full_name ?? '-' }}
                                </p>
                            </div>
                        </div>
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
                    <template #header>Contacto</template>
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
                                                {{ row.contact_company?.user?.full_name || 'Sin nombre' }}
                                            </h4>

                                            <span
                                                class="mt-1 inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                                {{ row.contact_company?.type || 'Sin tipo' }}
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
                                                    {{ row.contact_company?.email || 'No registrado' }}
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
                                                    {{ row.contact_company?.phone || 'No registrado' }}
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
                                        {{ row.contact_company?.user?.full_name || 'Sin contacto' }}
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
                            <el-dropdown trigger="click" placement="bottom-end">
                                <button type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                </button>

                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item @click="downloadOrderServicePdf(row)">
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

                                        <el-dropdown-item @click="onEdit(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                                <span>Editar orden</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item divided @click="onDelete(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-trash-can"></i>
                                                <span>Eliminar orden</span>
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
                :page-sizes="[10, 20, 50, 100]" @change="getOrders" />
        </div>
    </div>

    <confirm-dialog ref="confirmRef" />
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { ElMessage, ElMessageBox } from 'element-plus';
import tenant from '../../../stores/tenant';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import { Search } from '@element-plus/icons-vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { useListStore } from '../../../stores/list';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';

const activeNames = ref(['1'])

const listStore = useListStore()
const router = useRouter()
const orders = ref([])
const loading = ref(false)
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const confirmRef = ref(null)

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
    const ok = await confirmRef.value?.open({
        title: 'Eliminar orden de servicio',
        message: '¿Seguro que deseas eliminar la orden?',
        confirmText: 'Sí, aceptar',
        cancelText: 'Cancelar',
    })
    if (ok) {
        row.loading = true

        try {
            const { data } = await tenant.delete(`order-service/${row.id}`)
            ElMessage.success(data.message)
            getOrders()
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            row.loading = false
        }
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
