<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-file-half-dashed"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Ensayos</h2>
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
                <el-table :data="unitsmeasurement" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName" stripe>
                    <el-table-column type="index" label="#" width="60" />

                    <el-table-column label="Ensayo" min-width="280">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                {{ row?.description }}
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="LCM" min-width="280">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                {{ row?.lcm }}
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Unidad de Medida" min-width="280">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                {{ row?.units_measurement?.description }}
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Condición" min-width="280">
                        <template #default="{ row }">
                            <div class="flex items-center gap-3">
                                {{ row?.condition?.description }}
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
                                <el-button-group>
                                    <el-button v-tippy="'Relacionar Equipos'" size="small" class="!m-0"
                                        @click="handleTeam(row)">
                                        <i class="fa-solid fa-sitemap"></i>
                                    </el-button>
                                    <el-button v-tippy="'Editar'" type="warning" plain size="small" class="!m-0"
                                        @click="handleEdit(row)">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </el-button>
                                    <el-button v-tippy="'Eliminar'" type="danger" plain size="small" class="!m-0"
                                        @click="handleDestroy(row?.id)">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </el-button>
                                </el-button-group>
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
                        Mostrando <span class="font-semibold text-slate-700">{{ unitsmeasurement.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @update:current-page="changePage" />
                </div>
            </div>
        </div>
    </div>

    <el-dialog v-model="state" @close="handleClose" :close-on-click-modal="false"
        class="!p-0 overflow-hidden !rounded-xl" width="640px">
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                                <i class="fa-solid fa-ruler-combined text-emerald-500"></i>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 leading-5">
                                    {{ formData.id ? 'Actualizar ensayo' : 'Registrar ensayo' }}
                                </h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    Completa los campos y guarda los cambios.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 h-px w-full bg-gradient-to-r from-emerald-400 via-emerald-300 to-emerald-400">
                </div>
            </div>
        </template>

        <div class="px-6 pb-6 pt-5">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Detalle del ensayo</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Completa los campos obligatorios para registrar el ensayo.
                    </p>
                </div>

                <div
                    class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-600 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    Campos obligatorios
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div
                    class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Ensayo</label>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Describe el ensayo de forma clara (qué es y cómo se reporta).
                            </p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.description" type="textarea" :autosize="{ minRows: 3, maxRows: 8 }"
                        placeholder="Ej: Determinación de X según método Y..." class="mt-3" />
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">LCM</label>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Ingresa el límite (puede ser decimal).
                            </p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-input v-model="formData.lcm" inputmode="decimal" placeholder="Ej: 0.05" class="mt-3">
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
                            <label class="block text-sm font-semibold text-slate-900">Unidad de medida</label>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Selecciona la unidad que se mostrará en el reporte.
                            </p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-select v-model="formData.units_measurement_id" filterable remote reserve-keyword clearable
                        placeholder="Selecciona una unidad" :remote-method="remoteMethodUnits" :loading="loadingUnits"
                        class="mt-3 w-full">
                        <el-option v-for="u in unitsMeasurement" :key="u.id" :label="u.description" :value="u.id" />
                    </el-select>
                </div>

                <div
                    class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition
                    hover:border-slate-300 hover:shadow-md focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-slate-900">Condición</label>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Define la condición para evaluar/validar el resultado.
                            </p>
                        </div>

                        <span
                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                            <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                            Obligatorio
                        </span>
                    </div>

                    <el-select v-model="formData.condition_id" filterable remote reserve-keyword clearable
                        placeholder="Selecciona una unidad" :remote-method="remoteMethodConditions"
                        :loading="loadingConditions" class="mt-3 w-full">
                        <el-option v-for="c in conditions" :key="c.id" :label="c.description" :value="c.id" />
                    </el-select>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="px-6 pb-6">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <el-button class="!rounded-xl !m-0" @click="handleClose">
                        Cerrar
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
                        Los campos se guardan al confirmar.
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <i class="fa-regular fa-keyboard text-emerald-500"></i>
                        Tip: usa Enter para saltar líneas
                    </span>
                </div>
            </div>
        </template>
    </el-dialog>

    <el-dialog top="1vh" v-model="stateTeam" @close="handleCloseTeam" class="!p-0 overflow-hidden !rounded-xl"
        width="840px">
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 ring-1 ring-blue-100">
                                <i class="fa-solid fa-sitemap text-blue-500"></i>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 leading-5">
                                    Relacionar Equipos
                                </h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    Selecciona equipos para el ensayo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 h-px w-full bg-gradient-to-r from-blue-400 via-blue-300 to-blue-400">
                </div>
            </div>
        </template>

        <div class="mx-6 bg-slate-100 font-medium px-4 py-1.5 rounded-lg">
            Ensayo: {{ essay?.description }}
        </div>

        <div class="px-6 pb-5 pt-4">
            <div class="flex gap-3 items-center">
                <el-input v-model="searchTeam" clearable size="default" placeholder="Buscar por denominación..."
                    class="w-full sm:w-[320px]">
                    <template #prefix>
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </template>
                </el-input>
                <el-input v-model="searchTeamSerie" clearable size="default" placeholder="Buscar por serie..."
                    class="w-full sm:w-[320px]">
                    <template #prefix>
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </template>
                </el-input>
                <el-input v-model="searchTeamCode" clearable size="default" placeholder="Buscar codigo..."
                    class="w-full sm:w-[320px]">
                    <template #prefix>
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </template>
                </el-input>
            </div>


            <el-table :data="teams" v-loading="loadingTeam" @row-click="handleRowClick" stripe
                :row-class-name="rowClassNameTeam">
                <el-table-column width="30">
                    <template #default="{ row }">
                        <el-checkbox :model-value="valueArray(row)" @change="handleCheck(row, $event)" @click.stop />
                    </template>
                </el-table-column>

                <el-table-column label="#" width="60" type="index" :index="indexMethod" />

                <el-table-column label="Codigo Interno">
                    <template #default="{ row }">
                        {{ row?.code }}
                    </template>
                </el-table-column>

                <el-table-column label="Área">
                    <template #default="{ row }">
                        {{ row?.area?.description }}
                    </template>
                </el-table-column>

                <el-table-column label="Denominación">
                    <template #default="{ row }">
                        {{ row?.denomination }}
                    </template>
                </el-table-column>

                <el-table-column label="Marca / Modelo / Serie">
                    <template #default="{ row }">
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-900">{{ row?.brand_manufacturer || '—' }}</span>
                            <span class="text-xs text-gray-500">
                                {{ row?.model || '—' }} · {{ row?.serie || '—' }}
                            </span>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Mostrando <span class="font-semibold text-slate-700">{{ teams.length }}</span> de
                    <span class="font-semibold text-slate-700">{{ paginationTeam?.total }}</span> registros
                </p>

                <el-pagination background layout="prev, pager, next, sizes" :total="paginationTeam.total"
                    v-model:page-size="paginationTeam.per_page" v-model:current-page="paginationTeam.current_page"
                    :page-sizes="[10, 20, 50, 100]" @update:current-page="listStore.getTeams" />
            </div>
        </div>

        <template #footer>
            <div class="px-6 pb-6">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <el-button class="!rounded-xl !m-0" @click="handleCloseTeam">
                        Cancelar
                    </el-button>
                    <el-button :loading="loadingSave" type="primary" class="!rounded-xl !m-0" @click="saveRelations">
                        <i class="fa-solid fa-cloud-arrow-up me-1.5"></i>
                        Guardar Relaciones
                    </el-button>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from "vue"
import { ElNotification } from "element-plus";
import tenant from "../../../stores/tenant"
import { useListStore } from "../../../stores/list.js";
import { handleErrorsExeption } from "../../../stores/handleErrorsExeption.js";

const state = ref(false)
const listStore = useListStore()
const conditions = computed(() => listStore.conditions)
const unitsMeasurement = computed(() => listStore.unitsMeasurement)
const stateTeam = ref(false)

const searchTeam = ref('')
const searchTeamCode = ref('')
const searchTeamSerie = ref('')

const filters = reactive({
    q: null,
});

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
});

