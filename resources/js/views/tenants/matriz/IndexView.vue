<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Matriz</h2>
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
                            <el-button type="primary" size="default" class="!rounded-xl" @click="() => {
                                state = true
                            }">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Nuevo
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <el-table :data="matrices" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName" stripe>
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
                                    <el-button type="info" plain @click="handleOpenEssay(row)">
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

                    <el-table-column label="Precio Unit." min-width="160">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                S/ {{ row?.unit_price }}
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Precio" min-width="160">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                S/ {{ row?.price }}
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
                        Mostrando <span class="font-semibold text-slate-700">{{ matrices.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @update:current-page="changePage" />
                </div>
            </div>
        </div>
    </div>

    <el-dialog v-model="state" @close="handleClose" top="1vh" :close-on-click-modal="false" width="900px"
        class="!p-0 overflow-hidden !rounded-2xl">
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="h-10 w-10 rounded-2xl bg-emerald-50 ring-1 ring-emerald-100 flex items-center justify-center">
                            <i class="fa-solid fa-qrcode text-emerald-500"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-base font-semibold text-slate-900 leading-5 truncate">
                                {{ formData.id ? 'Actualizar matriz' : 'Registrar matriz' }}
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

        <div class="px-6 py-5 max-h-[72vh] overflow-y-auto">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">Información general</h4>
                    <p class="mt-1 text-sm text-slate-600">Define la matriz y asocia los ensayos correspondientes.</p>
                </div>

                <div
                    class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-600 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    Obligatorio
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                        hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Descripción</label>
                            <p class="mt-0.5 text-xs text-slate-500">Describe la matriz de forma clara.</p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.description" type="textarea" :autosize="{ minRows: 3, maxRows: 8 }"
                        placeholder="Ej: Matriz para reporte según LCM, con ensayos A/B..." class="mt-3" />
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Metodología</label>
                            <p class="mt-0.5 text-xs text-slate-500">Selecciona la metodología a usar.</p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-select v-model="formData.methodologie_id" filterable remote reserve-keyword clearable
                        placeholder="Buscar y seleccionar" :remote-method="remoteMethodMethodologies"
                        :loading="loadingMethodologies" class="mt-3 w-full">
                        <el-option v-for="u in methodologies" :key="u.id" :label="u.description" :value="u.id" />
                    </el-select>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">N° de muestras</label>
                            <p class="mt-0.5 text-xs text-slate-500">Cantidad total de muestras.</p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.number_samples" type="number" placeholder="Ej: 1" class="mt-3">
                        <template #prefix>
                            <i class="fa-solid fa-hashtag text-slate-400"></i>
                        </template>
                    </el-input>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Precio Unit.</label>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.unit_price" type="number" placeholder="Ej: 1" class="mt-3">
                        <template #prefix>
                            <i class="fa-solid fa-hashtag text-slate-400"></i>
                        </template>
                    </el-input>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Precio</label>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.price" type="number" placeholder="Ej: 1" class="mt-3">
                        <template #prefix>
                            <i class="fa-solid fa-hashtag text-slate-400"></i>
                        </template>
                    </el-input>
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h4 class="text-sm font-semibold text-slate-900">Ensayos asociados</h4>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Agrega desde “Disponibles” y revisa tu lista en “Seleccionados”.
                        </p>
                    </div>

                    <div
                        class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs text-emerald-700 ring-1 ring-emerald-100">
                        <i class="fa-solid fa-list-check"></i>
                        Seleccionados: <span class="font-semibold">{{ formData.essays?.length || 0 }}</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="p-3 border-b border-slate-200 bg-slate-50 sticky top-0 z-10">
                            <div class="flex items-center gap-2">
                                <el-input v-model="essayQ" clearable placeholder="Buscar ensayo (nombre o ID)..."
                                    @clear="fetchEssays(1)" class="w-full">
                                    <template #prefix>
                                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                                    </template>
                                </el-input>

                                <el-tooltip content="Recargar" placement="top">
                                    <el-button class="!rounded-xl" @click="fetchEssays(1)">
                                        <i class="fa-solid fa-rotate"></i>
                                    </el-button>
                                </el-tooltip>
                            </div>
                        </div>

                        <div class="max-h-[320px] overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead class="sticky top-0 bg-white">
                                    <tr class="text-left text-xs text-slate-500">
                                        <th class="px-3 py-2 w-16">ID</th>
                                        <th class="px-3 py-2">Ensayo</th>
                                        <th class="px-3 py-2 w-24 text-center">Acción</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="row in essays" :key="row.id"
                                        class="border-t border-slate-100 hover:bg-slate-50"
                                        :class="isEssaySelected(row.id) ? 'bg-emerald-50' : ''">
                                        <td class="px-3 py-2 text-slate-700">{{ row.id }}</td>
                                        <td class="px-3 py-2 text-slate-900">
                                            <div class="flex items-start flex-col justify-start gap-2">
                                                <span class="truncate">{{ row.description }}</span>
                                                <span v-if="isEssaySelected(row.id)"
                                                    class="block items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium text-emerald-700">
                                                    Agregado
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <el-button size="small" plain type="primary" class="!rounded-xl"
                                                @click="addEssays(row)" :disabled="isEssaySelected(row.id)">
                                                <i class="fa-solid fa-plus"></i>
                                            </el-button>
                                        </td>
                                    </tr>

                                    <tr v-if="!essays?.length">
                                        <td colspan="3" class="px-3 py-10 text-center text-sm text-slate-500">
                                            No hay ensayos para mostrar.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 border-t border-slate-200 bg-white">
                            <el-pagination small background layout="prev, pager, next" :total="paginateEssays.total"
                                v-model:page-size="paginateEssays.per_page"
                                v-model:current-page="paginateEssays.current_page"
                                @update:current-page="changePageEssays" />
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div
                            class="p-3 border-b border-slate-200 bg-slate-50 sticky top-0 z-10 flex items-center justify-between">
                            <div class="text-sm font-semibold text-slate-900">Seleccionados</div>

                            <el-button text type="danger" class="!rounded-xl" :disabled="!(formData.essays?.length)"
                                @click="clearSelectedEssays">
                                <i class="fa-solid fa-broom mr-2"></i>
                                Limpiar
                            </el-button>
                        </div>

                        <div class="max-h-[370px] overflow-y-auto">
                            <div v-if="!(formData.essays?.length)" class="p-10 text-center">
                                <div
                                    class="mx-auto h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fa-regular fa-folder-open text-slate-500 text-lg"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-900">Aún no agregas ensayos</p>
                                <p class="mt-1 text-sm text-slate-500">Busca en “Disponibles” y presiona + para
                                    agregarlos.
                                </p>
                            </div>

                            <ul v-else class="divide-y divide-slate-100">
                                <li v-for="(row, index) in formData.essays" :key="row.id"
                                    class="p-3 flex items-center justify-between gap-3 hover:bg-slate-50">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate">
                                            {{ index + 1 }}. {{ row.description }}
                                        </p>
                                        <p class="text-xs text-slate-500">ID: {{ row.id }}</p>
                                    </div>

                                    <el-button @click="removeEssays(index)" type="warning" plain size="small"
                                        class="!rounded-xl shrink-0">
                                        <i class="fa-solid fa-minus"></i>
                                    </el-button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="px-6 pb-6">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <el-button class="!rounded-xl !m-0" @click="handleClose">
                        Cancelar
                    </el-button>

                    <el-button type="primary"
                        class="!rounded-xl !bg-emerald-400 !border-emerald-400 hover:!bg-emerald-500 hover:!border-emerald-500"
                        @click="onSubmit()" :loading="loadingSubmit">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                        {{ formData.id ? 'Guardar cambios' : 'Guardar' }}
                    </el-button>
                </div>

                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Revisa antes de guardar.
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-keyboard text-emerald-500"></i>
                        Tip: usa el buscador para agregar rápido
                    </span>
                </div>
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
            <el-table :data="essaysRow" stripe>
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
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import tenant from '../../../stores/tenant';
import { ElMessage, ElNotification } from 'element-plus';
import { useListStore } from '../../../stores/list';

const state = ref(false)
const listStore = useListStore()
const methodologies = computed(() => listStore.methodologies)
const essays = computed(() => listStore.essays)
const paginateEssays = computed(() => listStore.paginationEssays)
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

const filters = ref({
    q: "",
})

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

const loadingSubmit = ref(false);
const loading = ref(false)
const matrices = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const formData = ref({
    description: null,
    methodologie_id: null,
    number_samples: null,
    essays: [],
    unit_price: 0,
    price: 0,
})

const addEssays = (row) => {
    const index = formData.value.essays.findIndex(e => e.id === row.id)

    if (index !== -1) {
        ElMessage.error("El ensayo ya fue agregado")
        return
    }

    formData.value.essays.push(row)
}

const removeEssays = (index) => {
    formData.value.essays.splice(index, 1)
}

const getMatriz = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`matriz?page=${page = 1}`)

        if (data.data) {
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

const resetFilters = () => {
    filters.q = "";
    getMatriz()
}

const changePage = (p) => {
    getMatriz(p)
}

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (formData.value.id) {
            const { data } = await tenant.put(`matriz/${formData.value.id}`, formData.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`matriz`, formData.value)
            ElNotification.success(data.message)
        }

        getMatriz()
        handleClose()
    }
    catch (e) {
        console.error(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const handleClose = () => {
    formData.value = {
        id: null,
        description: null,
        methodologie_id: null,
        number_samples: null,
        essays: [],
        unit_price: 0,
        price: 0,
    }

    state.value = false
}

const handleEdit = (row) => {
    state.value = true

    formData.value = {
        id: row?.id,
        description: row?.description,
        methodologie_id: row?.methodologie_id,
        number_samples: row?.number_samples,
        essays: row?.essays ?? [],
        unit_price: row?.unit_price,
        price: row?.price,
    }
}

const handleDestroy = async (id) => {
    try {
        const { data } = await tenant.delete(`matriz/${id}`)
        await getMatriz()
        ElNotification.success(data.message)
    }
    catch (e) {
        console.error(e)
    }
}

const loadingMethodologies = ref(false)

const remoteMethodMethodologies = async () => {
    loadingMethodologies.value = true
    await listStore.getMethodologies()
    loadingMethodologies.value = false
}

const essayQ = ref("");
let essayTimer = null;

const fetchEssays = (page = 1) => {
    listStore.getEssays(essayQ.value || null, page);
};

watch(essayQ, () => {
    clearTimeout(essayTimer);
    essayTimer = setTimeout(() => fetchEssays(1), 300);
});

const isEssaySelected = (id) => {
    return (formData.value.essays || []).some(e => e.id === id);
};

const clearSelectedEssays = () => {
    formData.value.essays = [];
};

// si ya tienes changePageEssays, solo asegúrate que use el query actual:
const changePageEssays = (p) => fetchEssays(p);

onMounted(async () => {
    await getMatriz()

    listStore.getMethodologies()
    listStore.getEssays()
})
</script>