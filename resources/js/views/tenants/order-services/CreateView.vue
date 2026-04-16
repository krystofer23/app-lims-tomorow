<template>
    <div class="w-full space-y-6">
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-5">
                    <div class="flex flex-col gap-4 rounded-3xl md:flex-row md:items-center md:justify-between">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                <i class="fa-solid fa-file-export text-xl"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold text-slate-900">
                                    {{ form.id ? 'Editar Orden de Servicio' : 'Generar Orden de Servicio' }}
                                </h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Completa la información general, agrega conceptos y revisa el resumen antes de
                                    guardar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-6">
                    <el-tabs v-model="activeTab" class="os-tabs">
                        <el-tab-pane name="general">
                            <template #label>
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-building-circle-check"></i>
                                    <span>Datos generales</span>
                                </span>
                            </template>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                                <div class="xl:col-span-2">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Empresa
                                    </label>
                                    <el-select :remote-method="remoteMethodCompany" :loading="loadingCompany"
                                        v-model="form.company_id" filterable class="w-full"
                                        placeholder="Selecciona una empresa" size="large">
                                        <el-option v-for="company in companies" :key="company.id"
                                            :label="company.business_name" :value="company.id" />
                                    </el-select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Fecha de atención
                                    </label>
                                    <el-date-picker v-model="form.date_attention" type="date" class="!w-full"
                                        placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                        size="large" />
                                </div>

                                <div class="xl:col-span-2">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Contacto
                                    </label>
                                    <el-select :loading="loadingContacts" clearable v-model="form.contact_id" filterable
                                        class="w-full" placeholder="Selecciona un contacto" size="large">
                                        <el-option v-for="contact in contacts" :key="contact.id"
                                            :label="contact?.user?.full_name + ' | ' + contact?.type"
                                            :value="contact.id" />
                                    </el-select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Dirección
                                    </label>
                                    <el-input v-model="form.direction" placeholder="Ej: Av. Javier Prado 123"
                                        size="large" />
                                </div>

                                <div class="md:col-span-2 xl:col-span-3">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Referencia
                                    </label>
                                    <el-input v-model="form.reference" type="textarea"
                                        :autosize="{ minRows: 3, maxRows: 4 }"
                                        placeholder="Detalle breve o referencia de la orden de servicio" />
                                </div>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane name="monitoring">
                            <template #label>
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                    <span>Monitoreo</span>
                                </span>
                            </template>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Procedencia
                                    </label>
                                    <el-input v-model="form.origin" placeholder="Ej: Huánuco y Cerro de Pasco"
                                        size="large" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Proyecto
                                    </label>
                                    <el-input v-model="form.project" placeholder="Nombre del proyecto" size="large" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Fecha de salida
                                    </label>
                                    <el-date-picker v-model="form.date_output" type="date" class="!w-full"
                                        placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                        size="large" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Fecha de inducción
                                    </label>
                                    <el-date-picker v-model="form.date_induction" type="date" class="!w-full"
                                        placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                        size="large" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Fecha inicio del monitoreo
                                    </label>
                                    <el-date-picker v-model="form.date_monitoring_init" type="date" class="!w-full"
                                        placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                        size="large" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Fecha fin del monitoreo
                                    </label>
                                    <el-date-picker v-model="form.date_monitoring_end" type="date" class="!w-full"
                                        placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                        size="large" />
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Especificar detalles
                                    </label>
                                    <el-input v-model="form.details" type="textarea"
                                        :autosize="{ minRows: 3, maxRows: 5 }"
                                        placeholder="Ej: Toma de muestras y mediciones de campo" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Estaciones de monitoreo
                                    </label>
                                    <el-input v-model="form.stations_monitoring" type="textarea"
                                        :autosize="{ minRows: 3, maxRows: 4 }"
                                        placeholder="Ej: Adjunta en la programación enviada" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Proyecto de monitoreo
                                    </label>
                                    <el-input v-model="form.project_monitoring" type="textarea"
                                        :autosize="{ minRows: 3, maxRows: 4 }"
                                        placeholder="Detalle del proyecto de monitoreo" />
                                </div>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane name="matrices">
                            <template #label>
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>Matrices</span>
                                </span>
                            </template>

                            <div class="mb-3 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="col-span-3"></div>
                                <div class="w-full max-w-md rounded-xl bg-blue-50 border border-blue-100 p-4">
                                    <div class="mb-2">
                                        <p class="text-sm font-semibold text-blue-900">
                                            Frecuencia de evaluación
                                        </p>
                                    </div>

                                    <el-select size="small" v-model="frequency" placeholder="Selecciona una frecuencia"
                                        class="w-full" clearable filterable>
                                        <el-option v-for="item in frequencies" :key="item.value" :label="item.label"
                                            :value="item.value" />
                                    </el-select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">
                                            Conceptos principales
                                        </h4>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Agrega servicios y matrices de la orden.
                                        </p>
                                    </div>

                                    <el-button class="!rounded-xl" plain type="primary" @click="showMatrixModal = true">
                                        <i class="fa-solid fa-layer-group me-2"></i>
                                        Agregar matrices
                                    </el-button>
                                </div>

                                <div class="overflow-hidden rounded-2xl border border-slate-200">
                                    <div class="overflow-x-auto">
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
                                                    <th class="px-4 py-3 text-right font-semibold text-slate-600">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                <tr v-for="(row, index) in form.items" :key="index"
                                                    class="transition hover:bg-slate-50"
                                                    @click="toggleRowSelection(row, $event)">
                                                    <td :class="row?.item?.bg" class="px-4 py-4 text-slate-700">
                                                        <div class="gap-2 items-center flex">
                                                            <el-checkbox v-model="row.select" class="!m-0 !p-0" />
                                                            {{ index + 1 }}
                                                        </div>
                                                    </td>

                                                    <td :class="row?.item?.bg" class="px-4 py-4">
                                                        <span
                                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                                                            :class="row.type === 'matriz'
                                                                ? 'bg-sky-50 text-sky-700'
                                                                : 'bg-emerald-50 text-emerald-700'">
                                                            {{ row.type === 'matriz' ? 'Matriz' : 'Servicio' }}
                                                        </span>

                                                        <div v-if="row.type === 'service'" class="mt-3">
                                                            <span
                                                                class="mb-1 block font-medium text-slate-600">Días</span>
                                                            <el-input v-model="row.item.days" size="small"
                                                                placeholder="Días" />
                                                        </div>
                                                    </td>

                                                    <td :class="row?.item?.bg" class="px-4 py-4 text-slate-700">
                                                        <p v-tippy="row?.item?.description || '-'"
                                                            class="font-semibold text-slate-800">
                                                            {{ row?.item?.description || '-' }}
                                                        </p>

                                                        <p v-if="row?.item?.methodologie?.description"
                                                            v-tippy="row?.item?.methodologie?.description || 'No registrada'"
                                                            class="mt-1 text-xs text-slate-500 line-clamp-3">
                                                            Metodología:
                                                            {{ row?.item?.methodologie?.description || 'No registrada'
                                                            }}
                                                        </p>
                                                    </td>

                                                    <td :class="row?.item?.bg" class="px-4 py-4 text-center">
                                                        <el-input v-if="row?.type === 'matriz'"
                                                            v-model="row.item.number_samples" size="small"
                                                            class="!w-[110px]" />
                                                        <el-input v-else v-model="row.item.amount" size="small"
                                                            class="!w-[110px]" />
                                                    </td>

                                                    <td :class="row?.item?.bg" class="px-4 py-4 text-center">
                                                        <el-button v-tippy="'Ver equipos'" @click="handleTeam(row)"
                                                            size="small">
                                                            <i class="fa-solid fa-chart-diagram"></i>
                                                        </el-button>
                                                    </td>

                                                    <td :class="row?.item?.bg" class="px-4 py-4 text-right relative">
                                                        <el-button @click="itemDelete(index)" type="danger" plain
                                                            size="small" class="!rounded-lg">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </el-button>

                                                        <div class="absolute top-0 right-1 flex gap-2"
                                                            v-if="row?.item?.frequency_label">
                                                            <span
                                                                class="bg-[#1abc9c] px-2 pt-1 text-white rounded-lg text-xs font-medium w-full h-[24px] truncate">
                                                                {{ row?.item?.frequency_label }}
                                                            </span>
                                                            <el-button @click="() => {
                                                                row.item.select = null
                                                                row.item.bg = null
                                                                row.item.frequency_label = null
                                                            }" plain size="small" class="!rounded-lg" type="warning"
                                                                v-tippy="'Remover frecuencia'">
                                                                <i class="fa-solid fa-eraser"></i>
                                                            </el-button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr v-if="form.items.length === 0">
                                                    <td colspan="7" class="px-4 py-12 text-center">
                                                        <div
                                                            class="flex flex-col items-center justify-center text-slate-400">
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
                            </div>
                        </el-tab-pane>

                        <el-tab-pane name="observations">
                            <template #label>
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-note-sticky"></i>
                                    <span>Observaciones</span>
                                </span>
                            </template>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Observaciones generales</h4>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Agrega indicaciones, condiciones o comentarios importantes de la orden de
                                        servicio.
                                    </p>
                                </div>

                                <el-input v-model="form.observations" type="textarea"
                                    :autosize="{ minRows: 8, maxRows: 10 }" placeholder="Observaciones" />
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>

                <div class="px-6 pb-6">
                    <div class="flex justify-end flex-wrap">
                        <el-button class="!rounded-xl !px-5" @click="onCancel">
                            <i class="fa-solid fa-arrow-left me-2"></i>
                            Volver
                        </el-button>

                        <el-button type="primary"
                            class="!rounded-xl !border-emerald-500 !bg-emerald-500 !px-5 hover:!border-emerald-600 hover:!bg-emerald-600"
                            :loading="loadingSubmit" @click="onSubmit">
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            {{ form.id ? 'Guardar cambios' : 'Generar OS' }}
                        </el-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <matriz-modal :items="form.items" :show-matrix-modal="showMatrixModal" @close="() => {
        showMatrixModal = false
    }" />

    <teams-modal :state="state" :matriz-id="matrizId" @close="() => {
        state = false
        matrizId = null
    }" />
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import tenant from '../../../stores/tenant';
import { useListStore } from '../../../stores/list';
import MatrizModal from '../quotes/modal/MatrizModal.vue';
import TeamsModal from './modals/TeamsModal.vue';
import { ElNotification } from 'element-plus';