const rowClassName = () => "hover:bg-slate-50 transition";

const loading = ref(false)
const unitsmeasurement = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const formData = ref({
    id: null,
    description: null,
    lcm: null,
    units_measurement_id: null,
    condition_id: null,
})

const getEssays = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`essays?page=${page}`)

        if (data.data) {
            unitsmeasurement.value = data.data.data
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
    getEssays()
}

const changePage = (p) => {
    getEssays(p)
}

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const loadingSubmit = ref(false)

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (formData.value.id) {
            const { data } = await tenant.put(`essays/${formData.value.id}`, formData.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`essays`, formData.value)
            ElNotification.success(data.message)
        }

        getEssays()
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
    formData.value.id = null
    formData.value.description = null
    formData.value.lcm = null
    formData.value.units_measurement_id = null
    formData.value.condition_id = null

    state.value = false
}

const handleEdit = (row) => {
    state.value = true

    formData.value.id = row.id ?? null
    formData.value.description = row.description ?? null
    formData.value.lcm = row.lcm ?? null
    formData.value.units_measurement_id = row.units_measurement_id ?? null
    formData.value.condition_id = row.condition_id ?? null
}

const handleDestroy = async (id) => {
    try {
        const { data } = await tenant.delete(`essays/${id}`)
        await getEssays()
        ElNotification.success(data.message)
    }
    catch (e) {
        console.error(e)
    }
}

