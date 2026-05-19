<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4 shadow-sm lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-regular fa-file-lines text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            Gestión de Muestras
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Registro y control de cadenas de custodia, informes y muestras.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[360px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <Search />
                    </el-icon>
                </template>
            </el-input>

            <el-button @click="() => {
                dialogVisible = true
            }" type="primary"
                class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <i class="fa-regular fa-file-lines mr-2"></i>
                Agregar Registro
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <!-- <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div
                class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total registros</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-800">
                            {{ pagination.total }}
                        </h3>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:bg-slate-200 transition">
                        <i class="fa-solid fa-database text-lg"></i>
                    </div>
                </div>

                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-slate-100 opacity-40"></div>
            </div>

            <div
                class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Muestras Aire</p>
                        <h3 class="mt-2 text-3xl font-bold text-sky-600">

                        </h3>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600 group-hover:bg-sky-200 transition">
                        <i class="fa-solid fa-wind text-lg"></i>
                    </div>
                </div>

                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-sky-100 opacity-40"></div>
            </div>

            <div
                class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Muestras Suelo</p>
                        <h3 class="mt-2 text-3xl font-bold text-emerald-600">

                        </h3>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition">
                        <i class="fa-solid fa-seedling text-lg"></i>
                    </div>
                </div>

                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-100 opacity-40"></div>
            </div>

            <div
                class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Condición Normal</p>
                        <h3 class="mt-2 text-3xl font-bold text-violet-600">

                        </h3>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600 group-hover:bg-violet-200 transition">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                </div>

                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-violet-100 opacity-40"></div>
            </div>
        </div> -->

        <el-collapse>
            <el-collapse-item>
                <template #title>
                    <i class="fa-solid fa-filter"></i>
                    Filtros
                </template>
                <div class="grid grid-cols-12">

                </div>
            </el-collapse-item>
        </el-collapse>

        <div class="grid grid-cols-12">
            <div class="col-span-6 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex flex-col gap-3 md:flex-row md:items-end">
                    <div class="min-w-0 flex-1">
                        <label class="mb-1.5 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-50 text-blue-600">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            Buscar cadena de custodia
                        </label>

                        <el-input v-model="filters.number_chain" placeholder="Ingrese código de cadena..." clearable
                            class="!w-full">
                            <template #prefix>
                                <i class="fa-solid fa-barcode text-slate-400"></i>
                            </template>
                        </el-input>
                    </div>

                    <button @click="handleGenerateOT()"
                        class="inline-flex h-8 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-sky-500 px-5 text-sm font-semibold text-white shadow-md shadow-blue-100 transition-all duration-200 hover:shadow-lg active:translate-y-0 md:w-auto">
                        <i class="fa-solid fa-file-circle-plus text-sm"></i>
                        Generar OT
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <el-table :data="receptions" v-loading="loading" stripe :header-cell-style="headerStyle"
                :row-class-name="rowClassName" class="custom-table w-full" table-layout="auto">
                <el-table-column fixed="left">
                    <template #default="{ row }">
                        <el-button v-tippy="'Replicar'" size="small" type="warning" plain @click="toReply(row)">
                            <i class="fa-solid fa-repeat"></i>
                        </el-button>
                    </template>
                </el-table-column>

                <el-table-column label="Razón Social" min-width="220">
                    <template #default="{ row }">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 text-xs max-w-[140px]">
                                {{ row.company?.business_name || '-' }}
                            </p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="Solicitante" min-width="220">
                    <template #default="{ row }">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 text-xs max-w-[140px]">
                                {{ row.application?.business_name || '-' }}
                            </p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="N° Orden de Servicio" min-width="190">
                    <template #default="{ row }">
                        <span class="inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                            {{ row.order?.code || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="N° Cadena de Custodia" min-width="190">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.number_chain || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="N° de Informe de Ensayo" min-width="200">
                    <template #default="{ row }">
                        <span class="font-medium text-slate-700">
                            {{ row.content?.number_report || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Tipo de muestra" min-width="180">
                    <template #default="{ row }">
                        <span
                            class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                            {{ row.content?.type_sample || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Matriz" min-width="160">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.matriz || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Muestra número N°" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.number_sample || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="N° de Ensayos" min-width="150" align="center">
                    <template #default="{ row }">
                        <span
                            class="inline-flex min-w-[36px] justify-center rounded-lg bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                            {{ row.content?.number_essays || 0 }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Recepción" min-width="220">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.date_reception || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Muestreo (Inicio)" min-width="240">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.date_sampling_init || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Muestreo (Final)" min-width="240">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.date_sampling_end || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha pactada" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.date_agreed || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Muestreo por" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.company_sampling_id || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Código de Laboratorio" min-width="280">
                    <template #default="{ row }">
                        <span class="font-mono text-xs text-slate-700 break-words">
                            {{ row.content?.code_lab || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Código de estación de muestreo" min-width="240">
                    <template #default="{ row }">
                        <span class="font-mono text-xs text-slate-700 break-words">
                            {{ row.content?.code_season || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Condición del reporte" min-width="190">
                    <template #default="{ row }">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                            :class="getConditionClass(row.content?.condition_report)">
                            {{ row.content?.condition_report || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Laboratorio Sub-Contrata" min-width="220">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.content?.other_company_id || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column fixed="right" width="120" label="Acciones">
                    <template #default="{ row }">
                        <el-button-group>
                            <el-button plain @click="handleEdit(row)" size="small" type="warning" v-tippy="'Editar'">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </el-button>
                            <el-button plain @click="handleDelete(row.id)" size="small" type="danger"
                                v-tippy="'Eliminar'">
                                <i class="fa-regular fa-trash-can"></i>
                            </el-button>
                        </el-button-group>
                    </template>
                </el-table-column>

                <template #empty>
                    <div class="py-10 text-center">
                        <p class="text-sm font-medium text-slate-500">
                            No hay recepciones disponibles
                        </p>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ receptions.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getReceptions" />
        </div>
    </div>

    <el-dialog v-model="dialogVisible" top="2vh" :style="{ width: computedDialogWidth }" width="980px" destroy-on-close
        class="modern-record-dialog !rounded-2xl">
        <template #header>
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div class="flex items-start gap-4">
                    <div
                        class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-100">
                        <i class="fa-solid fa-person-digging text-lg"></i>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold tracking-tight text-slate-700">
                            {{ form.id ? 'Editar registro' : 'Agregar registro' }}
                        </h3>
                        <p class="text-sm text-slate-500">
                            Registra la información de la cadena de custodia, muestra e informe.
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <div class="px-1 pb-2">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-600 ring-1 ring-slate-200">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-700">
                            Datos principales
                        </h4>
                        <p class="text-xs text-slate-500">
                            Información base del cliente y la orden.
                        </p>
                    </div>
                </div>
                <section class="rounded-2xl border border-slate-200 p-4 md:p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">N° Orden de servicio</label>
                            <el-select v-model="form.order_id" clearable filterable :remote-method="remoteMethodOrder"
                                :loading="loadingOrder" class="w-full" placeholder="Selecciona una orden" size="large">
                                <el-option v-for="company in orders" :key="company.id" :label="company.code"
                                    :value="company.id" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Empresa</label>
                            <el-select disabled v-model="form.company_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Solicitante</label>
                            <el-select disabled v-model="form.application_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>
                    </div>
                </section>

                <div class="mb-4 flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-sky-200">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-700">
                            Datos de cadena de custodia
                        </h4>
                        <p class="text-xs text-slate-500">
                            Datos operativos y de trazabilidad del registro.
                        </p>
                    </div>
                </div>

                <section class="rounded-2xl border border-slate-200 bg-white p-4 md:p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">N° Cadena de custodia</label>
                            <el-input v-model="form.number_chain" clearable size="large" placeholder="Ingrese cadena" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">N° Informe de ensayo</label>
                            <el-input v-model="form.number_report" clearable size="large"
                                placeholder="Ingrese informe" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Tipo de muestra</label>
                            <el-select v-model="form.type_of_sample_id" clearable size="large" placeholder="Seleccionar"
                                class="w-full" filterable>
                                <el-option :label="row.description" :value="row.id" v-for="row in typesSampling" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Matriz</label>
                            <el-select v-model="form.matrix_id" clearable size="large" filterable
                                placeholder="Seleccionar">
                                <el-option :label="row.description" :value="row.id" v-for="row in matrixs"></el-option>
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Muestra N°</label>
                            <el-input v-model="form.number_sample" clearable size="large" :min="1" class="w-full"
                                placeholder="Ingrese número" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">N° de ensayos</label>
                            <el-input v-model="form.number_essays" clearable size="large" :min="0" class="w-full"
                                placeholder="Ingrese cantidad" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">
                                Fecha y hora de recepción
                            </label>

                            <el-date-picker style="width: 100%;" v-model="form.date_reception" type="datetime"
                                placeholder="Seleccionar fecha y hora" format="DD/MM/YYYY HH:mm"
                                value-format="YYYY-MM-DD HH:mm:ss" clearable size="large" class="w-full" />
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-700">
                                    Fecha muestreo (Inicio)
                                </label>

                                <el-date-picker style="width: 100%;" v-model="form.date_sampling_init_date" type="date"
                                    placeholder="Seleccionar fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                    clearable size="large" class="w-full" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-700">
                                    Hora muestreo (Inicio)
                                </label>

                                <el-time-picker style="width: 100%;" v-model="form.date_sampling_init_time"
                                    placeholder="Seleccionar hora" format="HH:mm" value-format="HH:mm:ss" clearable
                                    size="large" class="w-full" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-700">
                                    Fecha muestreo (Fin)
                                </label>

                                <el-date-picker style="width: 100%;" v-model="form.date_sampling_end_date" type="date"
                                    placeholder="Seleccionar fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD"
                                    clearable size="large" class="w-full" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-700">
                                    Hora muestreo (Fin)
                                </label>

                                <el-time-picker style="width: 100%;" v-model="form.date_sampling_end_time"
                                    placeholder="Seleccionar hora" format="HH:mm" value-format="HH:mm:ss" clearable
                                    size="large" class="w-full" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">
                                Fecha pactada
                            </label>

                            <el-date-picker style="width: 100%;" v-model="form.date_agreed" type="date"
                                placeholder="Seleccionar fecha y hora" format="DD/MM/YYYY"
                                value-format="YYYY-MM-DD HH:mm:ss" clearable size="large" class="w-full" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">
                                Muestreo Por
                            </label>

                            <el-select v-model="form.company_sampling_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="row in [
                                    { business_name: 'CLIENTE' },
                                    { business_name: 'GREENLAB PERÚ S.A.C.' }
                                ]" :label="row.business_name" :value="row.business_name"></el-option>
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Código laboratorio</label>
                            <el-input v-model="form.code_lab" clearable size="large" placeholder="Ingrese código" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Código estación</label>
                            <el-input v-model="form.code_season" clearable size="large"
                                placeholder="Ingrese estación" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Condición del reporte</label>
                            <el-select clearable v-model="form.condition_report" size="large" placeholder="Seleccionar"
                                class="w-full">
                                <el-option label="Normal" value="Normal" />
                                <el-option label="Observado" value="Observado" />
                                <el-option label="Urgente" value="Urgente" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Laboratorio Sub-Contrata</label>
                            <el-select v-model="form.other_company_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>
                    </div>
                </section>

                <div class="mb-4 flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 ring-1 ring-violet-200">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-700">
                            Detalles adicionales
                        </h4>
                        <p class="text-xs text-slate-500">
                            Información complementaria del registro.
                        </p>
                    </div>
                </div>

                <section class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 md:p-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-12">
                        <section
                            class="col-span-12 md:col-span-7 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                        <i class="fa-solid fa-list-check text-sm"></i>
                                    </span>

                                    <div>
                                        <h3 class="text-sm font-bold text-slate-800">
                                            Análisis requeridos
                                        </h3>
                                        <p class="text-xs text-slate-400">
                                            Parámetros solicitados para la muestra
                                        </p>
                                    </div>
                                </div>

                                <el-button size="" type="primary" plain @click="() => {
                                    visible = true
                                }">
                                    [+]
                                    Parametros
                                </el-button>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <el-table :data="form.parameters" size="small" class="custom-parameter-table"
                                    empty-text="No hay parámetros agregados">
                                    <el-table-column label="Parámetro" min-width="220">
                                        <template #default="{ row }">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="grid h-7 w-7 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                                    <i class="fa-solid fa-vial text-xs"></i>
                                                </span>

                                                <el-input :model-value="row.description" readonly
                                                    class="parameter-input" />
                                            </div>
                                        </template>
                                    </el-table-column>

                                    <el-table-column label="Acción" width="95" align="center">
                                        <template #default="scope">
                                            <el-tooltip content="Eliminar parámetro" placement="top">
                                                <el-button @click="remove(scope.$index)" type="danger" plain circle
                                                    class="!h-8 !w-8 !rounded-xl hover:scale-105 transition-all duration-200">
                                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                                </el-button>
                                            </el-tooltip>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </section>

                        <section
                            class="col-span-12 md:col-span-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="grid h-9 w-9 place-items-center rounded-xl bg-amber-50 text-amber-600">
                                    <i class="fa-regular fa-note-sticky text-sm"></i>
                                </span>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Observaciones
                                    </h3>
                                    <p class="text-xs text-slate-400">
                                        Comentarios adicionales
                                    </p>
                                </div>
                            </div>

                            <el-input v-model="form.observations" type="textarea" :rows="7" resize="none"
                                placeholder="Ingrese observaciones..." class="custom-textarea" />
                        </section>
                    </div>
                </section>
            </div>
        </div>

        <template #footer>
            <div class="mt-2 border-t border-slate-200 px-1 pt-4">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-400">
                        Verifica la información antes de guardar el registro.
                    </p>

                    <div class="flex flex-col-reverse sm:flex-row">
                        <el-button class="!m-0 !h-9 !rounded-xl !border-slate-300 !px-5" @click="handleClose">
                            Cancelar
                        </el-button>

                        <el-button type="primary" :loading="loadingSubmit" @click="onSubmit"
                            class="!h-9 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                            Guardar relaciones
                        </el-button>
                    </div>
                </div>
            </div>
        </template>
    </el-dialog>

    <select-type :number_chain="filters.number_chain" :visible="visibleSelectType" @close="() => {
        visibleSelectType = false
    }" />

    <confirm-dialog ref="confirmRef" />

    <el-dialog v-model="visible" width="820px" class="!rounded-2xl" destroy-on-close>
        <template #header>
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600">
                    <i class="fa-solid fa-sliders"></i>
                </div>

                <div class="flex flex-col gap-1">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Seleccionar parámetros
                    </h2>
                    <p class="text-sm text-slate-500">
                        Busca y revisa los parámetros disponibles.
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600">
                        <i class="fa-solid fa-filter"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-slate-800">
                            Filtros de búsqueda
                        </h3>
                        <p class="text-xs text-slate-500">
                            Filtra los parámetros por nombre, tipo y matriz.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-4">
                        <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </div>
                            <span>Acreditación</span>
                        </div>

                        <el-select v-model="condition" clearable filterable size="large" placeholder="Seleccionar tipo"
                            class="w-full">
                            <template #prefix>
                                <i class="fa-solid fa-tags text-slate-400"></i>
                            </template>
                            <el-option v-for="row in conditions" :value="row.id" :label="row.description"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <i class="fa-solid fa-table-cells-large text-xs"></i>
                            </div>
                            <span>Tipo de Análisis</span>
                        </div>

                        <el-select size="large" v-model="type_of_analysis" clearable filterable class="w-full"
                            placeholder="Seleccionar acreditación">
                            <el-option v-for="row in typesAnalysis" :value="row.id"
                                :label="row.description"></el-option>
                        </el-select>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <el-table v-loading="loadingParameter" :data="parameters" height="420" stripe
                    empty-text="No se encontraron parámetros" class="parameter-table" @row-click="toggleItem"
                    :row-class-name="tableRowClassName">
                    <el-table-column label="" width="70" align="center">
                        <template #default="{ row }">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full border transition-all duration-200"
                                :class="isSelected(row)
                                    ? 'border-cyan-600 bg-cyan-600 text-white shadow-sm shadow-cyan-200'
                                    : 'border-slate-300 bg-white text-slate-400 hover:border-cyan-500 hover:bg-cyan-50 hover:text-cyan-600'">
                                <i class="fa-solid text-xs" :class="isSelected(row) ? 'fa-check' : 'fa-plus'"></i>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="300">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-flask text-cyan-600"></i>
                                <span>Acreditación</span>
                            </div>
                        </template>

                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ row?.condition?.description || 'Sin descripción' }}
                                    </span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="300">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-flask text-cyan-600"></i>
                                <span>Parámetro</span>
                            </div>
                        </template>

                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                    <i class="fa-solid fa-vial-circle-check text-sm"></i>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ row?.parameter?.description || 'Sin descripción' }}
                                    </span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div
                class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-database"></i>
                    </div>

                    <p class="text-sm text-slate-500">
                        Mostrando
                        <span class="font-semibold text-slate-700">
                            {{ parameters.length }}
                        </span>
                        de
                        <span class="font-semibold text-slate-700">
                            {{ paginationParameter.total }}
                        </span>
                        registros
                    </p>
                </div>

                <el-pagination background small layout="prev, pager, next" :total="paginationParameter.total"
                    v-model:page-size="paginationParameter.per_page"
                    v-model:current-page="paginationParameter.current_page"
                    @current-change="(page) => listStore.getParameters(page, form.matrix_id, form.type_of_sample_id, condition, type_of_analysis)" />
            </div>
        </div>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { ElNotification } from 'element-plus'
import { Search } from '@element-plus/icons-vue'
import { useWindowSize } from '@vueuse/core';
import { useListStore } from '../../../stores/list'
import tenant from '../../../stores/tenant';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption';
import SelectType from './modals/SelectType.vue';
import ConfirmDialog from '../../../components/tenants/ConfirmDialog.vue';

const { width: windowWidth } = useWindowSize();

const listStore = useListStore()
const dialogVisible = ref(false)
const visibleSelectType = ref(false)
const confirmRef = ref(null);
const loadingCompany = ref(false)
const companies = computed(() => listStore.companies)
const conditions = computed(() => listStore.conditions)
const typesAnalysis = computed(() => listStore.typesAnalysis)

const typesSampling = ref([])
const matrixs = ref([])

const getTypeOfSamples = async () => {
    try {
        const { data } = await tenant.get(`reception/get-type-of-samples`, {
            params: {
                order_id: form.order_id
            }
        })

        if (data.data) typesSampling.value = data.data ?? []
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const getMatrix = async () => {
    try {
        const { data } = await tenant.get(`reception/get-matrix`, {
            params: {
                order_id: form.order_id,
                type: form.type_of_sample_id,
            }
        })

        if (data.data) matrixs.value = data.data ?? []
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

const condition = ref(null)
const type_of_analysis = ref(null)
const loadingParameter = computed(() => listStore.loadingParameter)

const handleGenerateOT = async () => {
    const ok = await confirmRef.value?.open({
        title: 'Seguro de generar una OT',
        message: 'Si estas seguro dale continuar?',
        confirmText: 'Sí, continuar',
        cancelText: 'Cancelar',
    })

    if (ok) {
        visibleSelectType.value = true
    }
}

const remoteMethodCompany = async (q) => {
    loadingCompany.value = true
    await listStore.getCompanies(q)
    loadingCompany.value = false
}

const loadingOrder = ref(false)
const orders = computed(() => listStore.ordersServices)
const remoteMethodOrder = async (q) => {
    loadingOrder.value = true
    await listStore.getOrderServices(q)
    loadingOrder.value = false
}

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

const filters = reactive({
    search: null,
    number_chain: null,
})

const emptyForm = () => ({
    id: null,
    company_id: null,
    application_id: null,
    order_id: null,
    number_chain: null,
    number_report: null,
    type_of_sample_id: null,
    matrix_id: null,
    number_sample: null,
    number_essays: null,
    date_reception: null,
    date_sampling_init_date: null,
    date_sampling_init_time: null,
    date_sampling_end_date: null,
    date_sampling_end_time: null,
    date_agreed: null,
    company_sampling_id: null,
    code_lab: null,
    code_season: null,
    condition_report: null,
    other_company_id: null,
    parameters: [],
    observations: null,
})

const form = reactive(emptyForm())

const isSelected = (row) => {
    if (!Array.isArray(form.parameters)) return false

    return form.parameters.some((item) => item.id === row.id)
}

const toggleItem = (row) => {
    if (!Array.isArray(form.parameters)) {
        form.parameters = []
    }

    const index = form.parameters.findIndex((item) => item.id === row.id)

    if (index !== -1) {
        form.parameters.splice(index, 1)
        return
    }

    form.parameters.push(row)
}

const tableRowClassName = ({ row }) => {
    return isSelected(row) ? 'selected-row' : ''
}

const resetForm = () => {
    Object.assign(form, emptyForm())
}

const handleClose = () => {
    resetForm()
    dialogVisible.value = false
}

const loading = ref(false)
const receptions = ref([])
const pagination = ref({
    current_page: 0,
    per_page: 0,
    total: 0,
    last_page: 0,
})

const handleDelete = async (id) => {
    const ok = await confirmRef.value?.open({
        title: 'Seguro que deseas eliminar',
        message: 'Si estas seguro dale continuar?',
        confirmText: 'Sí, eliminar',
        cancelText: 'Cancelar',
    })

    if (ok) {
        try {
            const { data } = await tenant.delete(`reception/${id}`)
            ElNotification.success(data.message)
            getReceptions(pagination.value.current_page)
        }
        catch (e) {
            handleErrorsExeption(e)
        }
    }
}

const getReceptions = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`reception?page=${page}`, {
            params: {
                ...filters
            }
        })

        if (data.data) {
            receptions.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                per_page: data.data.per_page,
                total: data.data.total,
                last_page: data.data.last_page,
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

const loadingSubmit = ref(false)

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (form.id) {
            const { data } = await tenant.put(`reception/${form.id}`, form)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`reception`, form)
            ElNotification.success(data.message)
        }

        getReceptions(pagination.value.current_page)
        resetForm();
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const headerStyle = () => {
    return {
        background: '#f8fafc',
        color: '#334155',
        fontWeight: '700',
        fontSize: '13px',
        borderBottom: '1px solid #e2e8f0',
        height: '52px'
    }
}

const rowClassName = ({ rowIndex }) => {
    return rowIndex % 2 === 0 ? 'bg-white' : 'bg-slate-50/40'
}

const getConditionClass = (condition) => {
    if (!condition) {
        return 'bg-slate-100 text-slate-600'
    }

    const value = String(condition).toLowerCase()

    if (value.includes('conforme') || value.includes('aprobado')) {
        return 'bg-emerald-100 text-emerald-700'
    }

    if (value.includes('observado') || value.includes('pendiente')) {
        return 'bg-amber-100 text-amber-700'
    }

    if (value.includes('rechazado') || value.includes('no conforme')) {
        return 'bg-red-100 text-red-700'
    }

    return 'bg-slate-100 text-slate-600'
}

const parameters = computed(() => listStore.parameters)
const paginationParameter = computed(() => listStore.paginationParameter)

const visible = ref(false)
const parameter = ref(null)
const search = ref('')
const open = ref(false)
const filtered = ref([...parameters.value])

const filterOptions = () => {
    filtered.value = parameters.value.filter(item =>
        item.toLowerCase().includes(search.value.toLowerCase())
    )
}

const selectItem = (item) => {
    parameter.value = item
    search.value = item
    open.value = false
}

const handleAddParameter = () => {
    if (!parameter.value) return

    form.parameters.push(parameter.value)

    parameter.value = null
    visible.value = false
}

const remove = async (index) => {
    form.parameters.splice(index, 1)
}

const toReply = (row) => {
    dialogVisible.value = true

    form.company_id = row?.company_id
    form.application_id = row?.application_id
    form.order_id = row?.order_id
    form.number_chain = row.content?.number_chain
    form.number_report = row.content?.number_report
    form.company_sampling_id = row.content?.company_sampling_id
}

const handleEdit = (row) => {
    dialogVisible.value = true

    form.id = row.id
    form.company_id = row.company_id
    form.application_id = row.application_id
    form.order_id = row.order_id
    form.number_chain = row.number_chain
    form.number_report = row.number_report
    form.type_of_sample_id = row.type_of_sample_id
    form.matrix_id = row.matrix_id
    form.number_sample = row.number_sample
    form.number_essays = row.number_essays
    form.date_reception = row.date_reception
    form.date_sampling_init_date = row.date_sampling_init_date
    form.date_sampling_init_time = row.date_sampling_init_time
    form.date_sampling_end_date = row.date_sampling_end_date
    form.date_sampling_end_time = row.date_sampling_end_time
    form.date_agreed = row.date_agreed
    form.company_sampling_id = row.company_sampling_id
    form.code_lab = row.code_lab
    form.code_season = row.code_season
    form.condition_report = row.condition_report
    form.other_company_id = row.other_company_id
    form.parameters = row.parameters
    form.observations = row.observations
}

const getOrder = async () => {
    try {
        const { data } = await tenant.get(`order-service/${form.order_id}`)

        if (data.data) {
            form.company_id = Number(data.data.company_id)
            form.application_id = Number(data.data.application_id)
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

watch(() => form.order_id, async (newVal) => {
    if (newVal) {
        await getOrder()
        await getTypeOfSamples()
        await getMatrix()
    }
    else {
        form.company_id = null
        form.application_id = null
    }
})

watch(() => filters, (newVal) => {
    getReceptions()
}, { deep: true })

watch(() => form.type_of_sample_id, async () => {
    form.matrix_id = null
    await getMatrix()
})

watch(
    () => [form.matrix_id, form.type_of_sample_id, condition.value, type_of_analysis.value],
    ([matrixId, typeOfSampleId, conditionId, type_of_analysisId]) => {
        listStore.getParameters(1, matrixId, typeOfSampleId, conditionId, type_of_analysisId)
    }
)

watch(
    () => [form.matrix_id, form.type_of_sample_id, condition.value],
    ([matrixId, typeOfSampleId, conditionId]) => {
        type_of_analysis.value = null
        listStore.getTypesAnalysis(matrixId, typeOfSampleId, conditionId)
    }
)

onMounted(async () => {
    await getReceptions()

    await listStore.getCompanies()
    await listStore.getConditions()
    await listStore.getOrderServices()
    await listStore.getTypesSampling()
    await listStore.getMatrixs()
})
</script>

<style scoped>
:deep(.el-table th.el-table__cell) {
    background: #f8fafc;
    color: #334155;
    font-weight: 700;
}

:deep(.el-table td.el-table__cell),
:deep(.el-table th.el-table__cell) {
    padding: 14px 0;
}

:deep(.el-popover) {
    border-radius: 10px !important;
}

:deep(.custom-table .el-table__cell) {
    padding-top: 14px;
    padding-bottom: 14px;
    vertical-align: middle;
}

:deep(.custom-table .el-table__row:hover > td.el-table__cell) {
    background-color: #f8fafc !important;
}

:deep(.custom-table .el-table__inner-wrapper::before) {
    display: none;
}

:deep(.custom-table th.el-table__cell) {
    border-bottom: 1px solid #e2e8f0 !important;
}

:deep(.custom-table td.el-table__cell) {
    border-bottom: 1px solid #f1f5f9 !important;
}

:deep(.el-input__wrapper) {
    border-radius: 10px !important;
}

.custom-parameter-table :deep(.el-table__header th) {
    background: #f8fafc !important;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
}

.parameter-input :deep(.el-input__wrapper) {
    box-shadow: none !important;
    background: transparent !important;
    padding-left: 0;
}

.parameter-input :deep(.el-input__inner) {
    color: #334155;
    font-weight: 600;
}

.custom-textarea :deep(.el-textarea__inner) {
    border-radius: 14px;
    border-color: #e2e8f0;
    background: #f8fafc;
    font-size: 13px;
}

.custom-textarea :deep(.el-textarea__inner:focus) {
    border-color: #f59e0b;
    box-shadow: 0 0 0 4px #fef3c7;
}

:deep(.parameter-table .el-table__header-wrapper th) {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
}

:deep(.parameter-table .el-table__row:hover > td) {
    background-color: #ecfeff !important;
}

:deep(.parameter-table .el-table__cell) {
    padding: 10px 0;
}

:deep(.el-dialog) {
    border-radius: 1rem;
}

:deep(.el-dialog__header) {
    margin-right: 0;
    padding-bottom: 14px;
}

:deep(.el-dialog__body) {
    padding-top: 8px;
}

:deep(.selected-row td) {
    background-color: #ecfeff !important;
}

:deep(.selected-row:hover td) {
    background-color: #cffafe !important;
}

:deep(.selected-row td:first-child) {
    border-left: 4px solid #0891b2 !important;
}

:deep(.lims-table .el-table__row) {
    cursor: pointer;
}
</style>
