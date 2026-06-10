<template>
    <el-dialog top="2vh" :model-value="showMatrixModal" class="!rounded-2xl lims-dialog"
        :style="{ width: computedDialogWidth, maxWidth: '95%' }" @close="handleClose">
        <template #header>
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-200">
                <div class="flex items-start gap-3">
                    <div
                        class="flex items-center justify-center w-12 h-12 rounded-2xl bg-cyan-50 border border-cyan-100">
                        <i class="fa-solid fa-flask-vial text-cyan-600 text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-800">
                            Catálogo de ensayos LIMS
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Busca y selecciona ensayos por acreditación, matriz, metodología y unidad de medida.
                        </p>

                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                <i class="fa-solid fa-database text-slate-400"></i>
                                {{ pagination.total }} registros
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                <i class="fa-solid fa-certificate text-emerald-500"></i>
                                Catálogo técnico
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                <i class="fa-solid fa-layer-group text-blue-500"></i>
                                Matrices ambientales
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <section class="mt-5 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 via-white to-slate-50 px-5 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <i class="fa-solid fa-filter text-sm"></i>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold tracking-tight text-slate-800">
                                Filtros de búsqueda
                            </h4>
                            <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                                Refina los resultados para encontrar el ensayo correcto.
                            </p>
                        </div>
                    </div>

                    <el-button size="small" class="!rounded-xl" plain @click="clearFilters">
                        <i class="fa-solid fa-filter-circle-xmark mr-2"></i>
                        Limpiar filtros
                    </el-button>
                </div>
            </div>

            <div class="bg-slate-50/60 p-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-certificate text-[10px] text-blue-500"></i>
                            Acreditación
                        </label>

                        <el-select v-model="filters.condition" clearable filterable class="w-full"
                            placeholder="Seleccionar acreditación">
                            <el-option v-for="row in conditions" :key="row.id" class="!uppercase" :value="row.id"
                                :label="row.description" />
                        </el-select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-layer-group text-[10px] text-indigo-500"></i>
                            Tipo
                        </label>

                        <el-select v-model="filters.type" clearable filterable class="w-full"
                            placeholder="Seleccionar tipo">
                            <el-option v-for="row in typesItems" :key="row" :value="row" :label="row" />
                        </el-select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-flask-vial text-[10px] text-emerald-500"></i>
                            Producto
                        </label>

                        <el-select v-model="filters.product" clearable filterable class="w-full"
                            placeholder="Seleccionar producto">
                            <el-option v-for="row in typesSampling" :key="row.id" :value="row.id"
                                :label="row.description" />
                        </el-select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-table-cells-large text-[10px] text-amber-500"></i>
                            Matriz
                        </label>

                        <el-select v-model="filters.matrix" clearable filterable class="w-full"
                            placeholder="Seleccionar matriz">
                            <el-option v-for="row in matrixs" :key="row.id" :value="row.id" :label="row.description" />
                        </el-select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-microscope text-[10px] text-purple-500"></i>
                            Tipo de análisis
                        </label>

                        <el-select v-model="filters.type_of_analysis" clearable filterable class="w-full"
                            placeholder="Seleccionar análisis">
                            <el-option v-for="row in typesAnalysis" :key="row.id" :value="row.id"
                                :label="row.description" />
                        </el-select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            <i class="fa-solid fa-microscope text-[10px] text-purple-500"></i>
                            Parametro
                        </label>

                        <input v-model="filters.param" clearable class="w-full"
                            placeholder="Escribir..." />
                    </div>

                    <div class="flex items-end">
                        <el-button type="primary" :loading="loading"
                            class="!h-[32px] !w-full !rounded-xl !font-semibold" @click="getItem()">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>
                            Buscar
                        </el-button>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-4 rounded-2xl border border-slate-200 bg-white overflow-hidden">
            <div
                class="px-4 py-3 border-b border-slate-200 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h4 class="text-sm font-bold text-slate-700">
                        Resultados del catálogo
                    </h4>
                    <p class="text-xs text-slate-500">
                        Página {{ pagination.current_page || 1 }} de {{ pagination.last_page || 1 }}
                    </p>
                </div>

                <div class="text-xs text-slate-500">
                    Mostrando
                    <span class="font-bold text-slate-700">
                        {{ itemsData.length }}
                    </span>
                    de
                    <span class="font-bold text-slate-700">
                        {{ pagination.total }}
                    </span>
                    registros
                </div>
            </div>

            <el-table :data="itemsData" v-loading="loading" stripe highlight-current-row class="lims-table"
                header-cell-class-name="!bg-slate-50 !text-slate-600 !font-bold !text-xs uppercase"
                cell-class-name="!text-slate-700 !text-sm" :row-class-name="tableRowClassName" @row-click="toggleItem">
                <template #empty>
                    <div class="py-14 text-center">
                        <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-vial-circle-check text-slate-400 text-xl"></i>
                        </div>

                        <p class="font-semibold text-slate-600">
                            No se encontraron ensayos
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            Prueba limpiando los filtros o seleccionando otra matriz.
                        </p>
                    </div>
                </template>

                <el-table-column label="" width="60" align="center">
                    <template #default="{ row }">
                        <div class="mx-auto w-7 h-7 rounded-full flex items-center justify-center border transition-all"
                            :class="isSelected(row)
                                ? 'bg-cyan-600 border-cyan-600 text-white'
                                : 'bg-white border-slate-300 text-slate-400'">
                            <i class="fa-solid" :class="isSelected(row) ? 'fa-check' : 'fa-plus'"></i>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Acreditación" min-width="120">
                    <template #default="{ row }">
                        <el-tag size="small" effect="light" :type="row?.condition?.description ? 'success' : 'info'"
                            class="!rounded-full !font-bold">
                            {{ row?.condition?.description || 'No indica' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Parametro" min-width="340">
                    <template #default="{ row }">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-mono font-bold">
                            {{ row.parameter.description || 'No indica' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Ensayo" min-width="145">
                    <template #default="{ row }">
                        <span class="inline-flex items-center px-2.5 py-1 text-slate-700 text-xs font-mono font-bold">
                            {{ row?.reference?.code || 'No indica' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Metodología" min-width="340" show-overflow-tooltip>
                    <template #default="{ row }">
                        <div>
                            <p class="font-medium text-slate-700 line-clamp-1">
                                {{ row?.reference?.title || 'No indica' }}
                            </p>

                            <p v-if="row?.type" class="text-xs text-slate-400 mt-0.5">
                                Tipo: {{ row.type }}
                            </p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Unidad" min-width="140" align="center">
                    <template #default="{ row }">
                        <span
                            class="inline-flex items-center justify-center text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                            {{ row?.unit_measurement?.description || row?.unitMeasurement?.description || 'No indica' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="LCM" min-width="110" align="center">
                    <template #default="{ row }">
                        <span class="font-bold text-slate-700">
                            {{ row?.lcm || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Precio" fixed="right" min-width="140" align="right">
                    <template #default="{ row }">
                        <span class="font-semibold">
                            {{ formatMoney(row?.unit_price) }}
                        </span>
                    </template>
                </el-table-column>
            </el-table>

            <div
                class="px-4 py-3 border-t border-slate-200 bg-slate-50 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500">
                    Página
                    <span class="font-bold text-slate-700">
                        {{ pagination.current_page || 1 }}
                    </span>
                    de
                    <span class="font-bold text-slate-700">
                        {{ pagination.last_page || 1 }}
                    </span>
                </div>

                <el-pagination v-model:current-page="pagination.current_page" :page-size="pagination.per_page || 15"
                    :total="pagination.total" layout="prev, pager, next" background small
                    @current-change="handlePageChange" />
            </div>
        </section>

        <template #footer>
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-between pt-2">
                <p class="text-xs text-slate-400">
                    Sistema LIMS · Catálogo de matrices y ensayos
                </p>

                <div class="flex justify-end gap-2">
                    <el-button class="!rounded-xl" @click="handleClose">
                        Cerrar
                    </el-button>

                    <el-button type="primary" class="!rounded-xl" @click="handleClose">
                        <i class="fa-solid fa-check mr-2"></i>
                        Confirmar selección ({{ props.items.length }})
                    </el-button>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import tenant from '../../../../stores/tenant'
import { useListStore } from '../../../../stores/list'
import { useWindowSize } from '@vueuse/core'
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption'
import { ElNotification } from 'element-plus'

const { width: windowWidth } = useWindowSize()

const computedDialogWidth = computed(() => {
    if (windowWidth.value <= 576) return '94%'
    if (windowWidth.value <= 768) return '90%'
    if (windowWidth.value <= 992) return '86%'
    if (windowWidth.value <= 1200) return '82%'
    return '78%'
})

const props = defineProps({
    showMatrixModal: {
        type: Boolean,
        default: false,
    },
    items: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['close', 'update:items'])

const listStore = useListStore()

const loading = ref(false)
const itemsData = ref([])

const typesItems = computed(() => listStore.typesItems)
const conditions = computed(() => listStore.conditions)
const typesSampling = computed(() => listStore.typesSampling)
const typesAnalysis = computed(() => listStore.typesAnalysis)
const matrixs = computed(() => {
    const data = listStore.matrixs ?? []

    if (!filters.product) {
        return data
    }

    return data.filter(
        m => Number(m.type_of_sample_id) === Number(filters.product)
    )
})

const filters = reactive({
    matrix: null,
    product: null,
    condition: null,
    type_of_analysis: null,
    type: null,
    param: null
})

const pagination = ref({
    current_page: 1,
    last_page: 0,
    per_page: 15,
    total: 0,
})

const getItem = async (page = 1) => {
    if (!filters.type && !filters.product) {
        ElNotification.warning('Debe de ingresar el tipo o producto para poder filtrar')
        return
    }

    loading.value = true

    try {
        const { data } = await tenant.get('items', {
            params: {
                page,
                ...filters
            },
        })

        if (data.data) {
            itemsData.value = data.data.data || []

            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: Number(data.data.per_page),
                total: data.data.total,
            }
        }
    } catch (e) {
        handleErrorsExeption(e)
    } finally {
        loading.value = false
    }
}

const formatMoney = (value) => {
    return `S/ ${Number(value || 0).toFixed(2)}`
}

const handlePageChange = (page) => {
    getItem(page)
}

const clearFilters = () => {
    filters.matrix = null
    filters.product = null
    filters.condition = null
    filters.type_of_analysis = null
    filters.type = null
    filters.param = null

    itemsData.value = []
}

const isSelected = (row) => {
    if (!Array.isArray(props.items)) return false

    return props.items.some((item) => item.id === row.id)
}

const toggleItem = (row) => {
    const items = props.items ?? []
    const exists = isSelected(row)

    if (exists) {
        const newItems = items.filter((item) => item.id !== row.id)

        emit('update:items', newItems)

        return
    }

    emit('update:items', [
        ...items,
        {
            ...row,
            price: 0.00,
            number_samples: 1,
            type: filters.type,
            type_of_sample_filter: filters.product,
            matrix_filter: filters.matrix
        }
    ])
}

const tableRowClassName = ({ row }) => {
    return isSelected(row) ? 'selected-row' : ''
}

const handleClose = () => {
    clearFilters()
    emit('close')
}

watch(() => filters.matrix, () => {
    listStore.getTypesAnalysis(filters)
})

onMounted(async () => {
    await listStore.getTypesItems()
    await listStore.getConditions()
    await listStore.getTypesSampling()
    await listStore.getTypesAnalysis()
    await listStore.getMatrixs()
})
</script>

<style scoped>
:deep(.lims-dialog .el-dialog__header) {
    margin-right: 0;
    padding-bottom: 0;
}

:deep(.lims-dialog .el-dialog__body) {
    padding-top: 8px;
}

:deep(.lims-table) {
    width: 100%;
}

:deep(.lims-table .el-table__cell) {
    padding: 11px 0;
}

:deep(.lims-table .el-table__row:hover td) {
    background-color: #f0fdfa !important;
}

:deep(.el-pagination.is-background .el-pager li.is-active) {
    background-color: #0891b2;
}

:deep(.selected-row td) {
    background-color: #ecfeff !important;
}

:deep(.selected-row:hover td) {
    background-color: #cffafe !important;
}

:deep(.selected-row td:first-child) {
    border-left: 4px solid #0891b2 !important;
}

:deep(.lims-table .el-table__row) {
    cursor: pointer;
}

:deep(.el-select__wrapper) {
    border-radius: 12px !important;
}
</style>
