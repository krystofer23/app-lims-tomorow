<template>
    <custom-header title="Laboratorio" description="Registro y control de laboratorio." icon="fa-solid fa-flask-vial">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-button class="!rounded-xl !px-5 !h-8" @click="onCancel">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Volver
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div v-if="loading" class="space-y-5">
            <div class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2">
                <div v-for="item in 4" :key="`tab-loader-${item}`"
                    class="h-9 w-32 animate-pulse rounded-xl bg-slate-200"></div>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200">
                <div class="grid grid-cols-5 gap-0 border-b border-slate-200 bg-slate-50">
                    <div v-for="item in 5" :key="`head-loader-${item}`" class="p-3">
                        <div class="h-3 w-24 animate-pulse rounded bg-slate-200"></div>
                    </div>
                </div>

                <div v-for="row in 4" :key="`row-loader-${row}`"
                    class="grid grid-cols-5 gap-0 border-b border-slate-100 last:border-b-0">
                    <div class="p-3">
                        <div class="h-3 w-44 animate-pulse rounded bg-slate-100"></div>
                    </div>

                    <div class="p-3">
                        <div class="mx-auto h-3 w-16 animate-pulse rounded bg-slate-100"></div>
                    </div>

                    <div class="p-3">
                        <div class="mx-auto h-3 w-14 animate-pulse rounded bg-slate-100"></div>
                    </div>

                    <div class="p-3">
                        <div class="mx-auto h-3 w-14 animate-pulse rounded bg-slate-100"></div>
                    </div>

                    <div class="p-3">
                        <div class="h-8 w-full animate-pulse rounded-lg bg-slate-100"></div>
                    </div>
                </div>
            </div>
        </div>

        <template v-else>
            <div v-if="results?.length"
                class="mb-5 flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2">
                <button v-for="group in results" :key="group.type_of_sample_id" type="button" @click="() => {
                    handleTab(group)
                }" :class="tabSample === group.type_of_sample
                    ? 'bg-teal-500 text-white shadow-sm'
                    : isCompleted(getAllItems(group))
                        ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-100'
                        : 'bg-white text-slate-500 ring-1 ring-slate-200 hover:bg-slate-50 hover:text-slate-700'"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold transition-all duration-200">
                    <i :class="[
                        isCompleted(getAllItems(group))
                            ? 'fa-solid fa-circle-check'
                            : 'fa-solid fa-vial-circle-check',

                        tabSample === group.type_of_sample
                            ? 'text-white'
                            : isCompleted(getAllItems(group))
                                ? 'text-emerald-600'
                                : 'text-teal-500',

                        'text-[11px]'
                    ]"></i>

                    <span>{{ group.type_of_sample }}</span>

                    <span class="ml-1 rounded-full px-2 py-0.5 text-[10px] font-bold" :class="tabSample === group.type_of_sample
                        ? 'bg-white/20 text-white'
                        : isCompleted(getAllItems(group))
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-teal-50 text-teal-600'">
                        {{ getTotalStations(getAllItems(group)) }}
                    </span>

                    <span v-if="isCompleted(getAllItems(group))" class="rounded-full px-2 py-0.5 text-[10px] font-bold"
                        :class="tabSample === group.type_of_sample
                            ? 'bg-white/20 text-white'
                            : 'bg-emerald-100 text-emerald-700'">
                        Completado
                    </span>
                </button>
            </div>

            <template v-for="group in results" :key="`table-${group.type_of_sample_id}`">
                <div v-if="tabSample === group.type_of_sample">
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">
                                    {{ group.type_of_sample }}
                                </h3>

                                <p class="text-xs text-slate-400">
                                    Registro de resultados por parámetro y código de estación
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div>
                                <el-button :loading="loadingSubmit" @click="onSubmit(getActiveItems(group))"
                                    class="!rounded-lg" type="primary" plain>
                                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i>
                                    Guardar Resultados
                                </el-button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">
                                    Parámetros por acreditación
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Visualiza los ensayos agrupados por condición
                                </p>
                            </div>

                            <div v-loading="loadingTrialPeriod" element-loading-text="Cargando periodo..."
                                element-loading-background="rgba(255, 255, 255, 0.75)"
                                class="relative rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                                    <div class="flex-1">
                                        <div class="mb-2 flex items-center gap-2">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                                <i class="fa-regular fa-calendar-days text-sm"></i>
                                            </div>

                                            <div>
                                                <label class="text-sm font-semibold text-slate-800">
                                                    Periodo de ensayo
                                                </label>
                                                <p class="text-xs text-slate-500">
                                                    Selecciona el rango de fechas para el ensayo
                                                </p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                            <el-date-picker v-model="form.date_init" type="date" format="DD/MM/YYYY"
                                                value-format="YYYY-MM-DD" class="input-custom !w-full"
                                                placeholder="Fecha inicial" />

                                            <el-date-picker v-model="form.date_end" type="date" format="DD/MM/YYYY"
                                                value-format="YYYY-MM-DD" class="input-custom !w-full"
                                                placeholder="Fecha final" />
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <el-button :loading="loadingForm" :disabled="loadingTrialPeriod"
                                            @click="onSubmitForm" class="!rounded-lg" v-tippy="'Guardar periodo'"
                                            type="success" plain>
                                            <i v-if="!loadingForm" class="fa-solid fa-floppy-disk"></i>
                                        </el-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <el-tabs v-model="activeAccreditationTab" type="card" class="accreditation-tabs -mt-8">
                            <el-tab-pane name="ias" v-if="group?.items_ias && group.items_ias.length !== 0">
                                <template #label>
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>IAS</span>
                                    </span>
                                </template>

                                <el-table :data="group.items_ias" border stripe class="w-full rounded-xl">
                                    <el-table-column prop="parameter" label="Parámetro" min-width="230" fixed="left">
                                        <template #default="{ row }">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ row.parameter }}
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    {{ row.reference_code || '-' }}
                                                </p>

                                                <p v-if="!row.item_id"
                                                    class="mt-1 text-[11px] font-semibold text-red-500">
                                                    Sin item_id
                                                </p>
                                            </div>
                                        </template>
                                    </el-table-column>

                                    <el-table-column prop="condition" label="Acreditación" width="120" align="center" />
                                    <el-table-column prop="unit_measurement" label="Unidad" width="120"
                                        align="center" />
                                    <el-table-column prop="lcm" label="L.C.M." width="120" align="center" />

                                    <el-table-column label="Resultados por estación" min-width="430">
                                        <template #default="{ row }">
                                            <div v-if="row.stations?.length" class="space-y-2">
                                                <div v-for="station in row.stations"
                                                    :key="`${row.item_id || row.id}-${station.chain_custody_id}`"
                                                    class="grid grid-cols-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-2">
                                                    <div class="col-span-12 md:col-span-5">
                                                        <p class="text-xs font-bold text-slate-700">
                                                            {{ station.code_season || 'Sin código estación' }}
                                                        </p>

                                                        <p class="text-[11px] text-slate-400">
                                                            Código lab: {{ station.code_lab || '-' }}
                                                        </p>

                                                        <p v-if="station.code_sample"
                                                            class="text-[11px] text-slate-400">
                                                            Muestra: {{ station.code_sample }}
                                                        </p>
                                                    </div>

                                                    <div class="col-span-12 md:col-span-7">
                                                        <el-input v-model="station.result"
                                                            placeholder="Ingrese resultado" clearable
                                                            class="input-custom" />
                                                    </div>
                                                </div>
                                            </div>

                                            <el-alert v-else
                                                title="Este parámetro no está asociado a ninguna cadena de custodia"
                                                type="warning" show-icon :closable="false" />
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </el-tab-pane>

                            <el-tab-pane name="inacal" v-if="group?.items_inacal && group.items_inacal.length !== 0">
                                <template #label>
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span>INACAL</span>
                                    </span>
                                </template>

                                <el-table :data="group.items_inacal" border stripe class="w-full rounded-xl">
                                    <el-table-column prop="parameter" label="Parámetro" min-width="230" fixed="left">
                                        <template #default="{ row }">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ row.parameter }}
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    {{ row.reference_code || '-' }}
                                                </p>

                                                <p v-if="!row.item_id"
                                                    class="mt-1 text-[11px] font-semibold text-red-500">
                                                    Sin item_id
                                                </p>
                                            </div>
                                        </template>
                                    </el-table-column>

                                    <el-table-column prop="condition" label="Acreditación" width="120" align="center" />
                                    <el-table-column prop="unit_measurement" label="Unidad" width="120"
                                        align="center" />
                                    <el-table-column prop="lcm" label="L.C.M." width="120" align="center" />

                                    <el-table-column label="Resultados por estación" min-width="430">
                                        <template #default="{ row }">
                                            <div v-if="row.stations?.length" class="space-y-2">
                                                <div v-for="station in row.stations"
                                                    :key="`${row.item_id || row.id}-${station.chain_custody_id}`"
                                                    class="grid grid-cols-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-2">
                                                    <div class="col-span-12 md:col-span-5">
                                                        <p class="text-xs font-bold text-slate-700">
                                                            {{ station.code_season || 'Sin código estación' }}
                                                        </p>

                                                        <p class="text-[11px] text-slate-400">
                                                            Código lab: {{ station.code_lab || '-' }}
                                                        </p>

                                                        <p v-if="station.code_sample"
                                                            class="text-[11px] text-slate-400">
                                                            Muestra: {{ station.code_sample }}
                                                        </p>
                                                    </div>

                                                    <div class="col-span-12 md:col-span-7">
                                                        <el-input v-model="station.result"
                                                            placeholder="Ingrese resultado" clearable
                                                            class="input-custom" />
                                                    </div>
                                                </div>
                                            </div>

                                            <el-alert v-else
                                                title="Este parámetro no está asociado a ninguna cadena de custodia"
                                                type="warning" show-icon :closable="false" />
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </el-tab-pane>

                            <el-tab-pane name="no_acreditado" v-if="group?.items && group.items.length !== 0">
                                <template #label>
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        <span>NO ACREDITADO</span>
                                    </span>
                                </template>

                                <el-table :data="group.items" border stripe class="w-full rounded-xl">
                                    <el-table-column prop="parameter" label="Parámetro" min-width="230" fixed="left">
                                        <template #default="{ row }">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ row.parameter }}
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    {{ row.reference_code || '-' }}
                                                </p>

                                                <p v-if="!row.item_id"
                                                    class="mt-1 text-[11px] font-semibold text-red-500">
                                                    Sin item_id
                                                </p>
                                            </div>
                                        </template>
                                    </el-table-column>

                                    <el-table-column prop="condition" label="Acreditación" width="120" align="center" />
                                    <el-table-column prop="unit_measurement" label="Unidad" width="120"
                                        align="center" />
                                    <el-table-column prop="lcm" label="L.C.M." width="120" align="center" />

                                    <el-table-column label="Resultados por estación" min-width="430">
                                        <template #default="{ row }">
                                            <div v-if="row.stations?.length" class="space-y-2">
                                                <div v-for="station in row.stations"
                                                    :key="`${row.item_id || row.id}-${station.chain_custody_id}`"
                                                    class="grid grid-cols-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-2">
                                                    <div class="col-span-12 md:col-span-5">
                                                        <p class="text-xs font-bold text-slate-700">
                                                            {{ station.code_season || 'Sin código estación' }}
                                                        </p>

                                                        <p class="text-[11px] text-slate-400">
                                                            Código lab: {{ station.code_lab || '-' }}
                                                        </p>

                                                        <p v-if="station.code_sample"
                                                            class="text-[11px] text-slate-400">
                                                            Muestra: {{ station.code_sample }}
                                                        </p>
                                                    </div>

                                                    <div class="col-span-12 md:col-span-7">
                                                        <el-input v-model="station.result"
                                                            placeholder="Ingrese resultado" clearable
                                                            class="input-custom" />
                                                    </div>
                                                </div>
                                            </div>

                                            <el-alert v-else
                                                title="Este parámetro no está asociado a ninguna cadena de custodia"
                                                type="warning" show-icon :closable="false" />
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </template>

            <el-empty v-if="!results?.length" description="No hay resultados para mostrar" />
        </template>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption'
