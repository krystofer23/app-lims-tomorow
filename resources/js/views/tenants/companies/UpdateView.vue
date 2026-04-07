<template>
    <div class="w-full">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    {{ company.id ? 'Editar empresa' : 'Registrar empresa' }}
                </h2>
                <p class="text-sm text-slate-500">
                    Completa los datos de la empresa y registra sus contactos en la misma vista.
                </p>
            </div>

            <div class="flex">
                <el-button class="!rounded-xl" @click="onCancel">
                    Cancelar
                </el-button>

                <el-button type="primary"
                    class="!rounded-xl !bg-emerald-400 !border-emerald-400 hover:!bg-emerald-500 hover:!border-emerald-500"
                    :loading="loadingSubmit" @click="onSubmit">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                    {{ company.id ? 'Guardar cambios' : 'Guardar' }}
                </el-button>
            </div>
        </div>

        <div class="mt-5 h-px w-full bg-gradient-to-r from-emerald-400 via-emerald-300 to-emerald-400"></div>

        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-12">
            <div class="xl:col-span-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                                <i class="fa-solid fa-building text-emerald-500"></i>
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Datos de la empresa</h3>
                                <p class="mt-1 text-xs text-slate-500">Campos principales y estado comercial.</p>
                            </div>
                        </div>

                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1 text-xs text-slate-600 ring-1 ring-slate-200">
                            <span class="h-2 w-2 rounded-full"
                                :class="company.state ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                            {{ company.state ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-900">RUC</label>
                            <el-input v-model="company.ruc" placeholder="Ej: 20123456789" class="mt-2" />
                            <p class="mt-1 text-xs text-slate-500">Solo números, 11 dígitos (si aplica en tu caso).</p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-900">Razón social</label>
                            <el-input v-model="company.business_name" placeholder="Ej: GreenLab Perú S.A.C."
                                class="mt-2" />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-900">Dirección</label>
                            <el-input v-model="company.direction" placeholder="Ej: Av. ..." class="mt-2" />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-900">Actividad</label>
                            <el-input v-model="company.activity" type="textarea" :autosize="{ minRows: 2, maxRows: 6 }"
                                placeholder="Ej: Servicios de salud ocupacional..." class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-900">Estado</span>
                                    <el-switch v-model="company.state" />
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Habilita/inhabilita la empresa en el sistema.</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-900">Proveedor</span>
                                    <el-switch v-model="company.is_supplier" />
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Marca si esta empresa es proveedor.</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-900">Partner</span>
                                    <el-switch v-model="company.is_partner" />
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Marca si trabaja como aliado/partner.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-7">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 ring-1 ring-indigo-100">
                                <i class="fa-solid fa-address-book text-indigo-500"></i>
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Contactos</h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    Agrega uno o varios contactos para la empresa (RRHH, Finanzas, etc.).
                                </p>
                            </div>
                        </div>

                        <el-button type="primary"
                            class="!rounded-xl !bg-indigo-500 !border-indigo-500 hover:!bg-indigo-600 hover:!border-indigo-600"
                            @click="addContact">
                            <i class="fa-solid fa-plus me-2"></i>
                            Nuevo contacto
                        </el-button>
                    </div>

                    <div class="mt-5 h-px w-full bg-gradient-to-r from-indigo-400 via-indigo-300 to-indigo-400"></div>

                    <div v-if="contacts.length === 0"
                        class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                        <p class="text-sm font-semibold text-slate-800">Aún no agregaste contactos</p>
                        <p class="mt-1 text-sm text-slate-500">Haz clic en “Nuevo contacto” para empezar.</p>
                    </div>

                    <div v-else class="mt-5 space-y-4">
                        <div v-for="(c, idx) in contacts" :key="c.__key"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-sm font-semibold text-slate-700">
                                        {{ idx + 1 }}
                                    </span>

                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 truncate">
                                            {{ c.full_name || 'Contacto sin nombre' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ c.type || 'Tipo no definido' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs ring-1"
                                        :class="c.active ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-slate-50 text-slate-600 ring-slate-200'">
                                        <span class="h-2 w-2 rounded-full"
                                            :class="c.active ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                                        {{ c.active ? 'Activo' : 'Inactivo' }}
                                    </span>

                                    <el-button type="danger" plain class="!rounded-xl" @click="removeContact(idx)">
                                        <i class="fa-solid fa-trash me-2"></i>
                                        Eliminar
                                    </el-button>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-12">
                                <div class="lg:col-span-6">
                                    <label class="text-sm font-semibold text-slate-900">Nombre completo</label>
                                    <el-input v-model="c.full_name" placeholder="Ej: María Pérez" class="mt-2" />
                                </div>

                                <div class="lg:col-span-6">
                                    <label class="text-sm font-semibold text-slate-900">Tipo</label>
                                    <el-select v-model="c.type" class="mt-2 w-full" placeholder="Selecciona un tipo"
                                        filterable>
                                        <el-option v-for="t in contactTypes" :key="t" :label="t" :value="t" />
                                    </el-select>
                                </div>

                                <div class="lg:col-span-4">
                                    <label class="text-sm font-semibold text-slate-900">Teléfono</label>
                                    <el-input v-model="c.phone" placeholder="Ej: 999999999" class="mt-2" />
                                </div>

                                <div class="lg:col-span-5">
                                    <label class="text-sm font-semibold text-slate-900">Email</label>
                                    <el-input v-model="c.email" placeholder="Ej: correo@empresa.com" class="mt-2" />
                                </div>

                                <div class="lg:col-span-12">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold text-slate-900">Activo</span>
                                                <el-switch v-model="c.active" />
                                            </div>
                                            <p class="mt-2 text-xs text-slate-500">Define si este contacto está
                                                habilitado.</p>
                                        </div>

                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold text-slate-900">Cobranza</span>
                                                <el-switch v-model="c.is_collection" />
                                            </div>
                                            <p class="mt-2 text-xs text-slate-500">Marca si aplica a coordinación de
                                                recolección.</p>
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