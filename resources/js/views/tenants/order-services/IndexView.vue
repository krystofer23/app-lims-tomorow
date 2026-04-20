<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Ordenes de Servicio</h2>
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
                                @click="$router.push({ name: 'orders-services-create' })">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Generar OS
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
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

                    <el-table-column label="Acciones" width="160" fixed="right">
                        <template #default="{ row }">
                            <el-button-group>
                                <el-button :loading="row?.loadingPdf" type="primary" size="small"
                                    v-tippy="'Generar PDF'" @click="downloadOrderServicePdf(row)">
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
        </div>
    </div>

</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { ElMessage, ElMessageBox } from 'element-plus';
import tenant from '../../../stores/tenant';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';

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