const listStore = useListStore()
const companies = computed(() => listStore.companies)
const frequency = ref(null)
const loadingContacts = computed(() => listStore.loadingContacts)
const contacts = computed(() => listStore.contacts)

const activeTab = ref('general')
const state = ref(false)
const matrizId = ref(null)

const loadingCompany = ref(false)

const remoteMethodCompany = async (q) => {
    loadingCompany.value = true
    await listStore.getCompanies(q)
    loadingCompany.value = false
}

const form = reactive({
    id: null,
    company_id: null,
    direction: null,
    date_attention: null,
    version: null,
    code: null,
    items_total: null,
    other_expenses_total: null,
    igv: null,
    subtotal: null,
    total: null,
    reference: null,
    observations: null,
    contact_id: null,

    origin: null,
    project: null,
    date_monitoring_init: null,
    date_monitoring_end: null,
    date_induction: null,
    date_output: null,
    details: null,
    stations_monitoring: null,
    project_monitoring: null,

    items: [],
})

const frequencies = [
    { value: "biweekly", label: "Quincenal (cada 15 días)", bg: "bg-lime-50" },
    { value: "monthly", label: "Mensual (cada mes)", bg: "bg-teal-50" },
    { value: "bimonthly", label: "Bimestral (cada 2 meses)", bg: "bg-cyan-50" },
    { value: "quarterly", label: "Trimestral (cada 3 meses)", bg: "bg-sky-50" },
    { value: "four_monthly", label: "Cuatrimestral (cada 4 meses)", bg: "bg-blue-50" },
    { value: "semiannual", label: "Semestral (cada 6 meses)", bg: "bg-indigo-50" },
    { value: "annual", label: "Anual (cada 12 meses)", bg: "bg-violet-50" },
    { value: "decenal", label: "Decenal (cada 10 días)", bg: "bg-amber-50" },
    { value: "biennial", label: "Bienal (cada 2 años)", bg: "bg-yellow-50" },
    { value: "triennial", label: "Trienal (cada 3 años)", bg: "bg-orange-50" },
    { value: "quinquennial", label: "Quinquenal (cada 5 años)", bg: "bg-slate-100" },
]

