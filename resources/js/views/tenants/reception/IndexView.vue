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
                            <el-button @click="handleEdit(row)" size="small" type="warning" v-tippy="'Editar'">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </el-button>
                            <el-button @click="handleDelete(row.id)" size="small" type="danger" v-tippy="'Eliminar'">
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
                            <label class="text-sm font-medium text-slate-700">Empresa</label>
                            <el-select v-model="form.company_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Solicitante</label>
                            <el-select v-model="form.application_id" clearable filterable
                                :remote-method="remoteMethodCompany" :loading="loadingCompany" class="w-full"
                                placeholder="Selecciona una empresa" size="large">
                                <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                                    :value="company.id" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">N° Orden de servicio</label>
                            <el-select v-model="form.order_id" clearable filterable :remote-method="remoteMethodOrder"
                                :loading="loadingOrder" class="w-full" placeholder="Selecciona una orden" size="large">
                                <el-option v-for="company in orders" :key="company.id" :label="company.code"
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
                            <el-select v-model="form.type_sample" clearable size="large" placeholder="Seleccionar"
                                class="w-full" filterable>
                                <el-option :label="row" :value="row" v-for="row in [
                                    'Agua C.Humano ',
                                    'Agua Natural',
                                    'Agua Proceso ',
                                    'Agua Residual',
                                    'Agua Salina ',
                                    'Aire',
                                    'Emisiones',
                                    'Radiacion no ionizantes',
                                    'Ruido continuo ',
                                    'Ruido Ocupacional',
                                    'Ruido ambiental',
                                    'SSO',
                                    'Suelo',
                                    'Vibración Ambiental ',
                                    'Vibración en Edificios',
                                    'RNI',
                                    'SED',
                                ]" />
                            </el-select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Matriz</label>
                            <el-select v-model="form.matriz" clearable size="large" filterable
                                placeholder="Seleccionar">
                                <el-option :label="row" :value="row" v-for="row in [
                                    'A. R Doméstica (ARD)',
                                    'A. Alimentación de calderas (AAC)',
                                    'A. Calderas (AC)',
                                    'A. Circulación y Enfriamieno (ACE)',
                                    'A. de Mar (AMAR)',
                                    'A. de Piscina (APISC)',
                                    'A. de Reinyección (AREY)',
                                    'A. Inyección y Reinyección (AREY)',
                                    'A. Laguna Artificial (ALA)',
                                    'A. Lixiviación (ALIX)',
                                    'A. Potable (AP)',
                                    'A. Purificada (APU)',
                                    'A. Salobre (ASAL)',
                                    'A.R Industrial (ARI)',
                                    'A.R Municipal (ARM)',
                                    'A.Subterranea (ASB)',
                                    'A.Superficial (AS)',
                                    'Aire (AIR)',
                                    'Emisiones',
                                    'Filtro ',
                                    'Hidrobiológico',
                                    'Lodo (LD)',
                                    'Radiación Electromagnética',
                                    'Residuo solido (RSD)',
                                    'Ruido ambiental',
                                    'Ruido continuo ',
                                    'Ruido Ocupacional',
                                    'Salmuera (SALM)',
                                    'Sedimento (SED)',
                                    'Suelo (SU)',
                                    'Sonometría (SSO)',
                                    'A.de Manantial (AM)',
                                    'Vibración cuerpo entero',
                                    'Vibracion',
                                    'SSO',
                                ]"></el-option>
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

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">
                                Fecha y hora muestreo (Inicio)
                            </label>

                            <el-date-picker style="width: 100%;" v-model="form.date_sampling_init" type="datetime"
                                placeholder="Seleccionar fecha y hora" format="DD/MM/YYYY HH:mm"
                                value-format="YYYY-MM-DD HH:mm:ss" clearable size="large" class="w-full" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">
                                Fecha y hora muestreo (Fin)
                            </label>

                            <el-date-picker style="width: 100%;" v-model="form.date_sampling_end" type="datetime"
                                placeholder="Seleccionar fecha y hora" format="DD/MM/YYYY HH:mm"
                                value-format="YYYY-MM-DD HH:mm:ss" clearable size="large" class="w-full" />
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
                                    { business_name: 'Cliente' },
                                    { business_name: 'GreenLab S.A.C.' }
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

                                <el-popover placement="bottom-end" width="430" trigger="click" v-model:visible="visible"
                                    popper-class="parameter-popover">
                                    <template #default>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                                                    Seleccionar parámetro
                                                </label>

                                                <div class="relative">
                                                    <i
                                                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>

                                                    <input v-model="search" @focus="open = true" @input="filterOptions"
                                                        placeholder="Buscar parámetro..."
                                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100" />

                                                    <div v-if="open"
                                                        class="absolute z-50 mt-2 max-h-44 w-full overflow-auto rounded-xl border border-slate-200 bg-white p-1 shadow-xl">
                                                        <div v-for="item in filtered" :key="item"
                                                            @click="selectItem(item)"
                                                            class="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">
                                                            <i class="fa-solid fa-vial text-xs text-blue-400"></i>
                                                            <span class="truncate">{{ item }}</span>
                                                        </div>

                                                        <div v-if="!filtered.length"
                                                            class="flex items-center gap-2 px-3 py-3 text-xs text-slate-400">
                                                            <i class="fa-regular fa-face-frown"></i>
                                                            Sin resultados
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="flex items-center justify-between border-t border-slate-100 pt-3">
                                                <p class="text-xs text-slate-400">
                                                    Agrega el parámetro seleccionado.
                                                </p>

                                                <el-button type="primary" size="small"
                                                    class="!rounded-lg !border-0 !bg-blue-600 !font-semibold hover:!bg-blue-700"
                                                    @click="handleAddParameter">
                                                    <i class="fa-solid fa-plus mr-1"></i>
                                                    Agregar
                                                </el-button>
                                            </div>
                                        </div>
                                    </template>

                                    <template #reference>
                                        <button
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            Agregar
                                        </button>
                                    </template>
                                </el-popover>
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

                                                <el-input :model-value="row" readonly class="parameter-input" />
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
    type_sample: null,
    matriz: null,
    number_sample: null,
    number_essays: null,
    date_reception: null,
    date_sampling_init: null,
    date_sampling_end: null,
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

