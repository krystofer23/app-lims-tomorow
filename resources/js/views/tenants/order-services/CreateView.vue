<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4   lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class=" fa-solid fa-clipboard-list text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            {{ form.id ? 'Editar Orden de Servicio' : 'Generar Orden de Servicio' }}
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Completa la información general, agrega conceptos y revisa el resumen antes de
                        guardar.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
            <el-button class="!rounded-xl !px-5 !h-9" @click="onCancel">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Volver
            </el-button>

            <el-button :loading="loadingSubmit" @click="onSubmit" type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <i class="fa-solid fa-clipboard-list me-2"></i>
                {{ form.id ? 'Guardar cambios' : 'Generar OS' }}
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
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
                            v-model="form.company_id" filterable class="w-full" placeholder="Selecciona una empresa"
                            size="large">
                            <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                :value="company.id" />
                        </el-select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha de atención
                        </label>
                        <el-date-picker v-model="form.date_attention" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Contacto
                        </label>
                        <el-select :loading="loadingContacts" clearable v-model="form.contact_id" filterable
                            class="w-full" placeholder="Selecciona un contacto" size="large">
                            <el-option v-for="contact in contacts" :key="contact.id"
                                :label="contact?.user?.full_name + ' | ' + contact?.type" :value="contact.id" />
                        </el-select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Dirección
                        </label>
                        <el-input v-model="form.direction" placeholder="Ej: Av. Javier Prado 123" size="large" />
                    </div>

                    <div class="md:col-span-2 xl:col-span-3">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Referencia
                        </label>
                        <el-input v-model="form.reference" type="textarea" :autosize="{ minRows: 3, maxRows: 4 }"
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
                        <el-input v-model="form.origin" placeholder="Ej: Huánuco y Cerro de Pasco" size="large" />
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
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha de inducción
                        </label>
                        <el-date-picker v-model="form.date_induction" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha inicio del monitoreo
                        </label>
                        <el-date-picker v-model="form.date_monitoring_init" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha fin del monitoreo
                        </label>
                        <el-date-picker v-model="form.date_monitoring_end" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Especificar detalles
                        </label>
                        <el-input v-model="form.details" type="textarea" :autosize="{ minRows: 3, maxRows: 5 }"
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
                            :autosize="{ minRows: 3, maxRows: 4 }" placeholder="Detalle del proyecto de monitoreo" />
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
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                                    <i class="fa-solid fa-clipboard-list text-sm"></i>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Matrices
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Conceptos principales de la cotización.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <el-button size="small" class="!rounded-lg" plain type="primary"
                                    @click="showMatrixModal = true">
                                    <i class="fa-solid fa-layer-group me-1"></i>
                                    Agregar matrices
                                </el-button>

                                <!-- <el-button size="small" class="!rounded-lg" plain type="success"
                                        @click="showServiceModal = true">
                                        <i class="fa-solid fa-briefcase-medical me-1"></i>
                                        Agregar servicios
                                    </el-button> -->
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th
                                            class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            #
                                        </th>
                                        <th
                                            class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Matriz
                                        </th>
                                        <th
                                            class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Ensayo
                                        </th>
                                        <th
                                            class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Metodología
                                        </th>
                                        <th
                                            class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            N° de muestras
                                        </th>
                                        <th
                                            class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            P. Unit.
                                        </th>
                                        <th
                                            class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Precio
                                        </th>
                                        <th
                                            class="px-3 py-2 text-right text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <tr v-for="(row, index) in form.items" :key="index"
                                        @click="toggleRowSelection(row, $event)" class="transition hover:bg-slate-50">
                                        <td :class="row?.item?.bg" class="px-3 py-2 text-slate-700">
                                            <div class="flex items-center gap-2">
                                                <el-checkbox v-model="row.select" class="!m-0 !p-0" />

                                                <span
                                                    class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 text-[11px] font-bold text-slate-600">
                                                    {{ index + 1 }}
                                                </span>
                                            </div>
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2">
                                            {{ row?.matrix?.description }}
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2 text-slate-700">
                                            {{ row?.reference?.code }}
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2 text-center">
                                            {{ row?.reference?.title }}
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2 text-center">
                                            <el-input size="small" v-model="row.number_samples" />
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2 text-center">
                                            <el-input size="small" v-model="row.unit_price" />
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2 text-center">
                                            {{ row?.price }}
                                        </td>

                                        <td :class="row?.item?.bg" class="relative px-3 py-2 text-right">
                                            <el-button-group size="small">
                                                <el-button v-tippy="'Cambiar valores'" class="!rounded-l-lg">
                                                    <i class="fa-brands fa-unity"></i>
                                                </el-button>
                                                <el-button @click.stop="itemDelete(index)" type="danger" plain
                                                    size="small" class="!rounded-r-lg">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </el-button>
                                            </el-button-group>

                                            <div v-if="row?.item?.frequency_label"
                                                class="absolute right-2 top-1 flex items-center gap-1">
                                                <span
                                                    class="max-w-[100px] truncate rounded-full bg-teal-500 px-2 py-0.5 text-[10px] font-bold text-white">
                                                    {{ row?.item?.frequency_label }}
                                                </span>

                                                <el-button-group>
                                                    <el-button @click.stop="() => {
                                                        row.item.select = null
                                                        row.item.bg = null
                                                        row.item.frequency_label = null
                                                    }" plain size="small" class="!rounded-md" type="warning"
                                                        v-tippy="'Remover frecuencia'">
                                                        <i class="fa-solid fa-eraser text-xs"></i>
                                                    </el-button>
                                                </el-button-group>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-if="form.items.length === 0">
                                        <td colspan="7" class="px-4 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <div
                                                    class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                                    <i class="fa-solid fa-folder-open text-lg"></i>
                                                </div>

                                                <p class="text-xs font-bold text-slate-600">
                                                    Aún no agregaste servicios ni matrices
                                                </p>

                                                <span class="mt-1 text-[11px] text-slate-400">
                                                    Usa los botones superiores para comenzar.
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

                    <el-input v-model="form.observations" type="textarea" :autosize="{ minRows: 8, maxRows: 10 }"
                        placeholder="Observaciones" />
                </div>
            </el-tab-pane>
        </el-tabs>
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
    quote_id: null,
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

