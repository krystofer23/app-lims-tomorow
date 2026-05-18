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

        <section class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h4 class="text-sm font-bold text-slate-700">
                        Filtros de búsqueda
                    </h4>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Refina los resultados para encontrar el ensayo correcto.
                    </p>
                </div>

                <el-button size="small" class="!rounded-xl" plain @click="clearFilters">
                    <i class="fa-solid fa-filter-circle-xmark mr-2"></i>
                    Limpiar filtros
                </el-button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Acreditación
                    </label>

                    <el-select v-model="filters.condition" clearable filterable class="w-full"
                        placeholder="Seleccionar acreditación">
                        <el-option v-for="row in conditions" :key="row.id" class="!uppercase" :value="row.id"
                            :label="row.description" />
                    </el-select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Tipo
                    </label>

                    <el-select v-model="filters.type" clearable filterable class="w-full"
                        placeholder="Seleccionar tipo">
                        <el-option v-for="row in types" :key="row" class="!uppercase" :value="row" :label="row" />
                    </el-select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Matriz
                    </label>

                    <el-select v-model="filters.matrix" clearable filterable class="w-full"
                        placeholder="Seleccionar matriz">
                        <el-option v-for="row in matrixs" :key="row.id" class="!uppercase" :value="row.id"
                            :label="row.description" />
                    </el-select>
                </div>

                <!-- <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Categoria
                    </label>

                    <el-select v-model="filters.matrix" clearable filterable class="w-full"
                        placeholder="Seleccionar matriz">
                        <el-option v-for="row in matrixs" :key="row.id" class="!uppercase" :value="row.id"
                            :label="row.description" />
                    </el-select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Sub Categoria
                    </label>

                    <el-select v-model="filters.matrix" clearable filterable class="w-full"
                        placeholder="Seleccionar matriz">
                        <el-option v-for="row in matrixs" :key="row.id" class="!uppercase" :value="row.id"
                            :label="row.description" />
                    </el-select>
                </div> -->
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

                <el-table-column label="Acreditación" min-width="155">
                    <template #default="{ row }">
                        <el-tag size="small" effect="light" :type="row?.condition?.description ? 'success' : 'info'"
                            class="!rounded-full !font-bold">
                            {{ row?.condition?.description || 'No indica' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="Parametro" min-width="155">
                    <template #default="{ row }">
                        <el-tag size="small" effect="light" :type="row?.condition?.description ? 'success' : 'info'"
                            class="!rounded-full !font-bold">
                            {{ row.parameter.description || 'No indica' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Matriz" min-width="170">
                    <template #default="{ row }">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </span>

                            <span class="font-semibold text-slate-800">
                                {{ row?.matrix?.description || 'No indica' }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Categorias" min-width="270">
                    <template #default="{ row }">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </span>

                            <span class="font-semibold text-slate-800">
                                {{ row?.category?.description || '-' }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Ensayo" min-width="145">
                    <template #default="{ row }">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-mono font-bold">
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

const matrixs = computed(() => listStore.matrixs)
const types = computed(() => listStore.types)
const conditions = computed(() => listStore.conditions)

const filters = reactive({
    type: null,
    condition: null,
    matrix: null,
})

const pagination = ref({
    current_page: 1,
    last_page: 0,
    per_page: 15,
    total: 0,
})

const getItem = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get('items', {
            params: {
                page,
                type: filters.type,
                condition_id: filters.condition,
                matrix_id: filters.matrix,
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
    filters.type = null
    filters.condition = null
    filters.matrix = null

    getItem(1)
}

const isSelected = (row) => {
    if (!Array.isArray(props.items)) return false

    return props.items.some((item) => item.id === row.id)
}

const toggleItem = (row) => {
    const exists = isSelected(row)

    if (exists) {
        const newItems = props.items.filter((item) => item.id !== row.id)

        emit('update:items', newItems)

        return
    }

    emit('update:items', [
        ...props.items,
        {
            ...row,
            price: 0.00,
            number_samples: 1,
        }
    ])
}

const tableRowClassName = ({ row }) => {
    return isSelected(row) ? 'selected-row' : ''
}

const handleClose = () => {
    emit('close')
}

watch(
    filters,
    () => {
        getItem(1)
    },
    { deep: true }
)

onMounted(async () => {
    await listStore.getConditions()
    await listStore.getMatrixs()
    await listStore.getTypes()
    await getItem()
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
</style>
