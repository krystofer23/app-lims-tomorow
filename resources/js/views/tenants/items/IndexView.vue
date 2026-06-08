<template>
    <custom-header title="Gestión de Items" description="Registro y control de parametros condiciones items."
        icon="fa-solid fa-flask-vial">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[360px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <Search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="() => {
                dialogVisible = true
            }" type="primary"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                Agregar Registro
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
                                Acreditación
                            </label>
                            <el-select clearable v-model="filters.condition_id">
                                <el-option v-for="row in conditions" :label="row.description"
                                    :value="row.id"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                Tipo de muestras
                            </label>
                            <el-select clearable v-model="filters.type_of_sample_id">
                                <el-option v-for="row in types" :label="row.description" :value="row.id"></el-option>
                            </el-select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                Parametro
                            </label>
                            <el-input clearable placeholder="Escribir..." v-model="filters.parameter"></el-input>
                        </div>
                    </div>
                </template>
            </el-collapse-item>
        </el-collapse>

        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" stripe :data="items" v-loading="loading"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center">
                    <template #header>N°</template>
                </el-table-column>

                <el-table-column width="200" label="Precio Unitario">
                    <template #default="{ row }">
                        <p class="truncate font-semibold">
                            S/ {{ row.unit_price }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200" label="Tipo de Muestra">
                    <template #default="{ row }">
                        <p class="truncate font-semibold">
                            {{ row?.type_of_sample ??
                                row?.parameter?.connections_parameter[0]?.type_of_sample?.description ?? '-' }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200" label="Acreditación">
                    <template #default="{ row }">
                        <span class="truncate bg-teal-100 px-2 py-1 rounded-lg font-medium">
                            {{ row?.condition?.description }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column width="200" label="Parametro">
                    <template #default="{ row }">
                        {{ row?.parameter?.description }}
                    </template>
                </el-table-column>
                <el-table-column width="200" label="LCM">
                    <template #default="{ row }">
                        {{ row?.lcm }}
                    </template>
                </el-table-column>
                <el-table-column width="200" label="Unidad de medida">
                    <template #default="{ row }">
                        {{ row?.unit_measurement?.description }}
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" fixed="right">
                    <template #default="{ row }">
                        <el-dropdown trigger="click" placement="bottom-end">
                            <button type="button"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 active:scale-95">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>

                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item>
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Editar</span>
                                        </div>
                                    </el-dropdown-item>

                                    <el-dropdown-item divided>
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-regular fa-trash-can"></i>
                                            <span>Eliminar</span>
                                        </div>
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </template>
                </el-table-column>

                <template #empty>
                    <div class="py-10 text-center">
                        <p class="text-sm font-medium text-slate-500">
                            No hay items disponibles
                        </p>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ items.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination layout="prev, pager, next, sizes" :total="items.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getItems" />
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { handleCurrentChange } from 'element-plus/es/components/tree/src/model/util.mjs';
import tenant from '../../../stores/tenant.js';
import { useListStore } from '../../../stores/list.js';
import { Search } from '@element-plus/icons-vue';

const visible = ref(false)

const activeNames = ref(['1'])
const filters = ref({
    search: null,
    parameter: null,
    condition_id: null,
    type_of_sample_id: null
})

const listStore = useListStore()

const conditions = computed(() => listStore.conditions)
const types = computed(() => listStore.typesSampling)

const loading = ref(false)
const dialogVisible = ref(false)
const items = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0
})

const getItems = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`items?page=${page}`, {
            params: filters.value
        })

        if (data.data) {
            items.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total
            }
        }
    }
    catch (e) {
        handleCurrentChange(e)
    }
    finally {
        loading.value = false
    }
}

watch(() => filters.value, getItems, { deep: true })

onMounted(async () => {
    await getItems()
    await listStore.getConditions()
    await listStore.getTypesSampling()
})
</script>

<style scoped>
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
