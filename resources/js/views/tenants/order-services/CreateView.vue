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
        <el-tabs v-model="activeTab" class="os-tabs !rounded-lg overflow-hidden" type="border-card">
            <el-tab-pane name="general">
                <template #label>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-building-circle-check"></i>
                        <span>Datos del Cliente</span>
                    </span>
                </template>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Empresa
                        </label>
                        <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
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
                        <el-select :loading="loadingContacts" clearable v-model="form.contact_company" filterable
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

                    <!-- <div class="md:col-span-2 xl:col-span-3">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Referencia
                        </label>
                        <el-input v-model="form.reference" type="textarea" :autosize="{ minRows: 3, maxRows: 4 }"
                            placeholder="Detalle breve o referencia de la orden de servicio" />
                    </div> -->
                </div>
            </el-tab-pane>

            <el-tab-pane name="monitoring">
                <template #label>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>Datos del Monitoreo</span>
                    </span>
                </template>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-12">
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Cliente
                        </label>
                        <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                            v-model="form.application_id" filterable class="w-full" placeholder="Selecciona una empresa"
                            size="large">
                            <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                :value="company.id" />
                        </el-select>
                    </div>

                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Contacto
                        </label>
                        <el-select :loading="loadingContacts" clearable v-model="form.contact_application" filterable
                            class="w-full" placeholder="Selecciona un contacto" size="large">
                            <el-option v-for="contact in contacts" :key="contact.id"
                                :label="contact?.user?.full_name + ' | ' + contact?.type" :value="contact.id" />
                        </el-select>
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Departamento
                        </label>
                        <el-select clearable filterable v-model="form.department" size="large"
                            placeholder="Seleccionar">
                            <el-option v-for="row in departments" :label="row.departamento"
                                :value="row.departamento"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Provincia
                        </label>
                        <el-select clearable filterable v-model="form.province" size="large" placeholder="Seleccionar">
                            <el-option v-for="row in provinces" :label="row.provincia"
                                :value="row.provincia"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Distrito
                        </label>
                        <el-select clearable filterable v-model="form.district" size="large" placeholder="Seleccionar">
                            <el-option v-for="row in districts" :value="row.distrito" :label="row.distrito"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Ref. Sobre la ubicación
                        </label>
                        <el-input clearable v-model="form.reference" placeholder="Ej: Huánuco y Cerro de Pasco"
                            size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Procedencia
                        </label>
                        <el-input v-model="form.origin" placeholder="Ej: Huánuco y Cerro de Pasco" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Proyecto
                        </label>
                        <el-input v-model="form.project" placeholder="Nombre del proyecto" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha inicio del servicio
                        </label>
                        <el-date-picker clearable v-model="form.date_init_service" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha fin del monitoreo
                        </label>
                        <el-date-picker clearable v-model="form.date_end_monitoring" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Personal Programado
                        </label>
                        <el-select v-model="form.users" multiple filterable remote clearable size="large"
                            placeholder="Seleccionar usuarios" :remote-method="listStore.getUsers"
                            :loading="listStore.loadingUsers">
                            <el-option v-for="row in users" :key="row.id" :value="row.id" :label="row.full_name" />
                        </el-select>
                    </div>

                    <div class="col-span-12">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Especificar detalles
                        </label>
                        <el-input v-model="form.details" type="textarea" :autosize="{ minRows: 3, maxRows: 5 }"
                            placeholder="Ej: Toma de muestras y mediciones de campo" />
                    </div>

                    <!-- <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha de salida
                        </label>
                        <el-date-picker v-model="form.date_output" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha de inducción
                        </label>
                        <el-date-picker v-model="form.date_induction" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div>

                    <div class="col-span-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Fecha inicio del monitoreo
                        </label>
                        <el-date-picker v-model="form.date_monitoring_init" type="date" class="!w-full"
                            placeholder="Selecciona fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="large" />
                    </div> -->

                    <el-divider class="col-span-12 !my-7">
                        <div
                            class="flex items-center gap-3 rounded-full bg-white px-4 py-2 shadow-sm ring-1 ring-slate-200">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </div>

                            <div class="text-left">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-700">
                                    Datos de información sobre los puntos de monitoreo
                                </p>
                                <p class="text-[11px] font-medium text-slate-400">
                                    Aumentar las filas según sea necesario
                                </p>
                            </div>
                        </div>
                    </el-divider>

                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Estaciones de monitoreo
                        </label>
                        <el-input v-model="form.monitoring" type="textarea" :autosize="{ minRows: 3, maxRows: 4 }"
                            placeholder="Ej: Adjunta en la programación enviada" />
                    </div>

                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Proyecto de monitoreo
                        </label>
                        <el-input v-model="form.projects" type="textarea" :autosize="{ minRows: 3, maxRows: 4 }"
                            placeholder="Detalle del proyecto de monitoreo" />
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane name="services">
                <template #label>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-server"></i>
                        <span>Condiciones del Servicio</span>
                    </span>
                </template>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-12">
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Servicio incluye
                        </label>
                        <el-input v-model="form.service_includes" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Hospedaje
                        </label>
                        <el-input v-model="form.accommodation" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Viaticos
                        </label>
                        <el-input v-model="form.travel_expenses" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Dias de Servicio
                        </label>
                        <el-input v-model="form.days_service" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Trasporte de personal
                        </label>
                        <el-input v-model="form.personal_transport" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Envio de Muestra
                        </label>
                        <el-input v-model="form.send_sampling" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Vigilancia
                        </label>
                        <el-input v-model="form.surveillance" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Generador de Electrico
                        </label>
                        <el-input v-model="form.electric_generator" placeholder="Escribir..." />
                    </div>

                    <el-divider class="col-span-12 !my-7">
                        <div
                            class="flex items-center gap-3 rounded-full bg-white px-4 py-2 shadow-sm ring-1 ring-slate-200">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <i class="fa-solid fa-clipboard-check text-sm"></i>
                            </div>

                            <div class="text-left">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-700">
                                    Datos para la emisión informe
                                </p>
                            </div>
                        </div>
                    </el-divider>

                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Empresa
                        </label>
                        <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                            v-model="form.company_emission_id" filterable class="w-full"
                            placeholder="Selecciona una empresa" size="large">
                            <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                :value="company.id" />
                        </el-select>
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            TIPO DE DOCUMENTO SOLICITADO
                        </label>
                        <el-input v-model="form.type_document_required" placeholder="Escribir..." />
                    </div>
                    <div class="col-span-6">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            NÚMERO DE COPIAS IMPRESAS
                        </label>
                        <el-input v-model="form.number_copy" placeholder="Escribir..." />
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
                                            class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                            Parametro
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
                                            {{ row?.matrix?.description ??
                                                row?.parameter?.connections_parameter?.[0]?.matrix?.description ?? '-' }}
                                        </td>

                                        <td :class="row?.item?.bg" class="px-3 py-2">
                                            {{ row.parameter.description }}
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

                                        <td :class="row?.item?.bg" class="relative px-3 py-2 text-right">
                                            <el-button-group size="small">
                                                <el-button @click="() => {
                                                    visibleParameters = true
                                                    rowSelect = row
                                                    getToParametersMetal(row?.parameter?.ids_connections_parameters ?? [], row)
                                                }" v-if="row?.parameter?.is_metal" plain size="small" type="info"
                                                    class="!rounded-l-lg" v-tippy="'Seleccionar Parametros'">
                                                    <i class="fa-solid fa-bugs"></i>
                                                </el-button>
                                                <el-button @click="changeValues(row)" v-tippy="'Cambiar valores'"
                                                    :class="row?.parameter?.is_metal ? '' : '!rounded-l-lg'">
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

    <matriz-modal v-model:items="form.items" :show-matrix-modal="showMatrixModal" @close="() => {
        showMatrixModal = false
    }" />

    <teams-modal :state="state" :matriz-id="matrizId" @close="() => {
        state = false
        matrizId = null
    }" />

    <el-dialog v-model="visibleValue" width="620px" align-center class="parameter-dialog !rounded-lg">
        <template #header>
            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Configurar parámetro
                </h2>
                <p class="text-xs text-gray-500">
                    Selecciona unidad de medida y LCM.
                </p>
            </div>
        </template>

        <div class="space-y-4">
            <div v-if="rowValue && rowValue?.operations" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="border-b bg-blue-50 px-3 py-2">
                        <h3 class="text-xs font-semibold uppercase text-blue-700">
                            Unidad de medida
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div v-for="(reference, index) in unitReferences" :key="`unit-${index}`"
                            class="flex items-center justify-between gap-2 px-3 py-2 hover:bg-gray-50">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-700">
                                    {{ reference.unit }}
                                </p>
                                <p class="text-[11px] text-gray-400">
                                    {{ reference.condition }}
                                </p>
                            </div>

                            <div class="flex min-w-0 items-center gap-2">
                                <span class="max-w-[90px] truncate text-xs text-gray-600">
                                    {{ rowValue?.operations?.units_measurements?.[index] ?? '-' }}
                                </span>

                                <el-button v-tippy="'Seleccionar'" size="small" type="primary" plain circle
                                    :disabled="!rowValue?.operations?.units_measurements?.[index]"
                                    @click="selectUnit(rowValue.operations.units_measurements?.[index] ?? null)">
                                    <i class="fa-regular fa-hand-pointer text-xs"></i>
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="border-b bg-emerald-50 px-3 py-2">
                        <h3 class="text-xs font-semibold uppercase text-emerald-700">
                            LCM
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div v-for="(reference, index) in lcmReferences" :key="`lcm-${index}`"
                            class="flex items-center justify-between gap-2 px-3 py-2 hover:bg-gray-50">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-700">
                                    {{ reference.unit }}
                                </p>
                                <p class="text-[11px] text-gray-400">
                                    {{ reference.condition }}
                                </p>
                            </div>

                            <div class="flex min-w-0 items-center gap-2">
                                <span class="max-w-[90px] truncate text-xs text-gray-600">
                                    {{ rowValue?.operations?.lcms?.[index] ?? '-' }}
                                </span>

                                <el-button v-tippy="'Seleccionar'" size="small" type="success" plain circle
                                    :disabled="!rowValue?.operations?.lcms?.[index]"
                                    @click="lcm = rowValue.operations.lcms?.[index] ?? null">
                                    <i class="fa-regular fa-hand-pointer text-xs"></i>
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-gray-50 p-3 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">
                        Unidad de medida
                    </label>

                    <el-select v-model="unit_measurent" :remote-method="listStore.getUnitsMeasurement" filterable remote
                        reserve-keyword clearable placeholder="Seleccionar unidad" class="w-full" size="small">
                        <el-option v-for="row in unitsMeasurement" :key="row.id" :label="row.description"
                            :value="row.id" />
                    </el-select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">
                        LCM
                    </label>

                    <el-input v-model="lcm" placeholder="LCM" size="small" clearable />
                </div>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-end gap-2">
                <el-button class="!rounded-lg" size="small" @click="visibleValue = false">
                    Cancelar
                </el-button>

                <el-button class="!rounded-lg" size="small" type="primary" @click="saveChangeValues()">
                    Guardar
                </el-button>
            </div>
        </template>
    </el-dialog>

    <el-dialog v-model="visibleParameters" class="!rounded-lg" width="700px">
        <template #header>
            <div class="flex items-center justify-between border-b pb-2">
                <div>
                    <h4 class="text-sm font-semibold text-slate-700">
                        Parámetros del metal
                    </h4>
                    <p class="text-xs text-slate-400">
                        Selecciona los parámetros que deseas agregar.
                    </p>
                </div>

                <el-tag type="primary" effect="light">
                    {{ parametersToMetal.length }} parametros
                </el-tag>
            </div>
        </template>
        <div class="space-y-3">
            <el-input v-model="searchMetal" placeholder="Buscar parámetro..." clearable size="small" />

            <el-table v-loading="loadingMetalParameters" :data="filteredParametersToMetal" height="300" size="small"
                border :row-class-name="tableRowClassName">
                <el-table-column prop="description" label="Parámetro" min-width="180" show-overflow-tooltip />

                <el-table-column label="Unidad De Medida" show-overflow-tooltip>
                    <template #default="{ row }">
                        <el-select :remote-method="listStore.getUnitsMeasurement" filterable remote reserve-keyword
                            v-model="row.item[0].unit_measurement_id" size="small" placeholder="Seleccione"
                            class="w-full">
                            <el-option v-for="unit in unitsMeasurement" :key="unit.id" :label="unit.description"
                                :value="unit.id" />
                        </el-select>
                    </template>
                </el-table-column>

                <el-table-column label="LCM" show-overflow-tooltip>
                    <template #default="{ row }">
                        <el-input v-model="row.item[0].lcm" size="small" placeholder="LCM" />
                    </template>
                </el-table-column>

                <el-table-column label="Acciones" width="130" align="center">
                    <template #default="{ row: metalRow }">
                        <el-button v-if="!metalRow.is_select" size="small" type="success" plain class="!rounded-lg"
                            @click="addMetalParameter(metalRow)">
                            [+] Agregar
                        </el-button>
                        <el-button v-if="metalRow.is_select" size="small" type="danger" plain class="!rounded-lg"
                            @click="removeMetalParameter(metalRow.select_to_metal.id)">
                            [-] Remover
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import tenant from '../../../stores/tenant';
import { useListStore } from '../../../stores/list';
import MatrizModal from '../quotes/modal/MatrizModal.vue';
import TeamsModal from './modals/TeamsModal.vue';
import { ElMessage, ElNotification } from 'element-plus';
import { OfficeBuilding } from '@element-plus/icons-vue';

