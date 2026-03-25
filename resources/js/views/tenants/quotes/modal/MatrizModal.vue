<template>
    <el-dialog :model-value="showMatrixModal" style="max-width: 90% !important;" class="!rounded-xl"
        @close="handleClose" :style="{ width: computedDialogWidth }">
        <template #header>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-50 rounded-xl">
                        <i class="fa-solid fa-qrcode text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">
                            Seleccionar Matrices
                        </h3>
                        <p class="text-xs text-slate-500">
                            Selecciona una o más matrices para agregar
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <div class="mb-3 flex justify-end gap-3">
            <el-select clearable v-model="filters.type" placeholder="Filtro por tipo" style="max-width: 200px;">
                <el-option v-for="row in matrizDescription" :value="row" :label="row"></el-option>
            </el-select>
            <el-input style="max-width: 200px;" v-model="filters.q" clearable size="default" placeholder="Buscador..."
                class="w-full sm:w-[320px]">
                <template #prefix>
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </template>
            </el-input>
        </div>

        <el-table size="small" :data="matrices" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
            :row-class-name="rowClassName" stripe @row-click="handleRowClick">
            <el-table-column width="40" fixed="left">
                <template #default="{ row }">
                    <el-checkbox :model-value="valueArray(row)" @change="handleCheck(row, $event)" @click.stop />
                </template>
            </el-table-column>

            <el-table-column type="index" label="#" width="60" />

            <el-table-column label="Matriz" min-width="160">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        {{ row?.description }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Ensayo(s)" min-width="120">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        <el-tooltip effect="dark" content="Ver ensayo(s)">
                            <el-button size="small" type="info" plain @click.stop="handleOpenEssay(row)">
                                <i class="fa-solid fa-eye"></i>
                            </el-button>
                        </el-tooltip>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Metodologia" min-width="280">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        {{ row?.methodologie?.description }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="N° de muestras" min-width="160">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        {{ row?.number_samples }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Precio Unit." min-width="120" fixed="right">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        S/ {{ row?.unit_price }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Precio" min-width="120" fixed="right">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        S/ {{ row?.price }}
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
                Mostrando <span class="font-semibold text-slate-700">{{ matrices.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                @update:current-page="changePage" />
        </div>

        <template #footer>
            <div class="flex justify-end mt-5">
                <el-button @click="handleClose" class="!rounded-xl">
                    Cerrar
                </el-button>
            </div>
        </template>
    </el-dialog>

    <el-dialog v-model="stateEssay" @close="handleCloseEssay" width="600px" class="!p-0 overflow-hidden !rounded-2xl">
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="h-10 w-10 rounded-2xl bg-emerald-50 ring-1 ring-emerald-100 flex items-center justify-center">
                            <i class="fa-solid fa-file-half-dashed text-emerald-500"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-base font-semibold text-slate-900 leading-5 truncate">
                                Ensayo(s)
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">
                                Completa los campos obligatorios y guarda los cambios.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 h-px w-full bg-gradient-to-r from-emerald-400 via-emerald-300 to-emerald-400"></div>
            </div>
        </template>
        <div class="p-3">
            <el-table size="small" :data="essaysRow" stripe>
                <el-table-column type="index" label="#" width="60" />
                <el-table-column label="Ensayo">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.description }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="LCM">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.lcm }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="Unidad de Medida">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.units_measurement?.description }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="Condición">
                    <template #default="{ row }">
                        <div class="flex items-center gap-3">
                            {{ row?.condition?.description }}
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <template #footer>
            <div class="flex justify-end p-4">
                <el-button @click="handleCloseEssay" class="!rounded-xl">
                    Cerrar
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import tenant from '../../../../stores/tenant'
import { useListStore } from '../../../../stores/list'

import { useWindowSize } from '@vueuse/core';
const { width: windowWidth } = useWindowSize();

const computedDialogWidth = computed(() => {
    if (windowWidth.value <= 576) {
        return "90%";
    } else if (windowWidth.value <= 768) {
        return "80%";
    } else if (windowWidth.value <= 992) {
        return "70%";
    } else if (windowWidth.value <= 1200) {
        return "80%";
    } else {
        return "60%";
    }
});

const props = defineProps({
    showMatrixModal: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        default: () => []
    }
})

const valueArray = (item) => {
    return props.items.some(
        i => i.id === item?.id && i.type === 'matriz'
    )
}

const addItem = (item) => {
    const exists = props.items.some(
        i => i.id === item?.id && i.type === 'matriz'
    )

    if (!exists) {
        item.number_samples = item.number_samples ? item.number_samples : 1
        const newItem = JSON.parse(JSON.stringify(item))

        props.items.push({
            type: 'matriz',
            id: item?.id,
            item: newItem
        })
    }
}

const removeItem = (item) => {
    const index = props.items.findIndex(
        i => i.id === item?.id && i.type === 'matriz'
    )

    if (index !== -1) {
        props.items.splice(index, 1)
    }
}

const handleCheck = (row, checked) => {
    if (checked) {
        addItem(row)
    } else {
        removeItem(row)
    }
}

const handleRowClick = (row) => {
    if (valueArray(row)) {
        removeItem(row)
    } else {
        addItem(row)
    }
}

const rowClassName = ({ row }) => {
    return valueArray(row) ? 'selected-row' : ''
}

const filters = ref({
    type: null,
    q: null,
})

const listStore = useListStore()
const matrizDescription = computed(() => listStore.matrizDescription)
const stateEssay = ref(false)
const essaysRow = ref([])

const handleOpenEssay = (row) => {
    stateEssay.value = true
    essaysRow.value = row?.essays ?? [];
}

const handleCloseEssay = () => {
    stateEssay.value = false
    essaysRow.value = []
}

const emits = defineEmits(['close'])
const loading = ref(false)
const matrices = ref([])

const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const getMatriz = async (q = null, page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`matriz?page=${page}`, {
            params: {
                query: filters.value.q,
                type: filters.value.type
            }
        })

        if (data.data.data) {
            matrices.value = data.data.data
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

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const changePage = (p) => {
    getMatriz(null, p)
}

const handleClose = () => {
    emits('close')
}

watch(() => filters.value, (newVal) => {
    getMatriz()
}, { deep: true })

onMounted(() => {
    getMatriz()
    listStore.getMatrizDescription()
})
</script>

<style scoped>
:deep(.selected-row td) {
    background-color: #dcfce7 !important;
}

:deep(.selected-row:hover td) {
    background-color: #bbf7d0 !important;
}
</style>