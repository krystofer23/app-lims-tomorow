<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4   lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-building-user text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            {{ company.id ? 'Editar empresa' : 'Registrar empresa' }}
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Completa la información general.
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
                <i class="fa-solid fa-building-user mr-2"></i>
                {{ company.id ? 'Guardar cambios' : 'Guardar' }}
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
            <div class="xl:col-span-5">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div
                        class="border-b border-slate-100 bg-gradient-to-r from-emerald-50 via-white to-white px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-100">
                                    <i class="fa-solid fa-building text-lg"></i>
                                </div>

                                <div>
                                    <h3 class="text-base font-bold text-slate-900">
                                        Datos de la empresa
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Registra la información principal y el estado comercial.
                                    </p>
                                </div>
                            </div>

                            <span
                                class="inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1 text-xs font-bold ring-1"
                                :class="company.state
                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                    : 'bg-slate-50 text-slate-500 ring-slate-200'">
                                <span class="h-2 w-2 rounded-full"
                                    :class="company.state ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                                {{ company.state ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-5 p-6">
                        <div>
                            <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-id-card text-xs text-slate-400"></i>
                                RUC
                            </label>

                            <div class="flex gap-3 items-center">
                                <el-input v-model="company.ruc" placeholder="Ej: 20123456789" size="large" />
                                <el-button class="!rounded-xl !h-10" @click="consultingRuc()">
                                    <i class="fa-brands fa-sistrix"></i>
                                </el-button>
                            </div>

                            <p class="mt-1 text-xs text-slate-400">
                                Ingresa 11 dígitos si corresponde.
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-building-user text-xs text-slate-400"></i>
                                Razón social
                            </label>

                            <el-input v-model="company.business_name" placeholder="Ej: GreenLab Perú S.A.C."
                                size="large" />
                        </div>

                        <div>
                            <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-location-dot text-xs text-slate-400"></i>
                                Dirección
                            </label>

                            <el-input v-model="company.direction" placeholder="Ej: Av. Javier Prado 123" size="large" />
                        </div>

                        <div>
                            <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                <i class="fa-solid fa-briefcase text-xs text-slate-400"></i>
                                Actividad
                            </label>

                            <el-input v-model="company.activity" type="textarea" :autosize="{ minRows: 3, maxRows: 6 }"
                                placeholder="Ej: Servicios de salud ocupacional, laboratorio, monitoreo ambiental..." />
                        </div>

                        <!-- Switches -->
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">
                                            Estado
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Empresa activa.
                                        </p>
                                    </div>

                                    <el-switch v-model="company.state" />
                                </div>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-blue-200 hover:bg-blue-50/40">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">
                                            Proveedor
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Es proveedor.
                                        </p>
                                    </div>

                                    <el-switch v-model="company.is_supplier" />
                                </div>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">
                                            Partner
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Aliado comercial.
                                        </p>
                                    </div>

                                    <el-switch v-model="company.is_partner" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTACTOS -->
            <div class="xl:col-span-7">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <!-- Header -->
                    <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-50 via-white to-white px-6 py-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-500 text-white shadow-lg shadow-indigo-100">
                                    <i class="fa-solid fa-address-book text-lg"></i>
                                </div>

                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-900">
                                            Contactos
                                        </h3>

                                        <span
                                            class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 ring-1 ring-indigo-100">
                                            {{ contacts.length }} registrado(s)
                                        </span>
                                    </div>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Agrega contactos por área: RRHH, Finanzas, Operaciones o Cobranza.
                                    </p>
                                </div>
                            </div>

                            <el-button type="primary"
                                class="!rounded-xl !border-indigo-500 !bg-indigo-500 !font-bold hover:!border-indigo-600 hover:!bg-indigo-600"
                                @click="addContact">
                                <i class="fa-solid fa-plus me-2"></i>
                                Nuevo contacto
                            </el-button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <!-- Empty -->
                        <div v-if="contacts.length === 0"
                            class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-slate-400 shadow-sm">
                                <i class="fa-regular fa-address-card text-2xl"></i>
                            </div>

                            <p class="text-sm font-bold text-slate-700">
                                Aún no agregaste contactos
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Haz clic en “Nuevo contacto” para empezar.
                            </p>
                        </div>

                        <!-- Lista -->
                        <div v-else class="space-y-4">
                            <div v-for="(c, idx) in contacts" :key="c.__key"
                                class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                                <!-- Contact header -->
                                <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-sm font-black text-indigo-700">
                                                {{ idx + 1 }}
                                            </span>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-bold text-slate-900">
                                                    {{ c.full_name || 'Contacto sin nombre' }}
                                                </p>

                                                <p class="mt-0.5 text-xs text-slate-500">
                                                    {{ c.type || 'Tipo no definido' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold ring-1"
                                                :class="c.active
                                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                                    : 'bg-slate-50 text-slate-600 ring-slate-200'">
                                                <span class="h-2 w-2 rounded-full"
                                                    :class="c.active ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                                                {{ c.active ? 'Activo' : 'Inactivo' }}
                                            </span>

                                            <span v-if="c.is_collection"
                                                class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-100">
                                                <i class="fa-solid fa-coins text-[11px]"></i>
                                                Cobranza
                                            </span>

                                            <el-button type="danger" plain class="!rounded-xl"
                                                @click="removeContact(idx)">
                                                <i class="fa-solid fa-trash-can me-2"></i>
                                                Eliminar
                                            </el-button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact form -->
                                <div class="grid grid-cols-1 gap-4 p-5 lg:grid-cols-12">
                                    <div class="lg:col-span-6">
                                        <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                            <i class="fa-solid fa-user text-xs text-slate-400"></i>
                                            Nombre completo
                                        </label>

                                        <el-input v-model="c.full_name" placeholder="Ej: María Pérez" size="large" />
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                            <i class="fa-solid fa-user-tag text-xs text-slate-400"></i>
                                            Tipo
                                        </label>

                                        <el-select v-model="c.type" class="!w-full" placeholder="Selecciona un tipo"
                                            filterable size="large">
                                            <el-option v-for="t in contactTypes" :key="t" :label="t" :value="t" />
                                        </el-select>
                                    </div>

                                    <div class="lg:col-span-4">
                                        <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                            <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                                            Teléfono
                                        </label>

                                        <el-input v-model="c.phone" placeholder="Ej: 999999999" size="large" />
                                    </div>

                                    <div class="lg:col-span-8">
                                        <label class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                                            <i class="fa-solid fa-envelope text-xs text-slate-400"></i>
                                            Email
                                        </label>

                                        <el-input v-model="c.email" placeholder="Ej: correo@empresa.com" size="large" />
                                    </div>

                                    <div class="lg:col-span-12">
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                            <div
                                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">
                                                            Activo
                                                        </p>
                                                        <p class="mt-1 text-xs text-slate-400">
                                                            Contacto habilitado.
                                                        </p>
                                                    </div>

                                                    <el-switch v-model="c.active" />
                                                </div>
                                            </div>

                                            <div
                                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-amber-200 hover:bg-amber-50/40">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">
                                                            Cobranza
                                                        </p>
                                                        <p class="mt-1 text-xs text-slate-400">
                                                            Aplica para coordinación.
                                                        </p>
                                                    </div>

                                                    <el-switch v-model="c.is_collection" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { ElMessage, ElNotification } from 'element-plus'
import tenant from '../../../stores/tenant'
import { useRoute, useRouter } from 'vue-router'

const loadingSubmit = ref(false)
const router = useRouter()
const route = useRoute()
const loader = ref(false)

const company = reactive({
    id: null,
    ruc: '',
    business_name: '',
    direction: '',
    activity: '',
    state: true,
    is_supplier: false,
    is_partner: false,
})

const contacts = ref([])

const contactTypes = [
    'RRHH',
    'Profesional',
    'Facturación',
    'Citas',
    'Logística',
    'Gerente General',
    'Finanzas',
    'Contabilidad',
    'Asistente Social',
    'Comercial',
    'Área de Operaciones',
]

const newContact = () => ({
    __key: crypto?.randomUUID?.() ?? String(Date.now() + Math.random()),
    full_name: '',
    type: 'RRHH',
    phone: '',
    email: '',
    active: true,
    company_id: company.id ?? null,
    user_id: null,
    is_collection: false,
})

const addContact = () => {
    contacts.value.push(newContact())
}

const removeContact = (idx) => {
    contacts.value.splice(idx, 1)
}

const payload = computed(() => ({
    ...company,
    contacts: contacts.value.map(({ __key, ...c }) => ({
        ...c,
        company_id: company.id ?? c.company_id ?? null,
    })),
}))

const onCancel = () => {
    ElMessage.info('Acción cancelada')
    router.push({ name: 'companies' })
}

const basicValidate = () => {
    if (!company.ruc?.trim()) return 'El RUC es obligatorio'
    if (!company.business_name?.trim()) return 'La razón social es obligatoria'

    for (const [i, c] of contacts.value.entries()) {
        if (c.email && !String(c.email).includes('@')) return `Email inválido en contacto #${i + 1}`
        if (c.phone && String(c.phone).length < 6) return `Teléfono muy corto en contacto #${i + 1}`
    }

    return null
}

const onSubmit = async () => {
    const err = basicValidate()

    if (err) {
        ElMessage.warning(err)
        return
    }

    loadingSubmit.value = true

    try {
        const { data } = await tenant.put(`company/${company.id}`, payload.value)
        ElMessage.success(data.message)
        router.push({ name: 'companies' })
    }
    catch (e) {
        ElMessage.error(e?.message ?? 'Error al guardar')
    }
    finally {
        loadingSubmit.value = false
    }
}

const getCompany = async () => {
    loader.value = true

    try {
        const { data } = await tenant.get(`company/${company.id}`)

        if (data.data) {
            company.id = data.data.id
            company.ruc = data.data.ruc
            company.business_name = data.data.business_name
            company.direction = data.data.direction
            company.activity = data.data.activity
            company.state = data.data.state
            company.is_supplier = data.data.is_supplier
            company.is_partner = data.data.is_partner

            if (Array.isArray(data.data.contacts) && data.data.contacts.length !== 0) {
                data.data.contacts.forEach(contact => {
                    contacts.value.push({
                        id: contact?.id,
                        __key: crypto?.randomUUID?.() ?? String(Date.now() + Math.random()),
                        full_name: contact?.user?.full_name,
                        type: contact?.type,
                        phone: contact?.phone,
                        email: contact?.email,
                        active: contact?.active,
                        company_id: contact?.company_id,
                        user_id: contact?.user_id,
                        is_collection: contact?.is_collection,
                    })
                })
            }
        }
    }
    catch (e) {
        console.error(e)
    }
    finally {
        loader.value = false
    }
}

onMounted(() => {
    if (!route.params?.id) {
        ElNotification.error('Error no se encontro un ID valido')
        router.push('/companies')
    }

    company.id = route.params.id
    getCompany()
})
</script>

<style scoped></style>
