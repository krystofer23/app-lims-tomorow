<template>
    <custom-header :title="title" description="Registro y control de resultados." icon="fa-solid fa-flask-vial">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
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

        <div class="inline-flex w-full rounded-2xl border border-slate-200 bg-slate-100 p-1 shadow-sm">
            <button type="button" :class="tab === 'orders'
                ? 'bg-teal-400 text-white shadow-sm ring-1 ring-slate-200'
                : 'text-slate-500 hover:bg-white/60 hover:text-slate-700'"
                class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200"
                @click="tab = 'orders'">
                <i class="fa-solid fa-clipboard-list text-xs"></i>
                Órdenes
            </button>

            <button type="button" :class="tab === 'attended'
                ? 'bg-teal-400 text-white shadow-sm ring-1 ring-slate-200'
                : 'text-slate-500 hover:bg-white/60 hover:text-slate-700'"
                class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200"
                @click="tab = 'attended'">
                <i class="fa-solid fa-user-check text-xs"></i>
                Atendidos
            </button>
        </div>
        <div class="overflow-x-auto">
            <el-table v-if="tab === 'orders'" class="border rounded-xl" v-loading="loading" stripe :data="orders"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center" fixed="left">
                    <template #header>N°</template>
                </el-table-column>
                <el-table-column label="OS">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row.code }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Empresa" width="200">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row?.company?.business_name }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Solicitante" width="200">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row?.application?.business_name }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Tipos de muestra" width="200">
                    <template #default="{ row }">
                        <el-popover placement="top" :width="360" trigger="hover">
                            <template #default>
                                <div class="rounded-2xl bg-white p-3">
                                    <p class="mb-3 text-xs font-bold uppercase text-slate-500">
                                        Tipos de muestra
                                    </p>

                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="sample in row.items_grouped" :key="sample.type_of_sample_id"
                                            class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-700 ring-1 ring-teal-100">
                                            <i class="fa-solid fa-vial-circle-check text-[11px]"></i>
                                            {{ sample.type_of_sample }}

                                            <span class="rounded-full bg-white px-2 py-0.5 text-[10px] text-teal-600">
                                                {{ sample.items.length }}
                                            </span>
                                        </span>
                                    </div>

                                    <p v-if="!row.items_grouped?.length" class="text-xs text-slate-400">
                                        No hay tipos de muestra registrados.
                                    </p>
                                </div>
                            </template>

                            <template #reference>
                                <el-button v-tippy="'Ver tipos de muestra'" plain>
                                    <i class="fa-solid fa-eye"></i>
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
                <el-table-column fixed="right" label="Acciones">
                    <template #default="{ row }">
                        <el-button @click="$router.push('laboratory-show?orderId=' + row.id)"
                            v-tippy="'Agregar resultados'" type="warning" plain size="small">
                            <i class="fa-solid fa-vial-circle-check"></i>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div v-if="tab === 'orders'"
                class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Mostrando <span class="font-semibold text-slate-700">{{ orders.length }}</span> de
                    <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
                </p>

                <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                    v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                    :page-sizes="[10, 20, 50, 100]" @change="listStore.getOrderServices()" />
            </div>

            <el-table v-if="tab === 'attended'" class="border rounded-xl" v-loading="loading_attended" stripe
                :data="orders_attended" header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center" fixed="left">
                    <template #header>N°</template>
                </el-table-column>
                <el-table-column label="OS">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row.code }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Empresa" width="200">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row?.company?.business_name }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Solicitante" width="200">
                    <template #default="{ row }">
                        <p class="font-medium">{{ row?.application?.business_name }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="Tipos de muestra" width="200">
                    <template #default="{ row }">
                        <el-popover placement="top" :width="360" trigger="hover">
                            <template #default>
                                <div class="rounded-2xl bg-white p-3">
                                    <p class="mb-3 text-xs font-bold uppercase text-slate-500">
                                        Tipos de muestra
                                    </p>

                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="sample in row.items_grouped" :key="sample.type_of_sample_id"
                                            class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-700 ring-1 ring-teal-100">
                                            <i class="fa-solid fa-vial-circle-check text-[11px]"></i>
                                            {{ sample.type_of_sample }}

                                            <span class="rounded-full bg-white px-2 py-0.5 text-[10px] text-teal-600">
                                                {{ sample.items.length }}
                                            </span>
                                        </span>
                                    </div>

                                    <p v-if="!row.items_grouped?.length" class="text-xs text-slate-400">
                                        No hay tipos de muestra registrados.
                                    </p>
                                </div>
                            </template>

                            <template #reference>
                                <el-button v-tippy="'Ver tipos de muestra'" plain>
                                    <i class="fa-solid fa-eye"></i>
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
                <el-table-column fixed="right" label="Acciones">
                    <template #default="{ row }">
                        <el-button-group>
                            <el-button @click="$router.push('laboratory-show?orderId=' + row.id)"
                                v-tippy="'Editar resultados'" type="warning" plain size="small">
                                <i class="fa-solid fa-vial-circle-check"></i>
                            </el-button>
                            <el-button v-tippy="'Eliminar resultados'" type="danger" plain size="small">
                                <i class="fa-regular fa-trash-can"></i>
                            </el-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>

            <div v-if="tab === 'attended'"
                class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Mostrando <span class="font-semibold text-slate-700">{{ orders_attended.length }}</span> de
                    <span class="font-semibold text-slate-700">{{ paginationAttended.total }}</span> registros
                </p>

                <el-pagination background layout="prev, pager, next, sizes" :total="paginationAttended.total"
                    v-model:page-size="paginationAttended.per_page"
                    v-model:current-page="paginationAttended.current_page" :page-sizes="[10, 20, 50, 100]"
                    @change="listStore.getOrderServices()" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption.js';
import tenant from '../../../stores/tenant.js';
import { useListStore } from '../../../stores/list.js';
import { useRoute } from 'vue-router';

const filters = ref({
    search: null,
    application_id: null,
    company_id: null,
    order_id: null
})

const activeNames = ref(['1'])
const loading = ref(false)
const loading_attended = ref(false)
const orders = ref([])
const orders_attended = ref([])

const listStore = useListStore()
const companies = computed(() => listStore.companies)
const ordersOptimizate = computed(() => listStore.ordersOptimizate)

const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0
})

const paginationAttended = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0
})

const getOrder = async () => {
    loading.value = true

    try {
        const { data } = await tenant.get(`lab-orders`, {
            params: {
                is_attended: false,
                ...filters.value
            }
        })

        if (data.data) {
            orders.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total
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

const getOrderAttended = async () => {
    loading_attended.value = true

    try {
        const { data } = await tenant.get(`lab-orders`, {
            params: {
                is_attended: true,
                ...filters.value
            }
        })

        if (data.data) {
            orders_attended.value = data.data.data
            paginationAttended.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total
            }
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading_attended.value = false
    }
}

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const tab = ref('orders')

watch(() => filters.value, async () => {
    if (tab.value === 'orders') {
        await getOrder()
    } else {
        await getOrderAttended()
    }
}, { deep: true })

watch(tab, async (newTab) => {
    if (newTab === 'orders') {
        await getOrder()
    } else {
        await getOrderAttended()
    }
})

const title = ref('')
const route = useRoute()

watch(() => route.path, (newVal) => {
    if (route.path === '/operations') { title.value = 'Operaciones' }
    if (route.path === '/laboratory') { title.value = 'Laboratorio' }
})

onMounted(async () => {
    await getOrder()
    await getOrderAttended()
    await listStore.getCompanies()
    await listStore.getOrdersOptimizate()
})
</script>

<style scoped>
:deep(.lims-table-header) {
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