const listStore = useListStore()
const companies = computed(() => listStore.companies)
const frequency = ref(null)
const loadingContacts = computed(() => listStore.loadingContacts)
const contacts = computed(() => listStore.contacts)

const activeTab = ref('general')
const state = ref(false)
const matrizId = ref(null)
const users = computed(() => listStore.users)

const visibleParameters = ref(false)
const loadingMetalParameters = ref(false)
const parametersArrayUse = ref([])
const parametersToMetal = ref([])
const searchMetal = ref('')
const rowSelect = ref(null)

const normalizeMetalParameters = (parameters = []) => {
    return parameters.map((parameter) => ({
        ...parameter,
        item: Array.isArray(parameter.item) && parameter.item.length > 0
            ? parameter.item
            : [
                {
                    id: null,
                    type: parameter.type ?? null,
                    type_of_sample_id: null,
                    condition_id: null,
                    matrix_id: null,
                    reference_id: null,
                    parameter_id: parameter.id,
                    unit_measurement_id: null,
                    lcm: '',
                    is_operation: false,
                    operations: null,
                    is_other_company: false,
                    company_id: null,
                    unit_price: '0.00',
                },
            ],
    }))
}

const filteredParametersToMetal = computed(() => {
    const search = searchMetal.value.toLowerCase().trim()

    if (!search) {
        return parametersToMetal.value
    }

    return parametersToMetal.value.filter((item) => {
        return item.description?.toLowerCase().includes(search)
    })
})