const loadingUnits = ref(false)

const remoteMethodUnits = async (q) => {
    loadingUnits.value = true
    await listStore.getUnitsMeasurement(q)
    loadingUnits.value = false
}

const loadingConditions = ref(false)

const remoteMethodConditions = async (q) => {
    loadingConditions.value = true
    await listStore.getConditions(q)
    loadingConditions.value = false
}

const paginationTeam = computed(() => listStore.paginationTeam)
const loadingTeam = computed(() => listStore.loadingTeam)
const teams = computed(() => listStore.teams)

const essay = ref(null)
const selectIds = ref([])

const handleTeam = (row) => {
    stateTeam.value = true
    essay.value = row
}

const handleCloseTeam = () => {
    stateTeam.value = false

    essay.value = null
    selectIds.value = []
}

const handleRowClick = (row) => {
    if (valueArray(row)) {
        removeItem(row)
    } else {
        addItem(row)
    }
}

const valueArray = (item) => {
    return selectIds.value.some(
        i => i === item.id
    )
}

const addItem = (item) => {
    const exists = selectIds.value.some(
        i => i === item.id
    )

    if (!exists) {
        selectIds.value.push(item.id)
    }
}

const removeItem = (item) => {
    const index = selectIds.value.findIndex(
        i => i === item.id
    )

    if (index !== -1) {
        selectIds.value.splice(index, 1)
    }
}

const handleCheck = (row, checked) => {
    if (checked) {
        addItem(row)
    } else {
        removeItem(row)
    }
}

watch(() => stateTeam.value, async (newVal) => {
    if (newVal) {
        await listStore.getTeams()

        if (!essay.value) return

        await getRelationsTeam()
    }
})

watch(() => searchTeam.value, (newVal) => {
    listStore.getTeams(1, {
        denomination: newVal,
    })
})
watch(() => searchTeamCode.value, (newVal) => {
    listStore.getTeams(1, {
        code: newVal,
    })
})
watch(() => searchTeamSerie.value, (newVal) => {
    listStore.getTeams(1, {
        serie: newVal,
    })
})

const rowClassNameTeam = ({ row }) => {
    return valueArray(row) ? 'selected-row' : ''
}

const indexMethod = (index) => {
    return (paginationTeam.value.current_page - 1) * paginationTeam.value.per_page + index + 1
}

const loadingSave = ref(false)

const saveRelations = async () => {
    loadingSave.value = true

    try {
        const { data } = await tenant.post(`essays/relations-team`, {
            essay_id: essay.value?.id,
            teams_ids: selectIds.value,
        })

        ElNotification.success(data.message)
        handleCloseTeam()
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSave.value = false
    }
}

const getRelationsTeam = async () => {
    try {
        const { data } = await tenant.get(`essays/get-relations-team/${essay.value?.id}`)

        if (data.data) {
            selectIds.value = data.data
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

onMounted(() => {
    getEssays()

    listStore.getConditions()
    listStore.getUnitsMeasurement()
})
</script>

<style scoped>
:deep(.el-table) {
    border-radius: 16px;
}

:deep(.el-table__inner-wrapper::before) {
    height: 0;
}

:deep(.selected-row td) {
    background-color: #dce1fc !important;
}

:deep(.selected-row:hover td) {
    background-color: #bbc9f7 !important;
}
</style>