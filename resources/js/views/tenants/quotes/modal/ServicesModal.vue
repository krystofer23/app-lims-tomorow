<template>
    <el-dialog :style="{ width: computedDialogWidth }" :model-value="showServiceModal" width="800" class="!rounded-xl" @close="handleClose">
        <template #header>
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-emerald-50 rounded-xl">
                    <i class="fa-solid fa-clipboard-list text-emerald-500"></i>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Seleccionar Servicios
                    </h3>
                    <p class="text-xs text-slate-500">
                        Agrega servicios a la cotización
                    </p>
                </div>
            </div>
        </template>

        <div class="mb-3 flex justify-end gap-3">
            <el-input style="max-width: 200px;" v-model="filters.q" clearable size="default" placeholder="Buscador..."
                class="w-full sm:w-[320px]">
                <template #prefix>
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </template>
            </el-input>
        </div>

        <el-table size="small" :data="services" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
            :row-class-name="rowClassName" stripe @row-click="handleRowClick">
            <el-table-column width="40" fixed="left">
                <template #default="{ row }">
                    <el-checkbox :model-value="valueArray(row)" @change="handleCheck(row, $event)" @click.stop />
                </template>
            </el-table-column>

            <el-table-column type="index" label="#" width="60" />

            <el-table-column label="Servicio" min-width="160">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        {{ row?.description }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="Servicio" min-width="160">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        {{ row?.unit_price }}
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
                Mostrando <span class="font-semibold text-slate-700">{{ services.length }}</span> de
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
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
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
        return "40%";
    }
});

const listStore = useListStore()

const props = defineProps({
    showServiceModal: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        default: () => []
    }
})

const filters = ref({
    q: null,
})

watch(() => filters.value.q, (newVal) => {
    listStore.getServices(newVal)
})

const pagination = computed(() => listStore.paginationService)
const loading = computed(() => listStore.loadingService)
const services = computed(() => listStore.services)

const emits = defineEmits(['close'])

const handleClose = () => {
    emits('close')
}

const handleRowClick = (row) => {
    if (valueArray(row)) {
        removeItem(row)
    } else {
        addItem(row)
    }
}

const addItem = (item) => {
    const exists = props.items.some(
        i => i.id === item?.id && i.type === 'service'
    )

    if (!exists) {
        item.amount = 1

        const newItem = JSON.parse(JSON.stringify(item))

        props.items.push({
            type: 'service',
            id: item?.id,
            item: newItem
        })
    }
}

const removeItem = (item) => {
    const index = props.items.findIndex(
        i => i.id === item?.id && i.type === 'service'
    )

    if (index !== -1) {
        props.items.splice(index, 1)
    }
}

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = ({ row }) => {
    return valueArray(row) ? 'selected-row' : ''
}

const valueArray = (item) => {
    return props.items.some(
        i => i.id === item?.id && i.type === 'service'
    )
}

const handleCheck = (row, checked) => {
    if (checked) {
        addItem(row)
    } else {
        removeItem(row)
    }
}

const changePage = (p) => {
    listStore.getServices(null, pageXOffset)
}

onMounted(() => {
    listStore.getServices()
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