const showMatrixModal = ref(false)
const loadingSubmit = ref(false)
const router = useRouter()
const route = useRoute()

const itemDelete = (index) => {
    form.items.splice(index, 1)
}

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (form.id) {
            const { data } = await tenant.put(`order-service/${form.id}`, form)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`order-service`, form)
            ElNotification.success(data.message)
        }

        resetForm()
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const onCancel = () => {
    router.push({ name: 'orders-services' })
}

const selectedFrequency = computed(() => {
    return frequencies.find(item => item.value === frequency.value) || null
})

const applyFrequencyToSelected = () => {
    if (!selectedFrequency.value) return

    form.items.forEach(item => {
        if (item.select) {
            item.item.bg = selectedFrequency.value.bg
            item.item.frequency = selectedFrequency.value.value
            item.item.frequency_label = selectedFrequency.value.label
        }
    })
}

const toggleRowSelection = (row, event) => {
    const tag = event.target.tagName.toLowerCase()
    const className = event.target.className?.toString() || ''

    const ignoredTags = ['input', 'textarea', 'button', 'svg', 'path']
    const ignoredClasses = ['el-input', 'el-checkbox', 'el-button']

    if (
        ignoredTags.includes(tag) ||
        ignoredClasses.some(cls => className.includes(cls))
    ) {
        return
    }

    row.select = !row.select
}

