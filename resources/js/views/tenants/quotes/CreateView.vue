<template>
    <div class="w-full space-y-6">
        <div
            class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                    <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        {{ form.id ? 'Editar cotización' : 'Registrar cotización' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Completa la información general, agrega conceptos y revisa el resumen económico antes de
                        guardar.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap">
                <el-button class="!rounded-xl !px-5" @click="onCancel">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Volver
                </el-button>

                <el-button type="primary"
                    class="!rounded-xl !border-emerald-500 !bg-emerald-500 !px-5 hover:!border-emerald-600 hover:!bg-emerald-600"
                    :loading="loadingSubmit" @click="onSubmit">
                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    {{ form.id ? 'Guardar cambios' : 'Guardar cotización' }}
                </el-button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Fecha de atención</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ form.date_attention || 'Sin fecha' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Conceptos agregados</p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ form.items.length + form.other_expenses.length }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-emerald-600">Total actual</p>
                <p class="mt-2 text-xl font-bold text-emerald-700">
                    S/ {{ formatMoney(total) }}
                </p>
            </div>
            <div class="w-full max-w-md rounded-xl bg-blue-50 border border-blue-100 p-4">
                <div class="mb-2">
                    <p class="text-sm font-semibold text-blue-900">
                        Frecuencia de evaluación
                    </p>
                </div>

                <el-select size="small" v-model="frequency" placeholder="Selecciona una frecuencia" class="w-full"
                    clearable filterable>
                    <el-option v-for="item in frequencies" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
            <div class="xl:col-span-4 space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-start gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <i class="fa-solid fa-building-circle-check"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Datos generales</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Información principal de la cotización.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Empresa
                            </label>
                            <el-select v-model="form.company_id" filterable class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Dirección
                            </label>
                            <el-input v-model="form.direction" placeholder="Ej: Av. Javier Prado 123" size="large" />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Fecha de atención
                            </label>
                            <el-date-picker v-model="form.date_attention" type="date" class="!w-full"
                                placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                size="large" />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Referencia
                            </label>
                            <el-input v-model="form.reference" type="textarea" :autosize="{ minRows: 4, maxRows: 5 }"
                                placeholder="Detalle breve o referencia de la cotización" />
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-start gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Resumen económico</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Totales actualizados automáticamente.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Servicios y matrices</span>
                            <span class="text-sm font-semibold text-slate-800">S/ {{ formatMoney(itemsTotal) }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Otros gastos</span>
                            <span class="text-sm font-semibold text-slate-800">S/ {{ formatMoney(otherExpensesTotal)
                                }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Subtotal</span>
                            <span class="text-sm font-semibold text-slate-800">S/ {{ formatMoney(subtotal) }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">IGV</span>
                            <span class="text-sm font-semibold text-slate-800">S/ {{ formatMoney(igv) }}</span>
                        </div>

                        <div
                            class="flex items-center justify-between rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-4">
                            <span class="text-sm font-semibold text-emerald-800">Total</span>
                            <span class="text-xl font-bold text-emerald-700">S/ {{ formatMoney(total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-8 space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">
                                    Servicios y matrices
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Agrega los conceptos principales de la cotización.
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <el-button class="!rounded-xl" plain type="primary" @click="showMatrixModal = true">
                                    <i class="fa-solid fa-layer-group me-2"></i>
                                    Agregar matrices
                                </el-button>

                                <el-button class="!rounded-xl" plain type="success" @click="showServiceModal = true">
                                    <i class="fa-solid fa-briefcase-medical me-2"></i>
                                    Agregar servicios
                                </el-button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-600">#</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Tipo</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Concepto</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Cantidad/N° de
                                        muestras</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-600">P. Unit.</th>
                                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Importe</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-600">Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="(row, index) in form.items" :key="index"
                                    @click="toggleRowSelection(row, $event)" class="transition hover:bg-slate-50">
                                    <td :class="row?.item?.bg" class="px-4 py-4 text-slate-700">
                                        <div class="gap-2 items-center flex">
                                            <el-checkbox v-model="row.select" class="!m-0 !p-0"></el-checkbox>
                                            {{ index + 1 }}
                                        </div>
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" :class="row.type === 'matriz'
                                            ? 'bg-sky-50 text-sky-700'
                                            : 'bg-emerald-50 text-emerald-700'">
                                            {{ row.type === 'matriz' ? 'Matriz' : 'Servicio' }}
                                        </span>

                                        <span v-if="row.type === 'service'"
                                            class="mt-3 block font-medium text-slate-600">Dias</span>
                                        <el-input v-if="row.type === 'service'" v-model="row.item.days" size="small"
                                            placeholder="Dias" />
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4 text-slate-700">
                                        <p v-tippy="row?.item?.description || '-'" class="font-semibold text-slate-800">
                                            {{ row?.item?.description || '-' }}
                                        </p>

                                        <p v-tippy="row?.item?.methodologie?.description || 'No registrada'"
                                            class="mt-1 text-xs text-slate-500 line-clamp-3">
                                            Metodología:
                                            {{ row?.item?.methodologie?.description || 'No registrada' }}
                                        </p>
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4 text-center">
                                        <el-input v-if="row?.type === 'matriz'" v-model="row.item.number_samples"
                                            size="small" class="!w-[110px]" />
                                        <el-input v-else v-model="row.item.amount" size="small" class="!w-[110px]" />
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4 text-right">
                                        <el-input v-model="row.item.unit_price" size="small" class="!w-[120px]" />
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4 text-right">
                                        <el-input v-model="row.item.price" size="small" disabled class="!w-[120px]" />
                                    </td>

                                    <td :class="row?.item?.bg" class="px-4 py-4 text-right relative">
                                        <el-button @click="itemDelete(index)" type="danger" plain size="small"
                                            class="!rounded-lg">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </el-button>

                                        <div class="absolute top-0 right-1 flex gap-2" v-if="row.frequency_label">
                                            <span
                                                class="bg-[#1abc9c] px-2 pt-1 text-white rounded-lg text-xs font-medium w-full h-[24px] truncate">
                                                {{ row?.frequency_label }}
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

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">
                                    Otros gastos
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Registra gastos adicionales como movilidad, viáticos, materiales u otros conceptos.
                                </p>
                            </div>

                            <el-button class="!rounded-xl" plain type="warning" @click="() => {
                                state = true
                            }">
                                <i class="fa-solid fa-plus me-2"></i>
                                Agregar gasto
                            </el-button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="ps-4 pe-3 py-3 text-left font-semibold text-slate-600">#</th>
                                    <th class="ps-4 pe-3 py-3 text-left font-semibold text-slate-600">Descripción</th>
                                    <th class="ps-4 pe-3 py-3 text-center font-semibold text-slate-600">Dias</th>
                                    <th class="ps-4 pe-3 py-3 text-center font-semibold text-slate-600">Cantidad</th>
                                    <th class="ps-4 pe-3 py-3 text-right font-semibold text-slate-600">P. Unit.</th>
                                    <th class="ps-4 pe-3 py-3 text-right font-semibold text-slate-600">Importe</th>
                                    <th class="ps-4 pe-3 py-3 text-right font-semibold text-slate-600">Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="(expense, index) in form.other_expenses" :key="index"
                                    class="transition hover:bg-slate-50">
                                    <td class="ps-4 pe-3 py-4 text-slate-700">{{ index + 1 }}</td>

                                    <td class="px-3 py-4">
                                        <el-input v-model="expense.description" size="small"
                                            placeholder="Ej: Movilidad, viáticos, materiales..." />
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <el-input v-model="expense.days" size="small" class="!w-[60px]" />
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <el-input v-model="expense.amount" size="small" class="!w-[60px]" />
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <el-input v-model="expense.unit_price" size="small" class="!w-[60px]" />
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <el-input v-model="expense.price" size="small" disabled class="!w-[120px]" />
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <el-button @click="removeOtherExpense(index)" type="danger" plain size="small"
                                            class="!rounded-lg">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </el-button>
                                    </td>
                                </tr>

                                <tr v-if="form.other_expenses.length === 0">
                                    <td colspan="6" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div
                                                class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                                                <i class="fa-solid fa-receipt text-lg"></i>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-500">
                                                No hay otros gastos registrados
                                            </p>
                                            <span class="mt-1 text-xs text-slate-400">
                                                Agrega gastos solo si aplican a esta cotización
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-start gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                            <i class="fa-solid fa-note-sticky"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Observaciones</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Agrega indicaciones, condiciones o comentarios importantes de la cotización.
                            </p>
                        </div>
                    </div>

                    <el-input v-model="form.observations" type="textarea" :autosize="{ minRows: 5, maxRows: 7 }"
                        placeholder="Ej: La cotización está sujeta a disponibilidad, tiempos de atención, condiciones de muestreo u observaciones comerciales." />
                </div>
            </div>
        </div>
    </div>

    <services-modal :items="form.items" :show-service-modal="showServiceModal" @close="() => {
        showServiceModal = false
    }" />

    <matriz-modal :items="form.items" :show-matrix-modal="showMatrixModal" @close="() => {
        showMatrixModal = false
    }" />

    <logistic-cast-modal :items="form.other_expenses" :state="state" @close="() => {
        state = false
    }" />
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import ServicesModal from './modal/ServicesModal.vue'
import MatrizModal from './modal/MatrizModal.vue'
import { useListStore } from '../../../stores/list'
import { watch } from 'vue'
import tenant from '../../../stores/tenant'
import { ElNotification } from 'element-plus'
import LogisticCastModal from './modal/LogisticCastModal.vue'
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption'

const state = ref(false)
const router = useRouter()
const loadingSubmit = ref(false)
const listStore = useListStore()
const companies = computed(() => listStore.companies)

const frequency = ref(null);

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
];

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
    items: [],
    other_expenses: []
})

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

