<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Cotizaciones</h2>
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
                                @click="$router.push('/quote-create')">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Nuevo
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <el-collapse v-model="activeNames" class="mb-5">
                    <el-collapse-item name="1">
                        <template #title>
                            <i class="fa-solid fa-filter"></i> Filtros
                        </template>
                        <template #default>
                            <div class="grid grid-cols-12 w-full gap-3">
                                <div class="col-span-3">
                                    <p class="font-medium">Comercial</p>
                                    <el-select placeholder="Seleccionar" class="!w-full" size="small" clearable>
                                        <el-option></el-option>
                                    </el-select>
                                </div>
                                <div class="col-span-3">
                                    <p class="font-medium">Empresa</p>
                                    <el-select placeholder="Seleccionar" class="!w-full" size="small" clearable>
                                        <el-option></el-option>
                                    </el-select>
                                </div>
                                <div class="col-span-3">
                                    <p class="font-medium">OS Generada</p>
                                    <el-select placeholder="Seleccionar" class="!w-full" size="small" clearable>
                                        <el-option></el-option>
                                    </el-select>
                                </div>
                            </div>
                        </template>
                    </el-collapse-item>
                </el-collapse>

                <el-table stripe :data="quotes" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName">
                    <el-table-column type="index" label="#" width="60" />

                    <el-table-column label="Empresa">
                        <template #default="{ row }">
                            <p>{{ row.company?.business_name }}</p>
                            <span class="block text-xs font-medium">
                                RUC: {{ row.company?.ruc }}
                            </span>
                        </template>
                    </el-table-column>

                    <el-table-column label="Comercial">
                        <template #default="{ row }">
                            <p>{{ row.user?.full_name }}</p>
                        </template>
                    </el-table-column>

                    <el-table-column label="OS Generada">
                        <template #default="{ row }">
                            <el-tag type="danger">NO</el-tag>
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
                            <div class="flex justify-start gap-2">
                                <el-dropdown>
                                    <template #default>
                                        <button v-tippy="'Acciones'"
                                            class="bg-emerald-400 text-white font-medium px-2.5 h-6 rounded-lg text-xs">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </template>
                                    <template #dropdown>
                                        <el-dropdown-item>
                                            <i class="fa-regular fa-file-lines"></i>
                                            Ver Pdf
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <i class="fa-regular fa-file-excel"></i>
                                            Excel
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            Editar
                                        </el-dropdown-item>
                                        <el-dropdown-item class="!text-red-500">
                                            <i class="fa-regular fa-trash-can"></i>
                                            Eliminar
                                        </el-dropdown-item>
                                    </template>
                                </el-dropdown>
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
                        Mostrando <span class="font-semibold text-slate-700">{{ quotes.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @update:current-page="changePage" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import tenant from '../../../stores/tenant';

const activeNames = ref(['1'])

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

const filters = ref({
    q: null
})

const loading = ref(false)
const quotes = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const getQuotes = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`quote?page=${page}`)

        if (data.data) {
            quotes.value = data.data.data
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

const changePage = (p) => {
    getQuotes(p)
}

onMounted(() => {
    getQuotes()
})
</script>

<style scoped></style>