const normalizeItems = (items = []) => {
    return items.map((row) => ({
        ...row,
        id: row.id ?? row.filable_id ?? null,
        item: row.item ?? {},
        select: row.select ?? false,
    }))
}

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        const orderServiceId = form.id

        if (form.id) {
            const { data } = await tenant.put(`order-service/${form.id}`, form)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`order-service`, form)
            ElNotification.success(data.message)
        }

        if (orderServiceId) {
            await getOrderService(orderServiceId)
        }
        else {
            resetForm()
        }
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
            form.quote_id = data.data.id
            form.company_id = data.data.company_id
            form.direction = data.data.direction
            form.reference = data.data.reference
            form.contact_id = data.data.contact_id
            form.items = normalizeItems(data.data.items)
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const getOrderService = async (id) => {
    try {
        const { data } = await tenant.get(`order-service/${id}`)

        if (data.data) {
            form.id = data.data.id
            form.quote_id = data.data.quote_id
            form.company_id = data.data.company_id
            form.direction = data.data.direction
            form.date_attention = data.data.date_attention
            form.reference = data.data.reference
            form.observations = data.data.observations
            form.contact_id = data.data.contact_id
            form.origin = data.data.origin
            form.project = data.data.project
            form.date_monitoring_init = data.data.date_monitoring_init
            form.date_monitoring_end = data.data.date_monitoring_end
            form.date_induction = data.data.date_induction
            form.date_output = data.data.date_output
            form.details = data.data.details
            form.stations_monitoring = data.data.stations_monitoring
            form.project_monitoring = data.data.project_monitoring
            form.items = normalizeItems(data.data.items)
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
    matrizId.value = row.id ?? row.filable_id
}

const resetForm = () => {
    form.id = null
    form.quote_id = null
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

    if (route.params.id) {
        getOrderService(route.params.id)
    }
    else if (route.query?.quoteId) {
        getQuote(route.query?.quoteId)
    }
})
</script>

<style scoped>
:deep(.el-input-number .el-input__wrapper) {
    width: 100%;
}

:deep(.el-select__wrapper) {
    border-radius: 12px !important;
}
</style>