watch(() => frequency.value, () => {
    applyFrequencyToSelected()
})

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

const resetForm = () => {
    form.id = null
    form.company_id = null
    form.direction = null
    form.version = null
    form.code = null
    form.items_total = null
    form.other_expenses_total = null
    form.igv = null
    form.subtotal = null
    form.total = null
    form.reference = null
    form.observations = null
    form.items = []
    form.other_expenses = []
}

const addOtherExpense = () => {
    form.other_expenses.push({
        type: 'other',
        description: '',
        amount: 1,
        unit_price: 0,
        price: 0
    })
}

const removeOtherExpense = (index) => {
    form.other_expenses.splice(index, 1)
}

const showMatrixModal = ref(false)
const showServiceModal = ref(false)

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        form.items_total = itemsTotal.value
        form.other_expenses_total = otherExpensesTotal.value
        form.igv = igv.value
        form.subtotal = subtotal.value
        form.total = total.value

        if (form.id) {
            const { data } = await tenant.put(`quote/${form.id}`, form)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`quote`, form)
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
    router.push({ name: 'quotes' })
}

const itemDelete = (index) => {
    form.items.splice(index, 1)
}

const itemsTotal = computed(() => {
    return form.items.reduce((acc, item) => {
        return acc + Number(item?.item?.price ?? 0)
    }, 0)
})

