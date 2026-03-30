<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Empresas</h2>
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
                                @click="$router.push('/company-create')">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Nuevo
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <el-table stripe :data="companies" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName">
                    <el-table-column type="index" label="#" width="60" />

                    <el-table-column label="Razón social" min-width="280">
                        <template #default="{ row }">
                            <div class="flex flex-col">
                                {{ row?.business_name }}
                                <p class="text-xs">
                                    <strong>RUC:</strong> {{ row?.ruc }}
                                </p>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Actividad" min-width="280">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                {{ row?.activity ?? '-' }}
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Es Partner?" min-width="120">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                <span v-if="row?.is_partner"
                                    class="uppercase text-xs bg-emerald-400 text-white font-semibold rounded-lg p-1">Si</span>
                                <span v-else
                                    class="uppercase text-xs bg-red-400 text-white font-semibold rounded-lg p-1">No</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Es Proveedor?" min-width="120">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                <span v-if="row?.is_supplier"
                                    class="uppercase text-xs bg-emerald-400 text-white font-semibold rounded-lg p-1">Si</span>
                                <span v-else
                                    class="uppercase text-xs bg-red-400 text-white font-semibold rounded-lg p-1">No</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Estado" min-width="120">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                <span v-if="row?.state"
                                    class="uppercase text-xs bg-emerald-400 text-white font-semibold rounded-lg p-1">Activo</span>
                                <span v-else
                                    class="uppercase text-xs bg-red-400 text-white font-semibold rounded-lg py-1 px-1.5">inactivo</span>
                            </div>
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
                            <div class="flex justify-end gap-2">
                                <el-tooltip content="Editar" placement="top">
                                    <el-button circle class="!rounded-xl !m-0" @click="handleEdit(row)">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </el-button>
                                </el-tooltip>

                                <el-tooltip content="Eliminar" placement="top">
                                    <el-button circle type="danger" plain class="!rounded-xl !m-0"
                                        @click="handleDestroy(row?.id)">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </el-button>
                                </el-tooltip>
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
                            <el-button class="mt-4 !rounded-xl" @click="resetFilters">Limpiar filtros</el-button>
                        </div>
                    </template>
                </el-table>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Mostrando <span class="font-semibold text-slate-700">{{ companies.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @change="changePage" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import tenant from '../../../stores/tenant';
import { useRouter } from 'vue-router';

const router = useRouter()
const loading = ref(false)
const companies = ref([])
const pagination = ref({
    current_page: null,
    last_page: null,
    total: null,
    per_page: null,
})

const filters = reactive({
    q: null,
})

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const getCompanies = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`company?page=${page}`)

        if (data.data) {
            companies.value = data.data.data
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

const resetFilters = () => {
    filters.q = null
    pagination.page = 1

    getCompanies()
}

const changePage = (p) => {
    getCompanies(p)
}

const handleEdit = (row) => {
    router.push('company-update/' + row.id)
}

onMounted(() => {
    getCompanies()
})
</script>

<style scoped></style>