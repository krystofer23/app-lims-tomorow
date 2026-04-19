<template>
    <el-dialog v-model="osViewModalStore.state" width="1000" destroy-on-close align-center
        class="os-view-dialog !rounded-2xl">
        <template #header>
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-md">
                    <i class="fa-regular fa-file-lines text-lg"></i>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-slate-800">Detalle de orden de servicio</h2>
                    <p class="text-sm text-slate-500">
                        Vista informativa de los datos generales y monitoreo
                    </p>
                </div>
            </div>
        </template>
        <div class="max-h-[70vh] overflow-y-auto pr-1">
            <el-tabs v-model="activeName" class="px-2" v-loading="osViewModalStore.loading">
                <el-tab-pane v-if="order" label="Datos Generales" name="first">
                    <div class="space-y-6">
                        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">Datos generales</h3>
                                        <p class="text-sm text-slate-500">Información principal de la orden</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-4 px-5 py-5">
                                <div class="col-span-12 md:col-span-6 xl:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Empresa</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.company?.business_name ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Fecha de
                                        atención</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.date_attention ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Contacto</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.contact?.user?.full_name ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-span-12 xl:col-span-8">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Dirección</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.direction ?? order?.quote?.direction ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-span-12 xl:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Referencia</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.reference }}
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">Monitoreo</h3>
                                        <p class="text-sm text-slate-500">Datos operativos del servicio de monitoreo</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-4 px-5 py-5">
                                <div class="col-span-12 md:col-span-6 xl:col-span-4">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Procedencia</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.origin }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-8">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Proyecto</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.project }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Fecha de salida</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.date_output }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Fecha de
                                        inducción</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.date_induction }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">
                                        Fecha inicio del monitoreo
                                    </label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.date_monitoring_init }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">
                                        Fecha fin del monitoreo
                                    </label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ order?.date_monitoring_end }}
                                    </div>
                                </div>

                                <div class="col-span-12 xl:col-span-6">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">Especificar
                                        detalles</label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700 min-h-[48px]">
                                        {{ order?.details }}
                                    </div>
                                </div>

                                <div class="col-span-12 xl:col-span-6">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">
                                        Estaciones de monitoreo
                                    </label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700 min-h-[48px]">
                                        {{ order?.stations_monitoring }}
                                    </div>
                                </div>

                                <div class="col-span-12">
                                    <label class="mb-1 block text-sm font-medium text-slate-500">
                                        Proyecto de monitoreo
                                    </label>
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700 min-h-[48px]">
                                        {{ order?.project_monitoring }}
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">Observaciones</h3>
                                        <p class="text-sm text-slate-500">Comentarios adicionales del servicio</p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 py-5">
                                <div
                                    class="rounded-2xl bg-slate-50 px-4 py-4 text-sm leading-6 text-slate-700 min-h-[70px]">
                                    {{ order?.observations }}
                                </div>
                            </div>
                        </section>
                    </div>
                </el-tab-pane>
                <el-tab-pane v-if="order" label="Matrices" name="second">
                    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                    <i class="fa-solid fa-table"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800">Matrices</h3>
                                    <p class="text-sm text-slate-500">Listado de matrices relacionadas</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 py-5">
                            <div class="overflow-hidden rounded-2xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-slate-600">#</th>
                                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Tipo
                                            </th>
                                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                                Concepto</th>
                                            <th class="px-4 py-3 text-center font-semibold text-slate-600">
                                                Cantidad/N° de muestras
                                            </th>
                                            <th class="px-4 py-3 text-center font-semibold text-slate-600">
                                                Equipos
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100 bg-white !capitalize">
                                        <tr v-for="(row, index) in order?.items ?? []" :key="index"
                                            class="transition hover:bg-slate-50">
                                            <td class="px-4 py-4">
                                                {{ index + 1 }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                                                    :class="row.type === 'matriz'
                                                        ? 'bg-sky-50 text-sky-700'
                                                        : 'bg-emerald-50 text-emerald-700'">
                                                    {{ row.type === 'matriz' ? 'Matriz' : 'Servicio' }}
                                                </span>

                                                <div v-if="row.type === 'service'" class="mt-3">
                                                    <span class="mb-1 block font-medium text-slate-600">Días</span>
                                                    <el-input v-model="row.item.days" size="small" placeholder="Días" />
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-slate-700">
                                                <p v-tippy="row?.item?.description || '-'"
                                                    class="font-semibold text-slate-800">
                                                    {{ row?.item?.description || '-' }}
                                                </p>

                                                <p v-if="row?.item?.methodologie?.description"
                                                    v-tippy="row?.item?.methodologie?.description || 'No registrada'"
                                                    class="mt-1 text-xs text-slate-500 line-clamp-3">
                                                    Metodología:
                                                    {{ row?.item?.methodologie?.description || 'No registrada' }}
                                                </p>
                                            </td>

                                            <td class="px-4 py-4 text-center">
                                                <el-input disabled v-if="row?.type === 'matriz'"
                                                    v-model="row.item.number_samples" size="small" class="!w-[110px]" />
                                                <el-input disabled v-else v-model="row.item.amount" size="small"
                                                    class="!w-[110px]" />
                                            </td>

                                            <td class="px-4 py-4 text-center">
                                                <el-button v-tippy="'Ver equipos'" @click="handleTeam(row)"
                                                    size="small">
                                                    <i class="fa-solid fa-chart-diagram"></i>
                                                </el-button>
                                            </td>
                                        </tr>

                                        <tr v-if="order.items.length === 0">
                                            <td colspan="7" class="px-4 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center text-slate-400">
                                                    <div
                                                        class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                                                        <i class="fa-solid fa-folder-open text-lg"></i>
                                                    </div>
                                                    <p class="text-sm font-semibold text-slate-500">
                                                        Aún no agregaste servicios ni matrices
                                                    </p>
                                                    <span class="mt-1 text-xs text-slate-400">
                                                        Usa los botones superiores para comenzar
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </el-tab-pane>
            </el-tabs>
        </div>

        <template #footer>
            <div class="flex justify-end px-3">
                <el-button plain type="primary" class="!rounded-xl !border-slate-200 !px-5"
                    @click="() => { osViewModalStore.state = false }">
                    Cerrar
                </el-button>
            </div>
        </template>
    </el-dialog>

    <teams-modal :state="state" :matriz-id="matrizId" @close="() => {
        state = false
        matrizId = null
    }" />
</template>

<script setup>
import { computed, ref } from 'vue'
import { useOsViewModalStore } from '../../stores/os-view-modal'
import TeamsModal from '../../views/tenants/order-services/modals/TeamsModal.vue'

const osViewModalStore = useOsViewModalStore()
const order = computed(() => osViewModalStore.order)

const state = ref(false)
const matrizId = ref(null)

const activeName = ref('first')

const handleTeam = (row) => {
    state.value = true
    matrizId.value = row.filable_id
}
</script>

<style scoped>
:deep(.os-view-dialog .el-dialog) {
    border-radius: 1.5rem;
    overflow: hidden;
    padding: 0;
}

:deep(.os-view-dialog .el-dialog__header) {
    margin-right: 0;
    padding: 1.25rem 1.5rem 1rem 1.5rem;
    border-bottom: 1px solid rgb(241 245 249);
}

:deep(.os-view-dialog .el-dialog__body) {
    padding: 1.25rem 1.5rem;
    background: rgb(248 250 252);
}

:deep(.os-view-dialog .el-dialog__footer) {
    padding: 1rem 1.5rem 1.25rem 1.5rem;
    border-top: 1px solid rgb(241 245 249);
    background: white;
}
</style>