const otherExpensesTotal = computed(() => {
    return form.other_expenses.reduce((acc, expense) => {
        return acc + Number(expense?.price ?? 0)
    }, 0)
})

const subtotal = computed(() => {
    return itemsTotal.value + otherExpensesTotal.value
})

const igv = computed(() => {
    return subtotal.value * 0.18
})

const total = computed(() => {
    return subtotal.value + igv.value
})

const formatMoney = (value) => {
    return Number(value || 0).toFixed(2)
}

watch(() => form.items, (newVal) => {
    newVal.forEach((row) => {
        const unitPrice = Number(row?.item?.unit_price ?? 0)

        const quantity = row?.type === 'service'
            ? Number(row?.item?.amount ?? 0)
            : Number(row?.item?.number_samples ?? 0)

        row.item.price = row?.type === 'service' ? unitPrice * quantity * Number(row?.item.days ?? 1) : unitPrice * quantity
    })
}, { deep: true })

watch(() => form.other_expenses, (newVal) => {
    newVal.forEach((expense) => {
        const unitPrice = Number(expense?.unit_price ?? 0)
        const quantity = Number(expense?.amount ?? 0)
        const days = Number(expense?.days ?? 0)

        expense.price = unitPrice * quantity * days
    })
}, { deep: true })

onMounted(() => {
    listStore.getCompanies()

    const date = new Date()

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    form.date_attention = `${year}-${month}-${day}`
})
</script>

<style scoped>
s:deep(.el-input-number .el-input__wrapper) {
    width: 100%;
}
</style>