<template>
    <div class="w-full">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-100/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-400 text-white flex items-center justify-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 leading-tight">Usuarios</h2>
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
                <el-table :data="users" v-loading="loading" class="w-full" :header-cell-style="headerStyle"
                    :row-class-name="rowClassName" stripe>
                    <el-table-column type="index" label="#" width="60" />

                    <el-table-column label="Usuario" min-width="300">
                        <template #default="{ row }">
                            <div class="flex items-start gap-3">
                                <div
                                    class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-user"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">
                                        {{ row?.full_name || `${row?.name ?? ''} ${row?.last_name_first ?? ''}
                                        ${row?.last_name_second ?? ''}` }}
                                    </p>

                                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fa-regular fa-id-card"></i>
                                            {{ row?.type_document || '-' }}
                                        </span>

                                        <span class="text-slate-300">•</span>

                                        <span>{{ row?.document_number || '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Correo" min-width="240">
                        <template #default="{ row }">
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <i class="fa-regular fa-envelope text-sky-500"></i>
                                <span class="truncate">{{ row?.email || '-' }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Sexo" min-width="120">
                        <template #default="{ row }">
                            <el-tag size="small" effect="light" class="!rounded-full"
                                :type="row?.sex === 'MASCULINO' ? 'primary' : row?.sex === 'FEMENINO' ? 'danger' : 'info'">
                                <div class="flex items-center gap-1">
                                    <i :class="row?.sex === 'MASCULINO'
                                        ? 'fa-solid fa-mars'
                                        : row?.sex === 'FEMENINO'
                                            ? 'fa-solid fa-venus'
                                            : 'fa-solid fa-genderless'"></i>
                                    <span>{{ row?.sex || '-' }}</span>
                                </div>
                            </el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="Edad" min-width="100" align="center">
                        <template #default="{ row }">
                            <div class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                                <i class="fa-solid fa-hourglass-half text-amber-500"></i>
                                <span>{{ row?.age ?? '-' }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Nacimiento" min-width="150">
                        <template #default="{ row }">
                            <div class="text-sm text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-cake-candles text-pink-500"></i>
                                <span>{{ row?.birthdate ? formatDate(row?.birthdate) : '-' }}</span>
                            </div>
                        </template>
                    </el-table-column>

                    <!-- <el-table-column label="Profesional" min-width="130" align="center">
                        <template #default="{ row }">
                            <el-tag size="small" class="!rounded-full"
                                :type="row?.is_professional ? 'success' : 'info'">
                                <div class="flex items-center gap-1">
                                    <i
                                        :class="row?.is_professional ? 'fa-solid fa-user-doctor' : 'fa-regular fa-user'"></i>
                                    <span>{{ row?.is_professional ? 'Sí' : 'No' }}</span>
                                </div>
                            </el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="Firma" min-width="120" align="center">
                        <template #default="{ row }">
                            <el-tag size="small" class="!rounded-full" :type="row?.signature ? 'success' : 'warning'">
                                <div class="flex items-center gap-1">
                                    <i
                                        :class="row?.signature ? 'fa-solid fa-signature' : 'fa-regular fa-pen-to-square'"></i>
                                    <span>{{ row?.signature ? 'Registrada' : 'Sin firma' }}</span>
                                </div>
                            </el-tag>
                        </template>
                    </el-table-column> -->

                    <el-table-column label="Estado" min-width="120" align="center">
                        <template #default="{ row }">
                            <el-tag size="small" effect="dark" class="!rounded-full"
                                :type="row?.active ? 'success' : 'danger'">
                                <div class="flex items-center gap-1">
                                    <i
                                        :class="row?.active ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark'"></i>
                                    <span>{{ row?.active ? 'Activo' : 'Inactivo' }}</span>
                                </div>
                            </el-tag>
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
                                <el-tooltip content="Editar" placement="top">
                                    <el-button circle class="!rounded-xl !m-0" @click="handleEdit(row)">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </el-button>
                                </el-tooltip>

                                <el-tooltip content="Eliminar" placement="top">
                                    <el-button @click="handleDelete(row)" :loading="row.loading" circle type="danger"
                                        plain class="!rounded-xl !m-0">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </el-button>
                                </el-tooltip>
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
                            <el-button class="mt-4 !rounded-xl">Limpiar filtros</el-button>
                        </div>
                    </template>
                </el-table>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Mostrando <span class="font-semibold text-slate-700">{{ users.length }}</span> de
                        <span class="font-semibold text-slate-700">{{ pagination?.total }}</span> registros
                    </p>

                    <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                        v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                        :page-sizes="[10, 20, 50, 100]" @update:current-page="getUsers" />
                </div>
            </div>
        </div>
    </div>

    <el-dialog v-model="state" @close="handleClose" top="1.5vh" class="!p-0 overflow-hidden !rounded-xl" width="640px"
        :style="{ width: computedDialogWidth }">
        <template #header>
            <div class="px-6 pt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                                <i class="fa-solid fa-users text-emerald-400"></i>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 leading-5">
                                    {{ formData.id ? 'Actualizar usuario ' : 'Registrar usuario ' }}
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
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-3 border border-gray-200 rounded-xl shadow-sm hover:shadow p-3">
                    <div class="flex items-center gap-3 border-b border-gray-200 pb-2">
                        <div class="bg-emerald-400 text-white p-2 rounded-xl">
                            <i class="fa-solid fa-address-card"></i>
                        </div>
                        <p class="font-medium text-base text-gray-900">Información Personal</p>
                    </div>

                    <div class="grid grid-cols-12">
                        <div class="bg-white p-3 col-span-6">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-id-card text-emerald-500"></i>
                                    Tipo de Documento
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-select clearable placeholder="Seleccionar" class="mt-2"
                                v-model="formData.type_document">
                                <el-option :label="row" :value="row" v-for="row in [
                                    'DNI',
                                    'CUI',
                                    'PASAPORTE',
                                    'CARNET DE EXTRANJERIA',
                                    'OTRO',
                                ]"></el-option>
                            </el-select>
                        </div>

                        <div class="bg-white p-3 col-span-6">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-hashtag text-emerald-500"></i>
                                    N° de Documento
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-100">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.document_number" type="text"
                                placeholder="Ejem: 003300122" class="mt-2" />
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-user text-emerald-500"></i>
                                    Nombres
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.name" type="text" placeholder="-" class="mt-2" />
                        </div>

                        <div class="p-3 col-span-4">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-user-tag text-emerald-500"></i>
                                    Primer Apellido
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.last_name_first" type="text" placeholder="-"
                                class="mt-2" />
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-user-tag text-emerald-500"></i>
                                    Segundo Apellido
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.last_name_second" type="text" placeholder="-"
                                class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="col-span-3 border border-gray-200 rounded-xl shadow-sm hover:shadow p-3">
                    <div class="flex items-center gap-3 border-b border-gray-200 pb-2">
                        <div class="bg-emerald-400 text-white p-2 rounded-xl">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <p class="font-medium text-base text-gray-900">Detalles Personales</p>
                    </div>

                    <div class="grid grid-cols-12">
                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-venus-mars text-emerald-500"></i>
                                    Género
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-select class="mt-2" clearable v-model="formData.sex" placeholder="Seleccionar">
                                <el-option :label="row" :value="row"
                                    v-for="row in ['MASCULINO', 'FEMENINO']"></el-option>
                            </el-select>
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-cake-candles text-emerald-500"></i>
                                    F. Nacimiento
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-date-picker placeholder="Seleccionar" clearable class="mt-2"
                                v-model="formData.birthdate" style="width: 100%;" />
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-hourglass-half text-emerald-500"></i>
                                    Edad
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.age" type="number" placeholder="-" class="mt-2" />
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-toggle-on text-emerald-500"></i>
                                    Estado
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-select v-model="formData.active" class="mt-2" clearable placeholder="Seleccionar">
                                <el-option :value="row.value" :label="row.label" v-for="row in [
                                    { label: 'Activo', value: true },
                                    { label: 'Inactivo', value: false }
                                ]"></el-option>
                            </el-select>
                        </div>

                        <div class="col-span-4 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-user-shield text-emerald-500"></i>
                                    Rol
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-select multiple v-model="formData.role" class="mt-2" clearable
                                placeholder="Seleccionar">
                                <el-option :value="row" :label="row" v-for="row in [
                                    'Super Admin',
                                    'Comercial',
                                    'Recepción',
                                    'Profesional',
                                ]"></el-option>
                            </el-select>
                        </div>

                        <div class="col-span-6 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-envelope text-emerald-500"></i>
                                    Correo Electrónico
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input clearable v-model="formData.email" type="email" placeholder="-" class="mt-2" />
                        </div>

                        <div class="col-span-6 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-lock text-emerald-500"></i>
                                    Contraseña
                                </label>

                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-medium text-red-700">
                                    <i class="fa-solid fa-asterisk text-[9px] text-red-500"></i>
                                    Obligatorio
                                </span>
                            </div>

                            <el-input show-password clearable v-model="formData.password" type="password"
                                placeholder="-" class="mt-2" />
                        </div>
                    </div>
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

    <confirm-dialog ref="confirmRef" />
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import tenant from '../../../stores/tenant';
import { ElNotification } from 'element-plus';
import { useWindowSize } from '@vueuse/core';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';

const { width: windowWidth } = useWindowSize();

const computedDialogWidth = computed(() => {
    if (windowWidth.value <= 576) {
        return '90%'
    } else if (windowWidth.value <= 768) {
        return '90%'
    } else if (windowWidth.value <= 992) {
        return '80%'
    } else if (windowWidth.value <= 1200) {
        return '50%'
    } else {
        return '50%'
    }
})

const filters = reactive({
    q: null,
})

const confirmRef = ref(null);
const state = ref(false)
const users = ref([])
const loading = ref(false)
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const headerStyle = () => ({
    background: "#F8FAFC",
    color: "#0F172A",
    fontWeight: "700",
    borderBottom: "1px solid #E2E8F0",
})

const rowClassName = () => "hover:bg-slate-50 transition"

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
}