const parameters = ref([
    'Aceites y Grasas',
    'Aceites y Grasas (suelo)',
    '%O2',
    'Aceleración',
    'Acidez',
    'Ácido cianhidrico',
    'Ácido clorídrico',
    'Acido fluorhidrico',
    'Ácido nítrico ',
    'Acido perclorico',
    'Acido perclorhidrico',
    'Acido Sulfhídrico',
    'Ácido Sulfúrico ',
    'Alcalinidad por Bicarbonatos',
    'Alcalinidad por Carbonatos',
    'Alcalinidad Total',
    'Aluminio',
    'Aniones ',
    'Amoníaco ',
    'Amonio ',
    'Análisis Granolumétrico por tamizado',
    'AP 42 (CALCULO-MATERIAL PARTICULADO)',
    'Arsénico - Metales (ICP-AES)',
    'Arsénico  (PM10 alto volumen)',
    'Arsénico  (PM10 bajo volumen)',
    'Arsénico  (PM2.5 alto volumen)',
    'Arsénico  (PM2.5 bajo volumen)',
    'Benceno  ',
    'Benzoapireno',
    'Btex (suelo)',
    'Btex (agua)',
    'Bicarbonatos',
    'Bifenilos policlorados (PCBs) ',
    'Bromuro',
    'Cadmio (PM 10 bajo volumen)',
    'Cadmio (PM 10 alto volumen)',
    'Calcio',
    'Campo Eléctrico',
    'Campo Magnético',
    'Capacidad de Intercambio Catiónico',
    'Carbonato de calcio (CaCO3)',
    'Carbonatos',
    'Caudal ',
    'Caudal ultrasonico ',
    'Cianuro Libre',
    'Cianuro Total',
    'Cianuro WAD',
    'Cloro Residual (Libre)  ',
    'Cloro Total ',
    'Cloruro',
    'Cloruro de mercurio ',
    'CO (CTM 34)',
    'CO2 (Parametro NO ACREDITADO)',
    'Cobalto',
    'Cobre',
    'Compuestos Orgánicos Volatiles (aire)',
    'Compuestos Orgánicos Volatiles (agua)',
    'Compuestos Orgánicos Volatiles (suelo)',
    'Color',
    'Conductividad- suelo',
    'Conductividad Electrica (suelo)',
    'Confort Termico',
    'Contenido de Humedad (suelo)',
    'Cromo (PM10  bajo volumen)',
    'Cromo (PM10  alto volumen)',
    'Cromo Hexavalente',
    'Cromo Hexavalente - Suelo',
    'Demanda  Química de Oxígeno',
    'Demanda Bioquímica de Oxigeno',
    'Densidad de flujo magnetico',
    'Densidad de Potencia',
    'Desplazamiento',
    'Determinación de flujo ',
    'Det. Peso Filtros PM 2.5 HV',
    'Det. Peso Filtros PM 2.5 LV',
    'Det. Peso Filtros PM10 HV',
    'Det. Peso Filtros PM10 LV',
    'Det. Peso Filtros PTS ',
    'Det. Peso Partículas Respirables.',
    'Det. Peso Partículas Totales o Inhalable.',
    'Detergentes (SAAM)',
    'Dióxido de Azufre',
    'Dióxido de azufre (SO2)',
    'Dióxido de azufre (SO2) AUTOMATICO',
    'Dióxido de azufre (SO2) -CTM 34',
    'Dióxido de Azufre EPA 6C',
    'Dióxido de Azufre EPA 6',
    'Dióxido de carbono',
    'Dióxido de carbono (CO2)',
    'Dióxido de Nitrógeno (NO2)',
    'Dióxido de Nitrógeno (NO2) AUTOMATICO',
    'Dióxido de Nitrógeno (NO2)-CTM 34',
    'Dióxido de Nitrógeno (NO2)-CTM 22',
    'Dosimetrìa',
    'Dureza Cálcica',
    'Dureza Magnesica',
    'Dureza Total',
    'Emisiones Testo',
    'Enjuague epa 29',
    'Envase N°4 (HNO3 + H2O2)',
    'Enjuague 5 y 6 (HCI) epa 29',
    'Enjuague IMP. 4 (HNO3) (EPA 29)',
    'EPA 6C',
    'EPA 7E',
    'EPA 10',
    'Esteres Ftalatos',
    'Estrés térmico',
    'Estrés térmico por calor',
    'Estrés térmico por frio',
    'Electric field intensity',
    'Fauna (No acreditado)',
    'Fenoles',
    'Flora (No acreditado)',
    'Flow',
    'FLOW ISO 12242:2012',
    'Flujo Magnetico ',
    'Fluoruro',
    'Formas Parasitarias',
    'Fosfato ',
    'Fosforo soluble',
    'Fosforo Total',
    'Fuerza de campo magnetico ',
    'Fracción 1 (C6-C10)',
    'Fración 2 (C10-C28)',
    'Fracción 3 (C28-C40)',
    'H2 AP 42 ',
    'H2S AP 42 ',
    'Hexano',
    'Hidrocarburos Aromaticos Polinucleares (PAHs)',
    'Hidrocarburo No Metano HCNM (No acreditado)',
    'Hidrocarburo No Metano (AP 42)',
    'Hidrocarburo total de petroleo (HTP C10-C40)',
    'Hidrocarburos Totales Expresado como Hexano(HTC)',
    'Hidrocarburos Totales (CTM022)',
    'Hierro',
    'Hierro  (PM10 alto volumen)',
    'Hierro  (PM10 bajo volumen)',
    'Hierro  (PM2.5 bajo volumen)',
    'Hierro (Disuelto)',
    'Humedad',
    'Hidrocargburo de Petroleo ORGANOLEPTICO',
    'Hidrocarburos Totales de Petróleo (C28-C40)',
    'Hidrocarburos No Metano (HNM)',
    'HUMOS METALICOS ',
    'Humos metálicos (Hg, As)',
    'Humos metálicos (Pb, Zn, Cd)',
    'Humos metálicos (Pb, Zn, Cd,As,Sb,Co)',
    'Humos no metánicos',
    'Humidity ( method 04)',
    'Iluminacion',
    'Intensidad de campo electrico',
    'Intensidad de campo magnetico',
    'Intensidad de flujo magnetico',
    'Intensidad de Potencia (W/m2)',
    'Ion Amonio',
    'Macrobentos',
    'Magnesio',
    'Manganeso',
    'Material Flotante',
    'Materia Orgánica, Carbono Orgánico Total(COT)',
    'Material Particulado AP 42',
    'Material Particulado EPA 5',
    'Material Particulado EPA 29',
    'Material Partículado PM10 HV',
    'Material Partículado PM10 LV',
    'Material Particulado PM2.5 HV',
    'Material Partículado PM2.5 LV',
    'Material Particulado PTS',
    'Magnetic Field Strength',
    'Magnetic Flux Density',
    'Mediciones de campo',
    'Mercurio (PM 10 Alto Volumen)',
    'Metal(Aluminio)',
    'Metales Disuelto (Uranio)',
    'Metales Disueltos',
    'Metales disueltos por ICP',
    'Metales Totales (Litio)',
    'Metales Totales (Uranio)',
    'Metales (EPA 29) Hg',
    'Metales (EPA 29) As, Pb y Hg',
    'Metales (EPA 29) ',
    'Lavado de Sonda (EPA-29)',
    'Metales en PM10 (Pb, As, Cd,Cr)',
    'Metales PM10 HV',
    'Metales PM10 LV',
    'Metales PM2.5 HV',
    'Metales PM2.5 LV',
    'Metales (ICP AES)',
    'Metales Totales (ICP OES)',
    'Metales Totales (ICP-OES) (uranio)',
    'Metales Totales (ICP-AES) (cobre)',
    'Metales Totales (Hierro)',
    'Metales Totales (Plomo)',
    'Metano (CH4) AP 42',
    'Meteorologia',
    'Meteorologia radiación solar',
    'Mercurio gaseoso (hg)',
    'Mercurio gaseoso (hg) AUTOMATICO',
    'Monóxido de carbono (CO)',
    'Monóxido de carbono (CO) AUTOMATICO',
    'Monóxido de carbono (CO) -CTM34',
    'Monóxido de carbono (CO) -CTM30',
    'Monoxido de Carbono (EPA-10)',
    'Monoxido de Carbono (EPA-3A)',
    'MPT',
    'N2 AP 42 ',
    'Neblina ácida (ácido nítrico)',
    'Neblina ácida (ácido sulfúrico)',
    'NH3 (A partir del NO2)',
    'Nitratos ',
    'Nitratos + Nitritos ',
    'Nitritos ',
    'Nitrógeno Amoniacal',
    'Nitrógeno Total',
    'Nitrógeno-Nitratos',
    'Nitrógeno-Nitritos',
    'Nivel freatico ',
    'NO (CTM 22)',
    'Nox (Method 7E)',
    'NOx ',
    'NOx (AUTOMATICO)',
    'Nox (CTM-030)',
    'NOx (CTM034)',
    'Olor',
    'Óxido Nítrico',
    'Oxidos de nitrógeno (NOX) CTM 34',
    'Oxidos de nitrógeno (NOX) CTM 22',
    'Oxidos de nitrogeno (EPA 7)',
    'Óxidos Nitrosos (AUTOMATICO)',
    'Oxígeno (O2) CTM 34',
    'Ozono (O3)',
    'Ozono (O3) AUTOMATICOS',
    'O2-CTM034',
    'O2- METHOD 3A',
    'Particula Flotante',
    'PARTICULAS (AP42)',
    'Partículas Respirables.',
    'Particulas Respirables/Humos metálicos (Pb, Cd, Zn)',
    'Partículas Totales o Inhalable.',
    'Pesticidas Organoclorados',
    'Pesticidas Organoclorados (suelo)',
    'Pesticidas Organofosforados',
    'Power Density',
    'pH suelo',
    'Plomo (Metales AP42 )',
    'Plomo (PM10 alto volumen)',
    'Plomo (PM10 bajo volumen)',
    'Plomo (PM2.5 alto volumen)',
    'Plomo (PM2.5 bajo volumen)',
    'Potasio',
    'Potencial Redox ',
    'PTS (PM 10 HV)',
    'Presion (Method 2)',
    'Radiacion Electromagnetica',
    'Radiacion NO Ionizante',
    'Radiacion UV',
    'Radiación UV',
    'RAE CO2',
    'Recuperaco Epa 29',
    'Recuperado IM. 5 y 6 (KMnO4 ACIDO) (EPA29)',
    'Recuperaco Epa 5',
    'Riesgos disergonomicos',
    'Riesgos Psicosociales',
    'Ruido Ambiental',
    'Ruido Ambiental Diurno',
    'Ruido Ambiental Nocturno',
    'Ruido Continuo (24h)',
    'Ruido Continuo (08h)',
    'Ruido Ocupacional',
    'Sabor',
    'Salinidad',
    'Silice cristalina respirables',
    'SO2 (EPA 6C)',
    'Sodio',
    'Sólidos Sedimentables',
    'Sólidos Suspendidos Totales',
    'Sólidos Totales',
    'Sólidos Totales Disueltos',
    'Solidos Volátiles',
    'Solucion de Lavado EPA 5',
    'Solucion de recuperado H2O2 (EPA 16 A)',
    'Solucion de recuperado Buffer de Citrato(EPA 16 A)',
    'SOLUCION EPA-16A',
    'Solucion de Lavado (HNO3 METHOD 29)',
    'SONOMETRIA',
    'Sulfato',
    'Sulfito',
    'Sulfuro ',
    'Sulfuro de hidrógeno (H2S)',
    'Sulfuro de hidrógeno (EPA 16)',
    'Sulfuro de hidrógeno (Epa 18)',
    'Sulfuro de hidrógeno (H2S) AUTOMATICO',
    'Sulfuro de hidrógeno (H2S CTM34)',
    'Textura',
    'Trihalometanos ',
    'Tolueno',
    'TRS (Epa 16A)',
    'Temperatura (Method 2) ',
    'Velocidad',
    'Vibracion',
    'Vibración ambiental',
    'Vibración en edificios',
    'Vibracion Cuerpo Completo',
    'Vibración cuerpo completo ',
    'Vibraciòn Ocupacional Cuerpo Entero',
    'VOC´S',
    'Volatile Organic Compounds (EPA Method 18)',
    'Xileno',
    'Zinc (Metales Totales ICP-OES)',
    'Zinc  (PM10 alto volumen)',
    'Zinc  (PM10 bajo volumen)',
    'Zinc  (PM2.5 alto volumen)',
    'Zinc  (PM2.5 bajo volumen)',
    'T° en Campo',
    'Conductividad en Campo',
    'Caudal (campo)',
    'Cloro Residual (Libre)  ',
    'Cloro Totall (Campo)  ',
    'Oxígeno Disuelto en Campo',
    'pH en Campo',
    'Potencial Redox ',
    'Resistividad en campo',
    'Turbidez en campo',
    'Salinidad (campo)',
    'Flow',
    'Water level',
    '% Materia Orgánica **',
    'Aldicarb **',
    'Aniones **',
    'Bacterias Heterotroficas**',
    'Bicarbonatos',
    'Bifenilos policlorados (PCBs) (**)',
    'BTEX **',
    'Cadmio(PM10 alto volumen)',
    'Cianotoxinas**',
    'Clorofila A**',
    'Cloruro (CL-)',
    'Coliformes Fecales o termotolerantes**',
    'Coliformes Totales**',
    'Color**',
    'Cromo (PM10 alto volumen}',
    'E. Coli**',
    'Enteroccocos**',
    'Escherichie coli**',
    'Etilbenceno',
    'Fitoplacton**',
    'Fitoplancton cuantitativo**',
    'Fitoplancton cualitativo**',
    'FLOW ISO 12242:2012',
    'Formas Parasitarias **',
    'Fosfato  (PO4)-3',
    'Fósforo',
    'H. Bacterias (**)',
    'Helmintn Eggs**',
    'HTP**',
    'Hongos**',
    'Humos metálicos **',
    'Larvas**',
    'Macrobentos**',
    'Macroinvertebrados**',
    'Macrozobentos / Macroinvertebrados *',
    'Material flotante cleosen f Anthropogenic',
    'Metales ICP **',
    'Nemátodos',
    '"Nitratos (NO3--N) +',
    'Nitritos (NO2--N)"',
    'Nitrogeno Total*',
    'Olor ',
    'O.V.L.**',
    'Oil & Grease**',
    'Parasitos y protozoarios **',
    'Particulas Flotantes *',
    'Pesticidas Carbamatos (Aldicarb) (**)',
    'Pesticidas organoclorados (**)',
    'Pesticidas organofosforados (Parathion) (**)',
    'Phytoplankton Quantitative *',
    'Polychlorinated Biphenyls (PCB´s) **',
    'Polynuclear Aromatic Hydrocarbons - PAHs **',
    'Protozoarios Patógenos**',
    'PTS',
    'quistes ooquistes**',
    'Radiacion UV **',
    'Salmonella spp **',
    'Surfactantes Anionicos',
    'Thermotolerantes Fecales (**)',
    'TPH (C10-C28)**',
    'TPH (C11-22)**',
    'TPH (C10-C40)**',
    'Trihalometanos **',
    'Vibrio Cholerae**',
    'Virus **',
    'Xileno**',
    'Zooplacton**',
    'Zooplankton Quantitative *',
    'Zooplancton cuantitativo*',
    'Zooplancton cualitativo*',
    'NOx (CTM-022)',
    'NOx (CTM-034)',
    'Nox (No y No2) EPA 7',
    'NO (CTM-022)',
    'NO (CTM-34)',
    'NO2 (CTM-022)',
    'NO2 (CTM-34)',
    'CO (EPA 10)',
    'CO (CTM34)',
    'Bario - Metales Totales (ICP-AES)',
    'Mercurio gaseoso total',
    'Metales (ICP-AES)',
    'Benzene',
    'Solucion EPA -16A',
    'Metales en filtro',
    'Variación de T° C ',
    'Metales Totales (ICP OES) (CADMIO)',
    'Turbidez',
    'Oxigeno Disuelto',
    'Litio - Metales (ICP-AES) ',
    'Cadmio - Metales (ICP-AES)',
    'Mercurio - Mateales (ICP-AES)',
    'Plomo - Metales (ICP-AES)',
    'Arsenico - Metales (ICP-AES)',
    'Cromo - Metales (ICP-AES)',
    'Cianuro libre (Suelo)',
    'Metales (ICP-AES) Arsenico, Bario, Cadmio, Plomo, Mercurio - Suelo'
])

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

    form.id = row?.id
    form.company_id = row?.company_id
    form.application_id = row?.application_id
    form.order_id = row.order_id
    form.number_chain = row.content?.number_chain
    form.number_report = row.content?.number_report
    form.type_sample = row.content?.type_sample
    form.matriz = row.content?.matriz
    form.number_sample = row.content?.number_sample
    form.number_essays = row.content?.number_essays
    form.date_reception = row.content?.date_reception
    form.date_sampling_init = row.content?.date_sampling_init
    form.date_sampling_end = row.content?.date_sampling_end
    form.date_agreed = row.content?.date_agreed
    form.company_sampling_id = row.content?.company_sampling_id
    form.code_lab = row.content?.code_lab
    form.code_season = row.content?.code_season
    form.condition_report = row.content?.condition_report
    form.other_company_id = row.content?.other_company_id
    form.parameters = row.content?.parameters
    form.observations = row.content?.observations
}

watch(() => filters, (newVal) => {
    getReceptions()
}, { deep: true })

onMounted(async () => {
    await getReceptions()
    await listStore.getCompanies()
    await listStore.getOrderServices()
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
</style>
