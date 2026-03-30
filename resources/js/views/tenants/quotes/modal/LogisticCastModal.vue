<template>
    <el-dialog :model-value="state" style="max-width: 90% !important;" class="!rounded-xl" @close="handleClose"
        :style="{ width: computedDialogWidth }">
        <template #header>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-amber-50 rounded-xl">
                        <i class="fa-solid fa-coins text-amber-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">
                            Seleccionar Gastos Logisticos
                        </h3>
                        <p class="text-xs text-slate-500">
                            Selecciona uno o mas gastos para agregar
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <el-table size="small" :data="logisticCats" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
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

            <el-table-column label="Precio Unit." min-width="120" fixed="right">
                <template #default="{ row }">
                    <div class="flex items-center gap-3">
                        S/ {{ row?.unit_price }}
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
                Mostrando <span class="font-semibold text-slate-700">{{ logisticCats.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                @update:current-page="getLogisticCast" />
        </div>

        <template #footer>
            <div class="flex justify-end p-4">
                <el-button @click="handleClose" class="!rounded-xl">
                    Cerrar
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useWindowSize } from '@vueuse/core';
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption';
import tenant from '../../../../stores/tenant';
const { width: windowWidth } = useWindowSize();

const emits = defineEmits(['close'])

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
    state: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        default: () => []
    }
})

const loading = ref(false)
const logisticCats = ref([])
const pagination = ref({
    last_page: 0,
    current_page: 0,
    total: 0,
    per_page: 0,
})

const handleRowClick = (row) => {
    if (valueArray(row)) {
        removeItem(row)
    } else {
        addItem(row)
    }
}

const addItem = (item) => {
    const exists = props.items.some(
        i => i.id === item?.id
    )

    if (!exists) {
        item.amount = 1
        item.total = item.amount * item.unit_price
        item.days = 1

        const newItem = JSON.parse(JSON.stringify(item))

        props.items.push({
            ...newItem
        })
    }
}

const removeItem = (item) => {
    const index = props.items.findIndex(
        i => i.id === item?.id
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

const valueArray = (item) => {
    return props.items.some(
        i => i.id === item?.id
    )
}

const getLogisticCast = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`logistic-cats?page=${page}`)

        if (data.data) {
            logisticCats.value = data.data.data
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

const handleClose = () => {
    emits('close')
}

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
})

const rowClassName = ({ row }) => {
    return valueArray(row) ? 'selected-row' : ''
}

onMounted(() => {
    getLogisticCast()
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