const loadingSubmit = ref(false)
const formData = ref({
    id: null,
    name: null,
    last_name_first: null,
    last_name_second: null,
    type_document: null,
    document_number: null,
    sex: null,
    age: null,
    birthdate: null,
    email: null,
    password: null,
    active: null,
    role: null
})

const getUsers = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`users?page=${page}`)

        if (data.data) {
            users.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total,
            }
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (formData.value.id) {
            const { data } = await tenant.put(`users/${formData.value.id}`, formData.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`users`, formData.value)
            ElNotification.success(data.message)
        }

        getUsers(pagination.value.current_page)
        handleClose()
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const handleClose = () => {
    formData.value = {
        id: null,
        name: null,
        last_name_first: null,
        last_name_second: null,
        type_document: null,
        document_number: null,
        sex: null,
        age: null,
        birthdate: null,
        email: null,
        password: null,
        active: null,
        role: null
    }

    state.value = false
}

const handleEdit = (row) => {
    state.value = true

    formData.value = {
        id: row.id,
        name: row.name,
        last_name_first: row.last_name_first,
        last_name_second: row.last_name_second,
        type_document: row.type_document,
        document_number: row.document_number,
        sex: row.sex,
        age: row.age,
        birthdate: row.birthdate,
        email: row.email,
        password: row.password,
        active: row.active,
        role: row.roles.map(r => r.name)
    }
}

async function handleDelete(row) {
    const ok = await confirmRef.value?.open({
        title: 'Eliminar usuario',
        message: '¿Seguro de eliminar el usuario?',
        confirmText: 'Sí, aceptar',
        cancelText: 'Cancelar',
    })
    if (ok) {
        row.loading = true

        try {
            const { data } = await tenant.delete(`users/${row.id}`)
            ElNotification.success(data.message)

            getUsers(pagination.value.current_page)
        }
        catch (e) {
            handleErrorsExeption(e)
        }
        finally {
            row.loading = false
        }
    }
}

onMounted(() => {
    getUsers()
})
</script>