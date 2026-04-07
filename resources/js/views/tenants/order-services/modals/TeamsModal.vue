<template>
    <el-dialog :model-value="state" width="85%" top="5vh" :close-on-click-modal="false" destroy-on-close
        @close="closeModal" class="!rounded-xl" :style="{ width: computedDialogWidth }">
        <template #header>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Equipos por ensayo
                    </h2>
                    <p class="text-sm text-slate-500">
                        Relación de ensayos y equipos asociados a la matriz seleccionada
                    </p>
                </div>
            </div>
        </template>

        <div v-loading="loading" class="min-h-[180px]">
            <div v-if="!loading && teams.length" class="space-y-5">
                <div v-for="(item, index) in teams" :key="item?.essay?.id || index"
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                        Ensayo #{{ index + 1 || '-' }}
                                    </span>

                                    <span v-if="item?.teams?.length"
                                        class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ item.teams.length }} equipo(s)
                                    </span>

                                    <span v-else
                                        class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Sin equipos
                                    </span>
                                </div>

                                <h3 class="text-base font-semibold text-slate-900">
                                    {{ item?.essay?.description || 'Sin descripción' }}
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Unidad
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ item?.essay?.units_measurement?.description || '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Condición
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ item?.essay?.condition?.description || '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        LCM
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-700">
                                        {{ item?.essay?.lcm || '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <div v-if="item?.teams?.length" class="overflow-hidden rounded-2xl border border-slate-200">
                            <el-table :data="item.teams" stripe class="w-full">
                                <el-table-column type="index" label="#" width="60" />

                                <el-table-column prop="code" label="Código" min-width="140" />

                                <el-table-column label="Equipo" min-width="240">
                                    <template #default="{ row }">
                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ row.description || '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ row.denomination || '-' }}
                                            </p>
                                        </div>
                                    </template>
                                </el-table-column>

                                <el-table-column label="Marca / Modelo" min-width="180">
                                    <template #default="{ row }">
                                        <div>
                                            <p class="text-sm font-medium text-slate-700">
                                                {{ row.brand_manufacturer || '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ row.model || '-' }}
                                            </p>
                                        </div>
                                    </template>
                                </el-table-column>

                                <el-table-column prop="serie" label="Serie" min-width="140" />

                                <el-table-column label="Área" min-width="140">
                                    <template #default="{ row }">
                                        {{ row?.area?.description || '-' }}
                                    </template>
                                </el-table-column>

                                <!-- <el-table-column label="Estado" min-width="120" align="center">
                                    <template #default="{ row }">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" :class="row.status === 'IN'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-rose-100 text-rose-700'">
                                            {{ row.status || '-' }}
                                        </span>
                                    </template>
                                </el-table-column>

                                <el-table-column label="Operativo" min-width="130" align="center">
                                    <template #default="{ row }">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" :class="row.active
                                            ? 'bg-sky-100 text-sky-700'
                                            : 'bg-slate-200 text-slate-600'">
                                            {{ row.active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </template>
                                </el-table-column>

                                <el-table-column prop="operational_status" label="Estado operativo" min-width="160" />

                                <el-table-column prop="os" label="OS" min-width="120" /> -->
                            </el-table>
                        </div>

                        <div v-else
                            class="flex min-h-[120px] flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-center">
                            <div
                                class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                                <i class="fa-solid fa-toolbox"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600">
                                Este ensayo no tiene equipos relacionados
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                No se encontraron registros para este ensayo
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else-if="!loading && !teams.length"
                class="flex min-h-[280px] flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 text-center">
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-slate-400 shadow-sm">
                    <i class="fa-solid fa-circle-info text-xl"></i>
                </div>
                <p class="text-base font-semibold text-slate-700">
                    No hay información para mostrar
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    No se encontraron ensayos o equipos asociados a esta matriz
                </p>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-end">
                <el-button class="!rounded-xl !px-5" @click="closeModal">
                    Cerrar
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption'
import tenant from '../../../../stores/tenant'

const props = defineProps({
    state: {
        type: Boolean,
        default: false
    },
    matrizId: {
        type: Number,
        default: null
    }
})

const emit = defineEmits(['close'])

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
        return "60%";
    }
});


const loading = ref(false)
const teams = ref([])

const closeModal = () => {
    emit('close')
}

const getTeams = async () => {
    if (!props.matrizId) return

    loading.value = true

    try {
        const { data } = await tenant.get(`order-service/teams/${props.matrizId}`)

        if (data?.data) {
            teams.value = data.data
        } else {
            teams.value = []
        }
    } catch (e) {
        teams.value = []
        handleErrorsExeption(e)
    } finally {
        loading.value = false
    }
}

watch(
    () => props.state,
    (newVal) => {
        if (newVal) {
            getTeams()
        } else {
            teams.value = []
        }
    }
)
</script>

<style scoped>
:deep(.el-dialog) {
    border-radius: 24px;
    overflow: hidden;
}

:deep(.el-dialog__header) {
    margin-right: 0;
    padding: 20px 24px 0 24px;
}

:deep(.el-dialog__body) {
    padding: 20px 24px;
}

:deep(.el-dialog__footer) {
    padding: 0 24px 20px 24px;
}

:deep(.el-table th.el-table__cell) {
    background-color: #f8fafc !important;
    color: #475569;
    font-weight: 700;
}

:deep(.el-table td.el-table__cell) {
    vertical-align: top;
}
</style>