watch(() => form.items, (newVal) => {
    newVal.forEach((row) => {
        if (row.select === undefined) row.select = false
        if (row.bg === undefined) row.bg = ''
        if (row.frequency === undefined) row.frequency = null
        if (row.frequency_label === undefined) row.frequency_label = null
    })
}, { deep: true, immediate: true })

watch(() => frequency.value, () => {
    applyFrequencyToSelected()
})

const getQuote = async (quoteId) => {
    try {
        const { data } = await tenant.get(`quote/${quoteId}`, {
            params: { is_order_service: true }
        })

        if (data.data) {
            form.company_id = data.data.company_id
            form.direction = data.data.direction
            form.reference = data.data.reference
            form.contact_id = data.data.contact_id
            form.items = data.data.items
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

watch(() => form.company_id, (newVal) => {
    if (newVal) {
        listStore.getContacts(null, newVal)
        listStore.getCompanies(newVal)
    }
})

const handleTeam = (row) => {
    state.value = true
    matrizId.value = row.id
}

const resetForm = () => {
    form.id = null
    form.company_id = null
    form.direction = null
    form.date_attention = null
    form.version = null
    form.code = null
    form.items_total = null
    form.other_expenses_total = null
    form.igv = null
    form.subtotal = null
    form.total = null
    form.reference = null
    form.observations = null
    form.contact_id = null
    form.origin = null
    form.project = null
    form.date_monitoring_init = null
    form.date_monitoring_end = null
    form.date_induction = null
    form.date_output = null
    form.details = null
    form.stations_monitoring = null
    form.project_monitoring = null
    form.items = []
}

onMounted(() => {
    const date = new Date()

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    form.date_attention = `${year}-${month}-${day}`

    listStore.getCompanies()

    if (route.query?.quoteId) {
        getQuote(route.query?.quoteId)
        form.quote_id = route.query?.quoteId;
    }
})
</script>

<style scoped></style>