import tenant from '../../../stores/tenant'
import CustomHeader from '../../../components/tenants/CustomHeader.vue'
import { ElMessage } from 'element-plus'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const loadingSubmit = ref(false)

const results = ref([])
const tabSample = ref('')

const activeAccreditationTab = ref('ias')

const form = ref({
    date_init: null,
    date_end: null,
    order_id: null,
    type_of_sample_id: null,
    condition_id: null,
})

const handleTab = (group) => {
    tabSample.value = group.type_of_sample
    form.value.type_of_sample_id = group.type_of_sample_id
}

watch(() => tabSample.value, (newVal) => {
    let res = results.value.find(r => r.type_of_sample === newVal)

    if (res && res.items_ias.length !== 0) {
        activeAccreditationTab.value = 'ias'
    }
    if (res && res.items_inacal.length !== 0) {
        activeAccreditationTab.value = 'inacal'
    }
    if (res && res.items.length !== 0) {
        activeAccreditationTab.value = 'no_acreditado'
    }
})

watch(() => activeAccreditationTab.value, (newVal) => {
    if (newVal === 'ias') form.value.condition_id = 2
    if (newVal === 'inacal') form.value.condition_id = 1
    if (newVal === 'no_acreditado') form.value.condition_id = 3
})

const getActiveItems = (group) => {
    if (activeAccreditationTab.value === 'ias') return group.items_ias ?? []
    if (activeAccreditationTab.value === 'inacal') return group.items_inacal ?? []
    if (activeAccreditationTab.value === 'no_acreditado') return group.items ?? []

    return []
}

