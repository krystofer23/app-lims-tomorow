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
                <button v-for="group in results" :key="group.type_of_sample_id" type="button"
                    @click="tabSample = group.type_of_sample" :class="tabSample === group.type_of_sample
                        ? 'bg-teal-500 text-white shadow-sm'
                        : isCompleted(group.items)
                            ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-100'
                            : 'bg-white text-slate-500 ring-1 ring-slate-200 hover:bg-slate-50 hover:text-slate-700'"
                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold transition-all duration-200">
                    <i :class="[
                        isCompleted(group.items)
                            ? 'fa-solid fa-circle-check'
                            : 'fa-solid fa-vial-circle-check',

                        tabSample === group.type_of_sample
                            ? 'text-white'
                            : isCompleted(group.items)
                                ? 'text-emerald-600'
                                : 'text-teal-500',

                        'text-[11px]'
                    ]"></i>

                    <span>{{ group.type_of_sample }}</span>

                    <span class="ml-1 rounded-full px-2 py-0.5 text-[10px] font-bold" :class="tabSample === group.type_of_sample
                        ? 'bg-white/20 text-white'
                        : isCompleted(group.items)
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-teal-50 text-teal-600'">
                        {{ getTotalStations(group.items) }}
                    </span>

                    <span v-if="isCompleted(group.items)" class="rounded-full px-2 py-0.5 text-[10px] font-bold" :class="tabSample === group.type_of_sample
                        ? 'bg-white/20 text-white'
                        : 'bg-emerald-100 text-emerald-700'">
                        Completado
                    </span>
                </button>
            </div>

            <template v-for="group in results" :key="`table-${group.type_of_sample_id}`">
                <div v-if="tabSample === group.type_of_sample">
                    <div class="mb-3 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">
                                {{ group.type_of_sample }}
                            </h3>

                            <p class="text-xs text-slate-400">
                                Registro de resultados por parámetro y código de estación
                            </p>
                        </div>

                        <div>
                            <el-button :loading="loadingSubmit" @click="onSubmit(group.items)" class="!rounded-lg"
                                type="primary" plain>
                                <i class="fa-solid fa-cloud-arrow-up mr-2"></i>
                                Guardar
                            </el-button>
                        </div>
                    </div>

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

                                    <p v-if="!row.item_id" class="mt-1 text-[11px] font-semibold text-red-500">
                                        Sin item_id
                                    </p>
                                </div>
                            </template>
                        </el-table-column>

                        <el-table-column prop="condition" label="Acreditación" width="120" align="center" />
                        <el-table-column prop="unit_measurement" label="Unidad" width="120" align="center" />
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

                                            <p v-if="station.code_sample" class="text-[11px] text-slate-400">
                                                Muestra: {{ station.code_sample }}
                                            </p>
                                        </div>

                                        <div class="col-span-12 md:col-span-7">
                                            <el-input v-model="station.result" placeholder="Ingrese resultado" clearable
                                                class="input-custom" />
                                        </div>
                                    </div>
                                </div>

                                <el-alert v-else title="Este parámetro no está asociado a ninguna cadena de custodia"
                                    type="warning" show-icon :closable="false" />
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </template>

            <el-empty v-if="!results?.length" description="No hay resultados para mostrar" />
        </template>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
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

const getShow = async (orderId) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`lab-orders-show/${orderId}`)

        results.value = data.data ?? []
        tabSample.value = results.value?.[0]?.type_of_sample ?? ''
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

            payload.push({
                item_id: item.item_id,
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
    }
})
</script>

<style scoped>
:deep(.input-custom .el-input__wrapper) {
    border-radius: 10px !important;
}
</style>