const getToParametersMetal = async (listArray = [], row = null) => {
    loadingMetalParameters.value = true

    try {
        parametersToMetal.value = []

        const { data } = await tenant.get('parameters/list', {
            params: {
                list_array: listArray,
                condition_id: row?.condition_id,
                type: row?.type,
                order_id: route.params.id
            },
        })

        parametersToMetal.value = normalizeMetalParameters(data.data ?? [])
        listStore.getUnitsMeasurement(parametersToMetal.value.map(e => e.item[0].unit_measurement_id))
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingMetalParameters.value = false
    }
}

const addMetalParameter = async (row) => {
    if (!row || !rowSelect.value) return

    const item = row.item?.[0]

    if (!item?.unit_measurement_id) {
        return ElMessage.warning('No puedes agregar el parámetro sin unidad de medida')
    }

    if (!item?.lcm) {
        return ElMessage.warning('No puedes agregar el parámetro sin LCM')
    }

    const originalParameterId = rowSelect.value.parameter_id

    const itemPayload = {
        ...rowSelect.value,
        lcm: item.lcm,
        unit_measurement_id: item.unit_measurement_id,
        parameter_id: row.id,
        parameter: {
            ...(rowSelect.value.parameter ?? {}),
            id: row.id,
            description: row.description,
        },
    }

    const payload = {
        order_id: route.params.id,
        to_metal_id: originalParameterId,
        parameter_id: row.id,
        item: itemPayload,
    }

    try {
        const { data } = await tenant.post('to-metal', payload)

        row.is_select = true
        row.select_to_metal = data.data ?? null

        ElMessage.success(data.message ?? 'Parámetro agregado correctamente')

        getToParametersMetal(rowSelect.value?.parameter?.ids_connections_parameters ?? [], rowSelect.value)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const removeMetalParameter = async (id) => {
    try {
        const { data } = await tenant.delete(`to-metal/${id}`)
        ElMessage.success(data.message)

        getToParametersMetal(rowSelect.value?.parameter?.ids_connections_parameters ?? [], rowSelect.value)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const tableRowClassName = ({ row }) => {
    if (row.is_select) {
        return 'row-selected'
    }

    return ''
}

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
    contact_company: null,
    direction: null,
    date_attention: null,
    application_id: null,
    contact_application: null,
    department: null,
    district: null,
    province: null,
    reference: null,
    origin: null,
    project: null,
    date_init_service: null,
    date_end_monitoring: null,
    users: [],
    details: null,
    monitoring: null,
    projects: null,
    service_includes: null,
    accommodation: null,
    travel_expenses: null,
    days_service: null,
    personal_transport: null,
    send_sampling: null,
    surveillance: null,
    electric_generator: null,
    company_emission_id: null,
    type_document_required: null,
    number_copy: null,
    version: null,
    code: null,
    items: [],
    observations: null
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

const unitsMeasurement = computed(() => listStore.unitsMeasurement)
const visibleValue = ref(false)
const rowValue = ref(null)
const unit_measurent = ref(null)
const lcm = ref(null)

const unitReferences = [
    {
        unit: 'mg/Nm³',
        condition: '0° y 1 atm',
    },
    {
        unit: 'mg/m³',
        condition: '25° y 1 atm',
    },
    {
        unit: 'mg/m³',
        condition: '20° y 1 atm',
    },
    {
        unit: 'PPM / %',
        condition: '10-6 mol/mol',
    },
];

const lcmReferences = [
    {
        unit: 'mg/Nm³',
        condition: '0° y 1 atm',
    },
    {
        unit: 'mg/m³',
        condition: '25° y 1 atm',
    },
    {
        unit: 'mg/m³',
        condition: '20° y 1 atm',
    },
    {
        unit: 'PPM',
        condition: '10-6 mol/mol',
    },
];

const selectUnit = async (uni) => {
    await listStore.getUnitsMeasurement(uni)
    unit_measurent.value = listStore.unitsMeasurement[0]?.id ?? null
}

const changeValues = async (row) => {
    rowValue.value = row

    unit_measurent.value = row.unit_measurement_id;
    lcm.value = row.lcm;

    await listStore.getUnitsMeasurement(row.unit_measurement_id)
    visibleValue.value = true
}

const saveChangeValues = async () => {
    rowValue.value.unit_measurement_id = unit_measurent.value
    rowValue.value.lcm = lcm.value

    visibleValue.value = false
}

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
            form.application_id = data.data.applicant_id
            form.direction = data.data.direction
            form.contact_company = data.data.contact_id
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
            form.contact_company = data.data.contact_company
            form.direction = data.data.direction
            form.date_attention = data.data.date_attention
            form.application_id = data.data.application_id
            form.contact_application = data.data.contact_application
            form.department = data.data.department
            form.district = data.data.district
            form.province = data.data.province
            form.reference = data.data.reference
            form.origin = data.data.origin
            form.project = data.data.project
            form.date_init_service = data.data.date_init_service
            form.date_end_monitoring = data.data.date_end_monitoring
            form.users = data.data.users ?? []
            form.details = data.data.details
            form.monitoring = data.data.monitoring
            form.projects = data.data.projects
            form.service_includes = data.data.service_includes
            form.accommodation = data.data.accommodation
            form.travel_expenses = data.data.travel_expenses
            form.days_service = data.data.days_service
            form.personal_transport = data.data.personal_transport
            form.send_sampling = data.data.send_sampling
            form.surveillance = data.data.surveillance
            form.electric_generator = data.data.electric_generator
            form.company_emission_id = data.data.company_emission_id
            form.type_document_required = data.data.type_document_required
            form.number_copy = data.data.number_copy
            form.version = data.data.version
            form.code = data.data.code
            form.items = data.data.items ?? []
            form.observations = data.data.observations
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

const departments = ref([])
const provinces = ref([])
const districts = ref([])

const getDepartments = async (q = null) => {
    try {
        const { data } = await tenant.get('ubigeo/departments', {
            search: q
        })

        if (data.data) {
            departments.value = data.data
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const getProvinces = async (q = null, departament = null) => {
    try {
        const { data } = await tenant.get('ubigeo/provinces', {
            params: {
                search: q,
                departamento_id: departament
            }
        })

        if (data.data) {
            provinces.value = data.data
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const getDistricts = async (q = null, province = null) => {
    try {
        const { data } = await tenant.get('ubigeo/districts', {
            params: {
                search: q,
                provincia_id: province
            }
        })

        if (data.data) {
            districts.value = data.data
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

watch(() => form.department, (newVal) => {
    if (newVal) {
        getProvinces(null, newVal)
    }
})

watch(() => form.province, (newVal) => {
    if (newVal) {
        getDistricts(null, newVal)
    }
})

onMounted(async () => {
    const date = new Date()

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    form.date_attention = `${year}-${month}-${day}`

    await listStore.getCompanies()
    await listStore.getUnitsMeasurement()
    await listStore.getUsers()

    await getDepartments()
    await getProvinces()
    await getDistricts()

    if (route.params.id) {
        await getOrderService(route.params.id)
    }
    else if (route.query?.quoteId) {
        await getQuote(route.query?.quoteId)
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

:deep(.row-selected td) {
    background-color: #dcfce7 !important;
}
</style>