const getAllItems = (group) => {
    return [
        ...(group.items_ias ?? []),
        ...(group.items_inacal ?? []),
        ...(group.items ?? []),
    ]
}

const loadingForm = ref(false)
const loadingTrialPeriod = ref(false)

const getTrialPeriod = async () => {
    loadingTrialPeriod.value = true

    try {
        const { data } = await tenant.get(`trial-period`, {
            params: {
                order_id: form.value.order_id,
                type_of_sample_id: form.value.type_of_sample_id,
                condition_id: form.value.condition_id,
            }
        })

        if (data.data) {
            form.value.date_init = data.data?.date_init ?? null
            form.value.date_end = data.data?.date_end ?? null
        }
        else {
            form.value.date_end = null
            form.value.date_init = null
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingTrialPeriod.value = false
    }
}

const onSubmitForm = async () => {
    if (!form.value.date_init) return ElMessage.warning('Debe de ingresar la fecha incial')
    if (!form.value.date_end) return ElMessage.warning('Debe de ingresar la fecha final')
    if (!form.value.order_id) return ElMessage.error('Error no hay una orden')
    if (!form.value.type_of_sample_id) return ElMessage.error('Error no hay tipo de muestra')
    if (!form.value.condition_id) return ElMessage.error('Error no hay una condición')

    loadingForm.value = true

    try {
        const { data } = await tenant.post(`trial-period`, form.value)
        ElMessage.success(data.message)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingForm.value = false
    }
}

const getShow = async (orderId) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`lab-orders-show/${orderId}`)

        results.value = data.data ?? []

        const firstGroup = results.value?.[0]
        tabSample.value = firstGroup?.type_of_sample ?? ''

        if (firstGroup?.items_ias?.length) {
            activeAccreditationTab.value = 'ias'

            form.value.condition_id = 2
            form.value.type_of_sample_id = firstGroup?.type_of_sample_id
        }
        else if (firstGroup?.items_inacal?.length) {
            activeAccreditationTab.value = 'inacal'

            form.value.condition_id = 1
            form.value.type_of_sample_id = firstGroup?.type_of_sample_id
        }
        else if (firstGroup?.items?.length) {
            activeAccreditationTab.value = 'no_acreditado'

            form.value.condition_id = 3
            form.value.type_of_sample_id = firstGroup?.type_of_sample_id
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const buildPayload = (items = []) => {
    const payload = []

    items.forEach((item) => {
        if (!Array.isArray(item.stations)) return

        item.stations.forEach((station) => {
            if (!item.item_id || !station.chain_custody_id) return

            console.log(item)

            payload.push({
                item_id: item.to_metal_id ? item.parameter_id : item.item_id,
                order_item_id: item.id,
                chain_custody_id: station.chain_custody_id,
                result: station.result ?? null,
            })
        })
    })

    return payload
}

const onSubmit = async (items = []) => {
    if (!route.query.orderId) {
        ElMessage.warning('Error orden no encontrada')
        return
    }

    const payload = buildPayload(items)

    if (!payload.length) {
        ElMessage.warning('No hay resultados para guardar')
        return
    }

    loadingSubmit.value = true

    try {
        const { data } = await tenant.post(`lab-orders-store`, {
            order_id: route.query.orderId,
            results: payload,
        })

        ElMessage.success(data.message)

        await getShow(route.query.orderId)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

watch(
    () => [
        form.value.condition_id,
        form.value.order_id,
        form.value.type_of_sample_id,
    ],
    ([conditionId, orderId, typeOfSampleId], [oldConditionId, oldOrderId, oldTypeOfSampleId]) => {
        if (!conditionId || !orderId || !typeOfSampleId) {
            return
        }

        getTrialPeriod()
    }
)

const onCancel = () => {
    router.push({ name: 'laboratory' })
}

const getTotalStations = (items = []) => {
    return items.reduce((total, item) => {
        return total + (Array.isArray(item.stations) ? item.stations.length : 0)
    }, 0)
}

const isCompleted = (items = []) => {
    if (!items.length) return false

    const stations = items.flatMap((item) => {
        return Array.isArray(item.stations) ? item.stations : []
    })

    if (!stations.length) return false

    return stations.every((station) => {
        return station.result !== null &&
            station.result !== undefined &&
            String(station.result).trim() !== ''
    })
}

onMounted(async () => {
    if (route.query.orderId) {
        await getShow(route.query.orderId)
        form.value.order_id = route.query.orderId
    }
})
</script>

<style scoped>
:deep(.input-custom .el-input__wrapper) {
    border-radius: 10px !important;
}

.accreditation-tabs :deep(.el-tabs__header) {
    margin-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.accreditation-tabs :deep(.el-tabs__nav) {
    border: none;
    gap: 8px;
}

.accreditation-tabs :deep(.el-tabs__item) {
    height: 36px;
    padding: 0 16px;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px 12px 0 0;
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}

.accreditation-tabs :deep(.el-tabs__item.is-active) {
    background: #ffffff;
    color: #0f172a;
    border-bottom-color: #ffffff !important;
}

.accreditation-tabs :deep(.el-tabs__content) {
    padding-top: 4px;
}

:deep(.el-loading-mask) {
    border-radius: 1rem;
    backdrop-filter: blur(2px);
}

:deep(.el-loading-spinner .circular) {
    height: 34px;
    width: 34px;
}

:deep(.el-loading-text) {
    margin-top: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
}
</style>
