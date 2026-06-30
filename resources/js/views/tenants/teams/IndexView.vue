<template>
    <custom-header title="Equipos" description="Registro y control de equipos." icon="fa-solid fa-screwdriver-wrench">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button type="primary" @click="visible = true"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                Agregar Registro
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="teams"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column prop="id" label="N°" width="80" />

                <el-table-column prop="code" label="Código" min-width="140" />

                <el-table-column prop="description" label="Descripción" min-width="180" show-overflow-tooltip />

                <el-table-column prop="denomination" label="Denominación" min-width="180" show-overflow-tooltip />

                <el-table-column prop="area.description" label="Área" min-width="150" />

                <el-table-column prop="brand_manufacturer" label="Marca / Fabricante" min-width="170" />

                <el-table-column prop="model" label="Modelo" min-width="120" />

                <el-table-column prop="serie" label="Serie" min-width="130" />

                <el-table-column prop="operational_status" label="Estado operativo" min-width="160">
                    <template #default="{ row }">
                        <el-tag :type="row.operational_status === 'OPERATIVO' ? 'success' : 'danger'" effect="light">
                            {{ row.operational_status || '-' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column prop="executed_calibration" label="Calibración" min-width="140" />

                <el-table-column prop="conformity" label="Conformidad" min-width="140" />

                <el-table-column prop="frequency" label="Frecuencia" min-width="140" />

                <el-table-column prop="executed_verification" label="Verificación" min-width="140" />

                <el-table-column prop="accordance" label="Acorde" min-width="120" />

                <el-table-column prop="observations_certificate" label="Obs. certificado" min-width="180"
                    show-overflow-tooltip />

                <el-table-column fixed="right" label="Estado" width="120">
                    <template #default="{ row }">
                        <div class="my-1">
                            <span v-if="row.status === 'IN'"
                                class="bg-teal-500 text-white font-semibold rounded-lg px-1.5 py-1" effect="light">
                                <i class="fa-solid fa-dolly"></i> En almacén
                            </span>
                            <span v-if="row.status === 'OUT'"
                                class="bg-amber-500 text-white font-semibold rounded-lg px-1.5 py-1" effect="light">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i> Fuera
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="active" label="Activo" width="100">
                    <template #default="{ row }">
                        <el-tag :type="row.active ? 'success' : 'danger'" effect="light">
                            {{ row.active ? 'Sí' : 'No' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="OS" width="125" fixed="right">
                    <template #default="{ row }">
                        <span v-if="row.os" class="bg-[#1aa3c8] text-white font-semibold rounded-lg px-1.5 py-1"
                            effect="light">
                            {{ row.os }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" fixed="right">
                    <template #default="{ row }">
                        <el-dropdown trigger="click" placement="bottom-end">
                            <button type="button"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>

                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item @click="handleEdit(row)">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-solid fa-book-bookmark"></i>
                                            <span>Inf. Mantenimiento</span>
                                        </div>
                                    </el-dropdown-item>
                                    <el-dropdown-item @click="handleEdit(row)">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-solid fa-book-skull"></i>
                                            <span>Hoja de Vida</span>
                                        </div>
                                    </el-dropdown-item>
                                    <el-dropdown-item @click="handleEdit(row)">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            <span>Editar</span>
                                        </div>
                                    </el-dropdown-item>

                                    <el-dropdown-item divided @click="handleDelete(row)">
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
                    <div class="py-16 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                            <i class="fa-solid fa-flask-vial text-2xl"></i>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-900">
                            No hay equipos registradas
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Ajusta los filtros o registra una nueva cotización para continuar.
                        </p>

                        <el-button class="mt-4 !rounded-xl !font-semibold" plain>
                            Limpiar filtros
                        </el-button>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ teams.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getTypeOfAnalysis" />
        </div>
    </div>

    <!-- <confirm-dialog ref="confirmRef" /> -->
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption.js';
import tenant from '../../../stores/tenant.js';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';
import { ElMessage, ElNotification } from 'element-plus';

const visible = ref(false)

const filters = reactive({
    search: null,
})

const loading = ref(false)
const teams = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const getTeams = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`teams?page=${page}`, {
            params: {
                search: filters.search
            }
        })

        if (data.data) {
            teams.value = data.data.data
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

watch(() => filters.search, (newVal) => {
    getTeams()
})

onMounted(async () => {
    await getTeams()
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
