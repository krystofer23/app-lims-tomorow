<template>
    <custom-header title="Gestión de Muestras"
        description="Registro y control de cadenas de custodia, informes y muestras." icon="fa-regular fa-file-lines">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
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
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                <!-- <i class="fa-regular fa-file-lines mr-2"></i> -->
                Agregar Registro
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <el-collapse v-model="activeNames" class="filters-collapse mb-5">
            <el-collapse-item name="1">
                <template #title>
                    <div class="flex w-full items-center justify-between pr-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-100 text-teal-600 ring-1 ring-cyan-100">
                                <i class="fa-solid fa-filter text-sm"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Filtros de búsqueda
                                </p>
                                <p class="text-xs text-slate-400">
                                    Refina las cotizaciones por comercial, empresa u orden generada
                                </p>
                            </div>
                        </div>
                    </div>
                </template>

                <template #default>
                </template>
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
            <el-table class="border rounded-xl" stripe :data="receptions" v-loading="loading"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column type="index" width="60" align="center" fixed="left">
                    <template #header>N°</template>
                </el-table-column>
                <el-table-column width="300">
                    <template #header>Razón Social</template>
                    <template #default="{ row }">
                        <p class="truncate font-semibold" v-tippy="row?.company?.business_name">{{
                            row?.company?.business_name }}</p>
                        <p>
                            RUC: {{ row?.company?.ruc }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="300">
                    <template #header>Solicitante</template>
                    <template #default="{ row }">
                        <div class="flex items-center gap-3 py-1">
                            <!-- <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                                <i class="fa-solid fa-user-tie text-[10px]"></i>
                            </div> -->

                            <div class="min-w-0">
                                <p class="line-clamp-2 text-xs font-semibold">
                                    {{ row?.application?.business_name ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>N° Orden de Servicio</template>
                    <template #default="{ row }">
                        <p class="truncate font-semibold">
                            {{ row?.order?.code }}
                        </p>
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>N° Cadena de Custodia</template>
                    <template #default="{ row }">
                        {{ row?.number_chain }}
                    </template>
                </el-table-column>
                <el-table-column width="200">
                    <template #header>N° de Informe de Ensayo</template>
                    <template #default="{ row }">
                        {{ row?.number_report }}
                    </template>
                </el-table-column>
                <el-table-column label="Tipo de muestra" min-width="180">
                    <template #default="{ row }">
                        <span
                            class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                            {{ row?.type_of_sample?.description || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Matriz" min-width="160">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row.matrix?.description || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Muestra número N°" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.number_sample || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="N° de Ensayos" min-width="150" align="center">
                    <template #default="{ row }">
                        <span
                            class="inline-flex min-w-[36px] justify-center rounded-lg bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                            {{ row?.number_essays || 0 }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Recepción" min-width="220">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.date_reception || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Muestreo (Inicio)" min-width="240">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.date_sampling_init_date || '-' }}
                            {{ row?.date_sampling_init_time || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha y Hora de Muestreo (Final)" min-width="240">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.date_sampling_end_date || '-' }}
                            {{ row?.date_sampling_end_time || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Fecha pactada" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.date_agreed || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Muestreo por" min-width="180">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.company_sampling_id || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Código de Laboratorio" min-width="280">
                    <template #default="{ row }">
                        <span class="font-mono text-xs text-slate-700 break-words">
                            {{ row?.code_lab || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Código de estación de muestreo" min-width="240">
                    <template #default="{ row }">
                        <span class="font-mono text-xs text-slate-700 break-words">
                            {{ row?.code_season || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Condición del reporte" min-width="190">
                    <template #default="{ row }">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                            :class="getConditionClass(rownt?.condition_report)">
                            {{ row?.condition_report || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="Laboratorio Sub-Contrata" min-width="220">
                    <template #default="{ row }">
                        <span class="text-sm text-slate-700">
                            {{ row?.other_company_id || '-' }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column width="140" fixed="right">
                    <template #header>Acciones</template>
                    <template #default="{ row }">
                        <div class="flex items-center justify-start gap-2">
                            <el-dropdown trigger="click" placement="bottom-end">
                                <button type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 active:scale-95">
                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                </button>

                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item @click="handleEdit(row)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                                <span>Editar registro</span>
                                            </div>
                                        </el-dropdown-item>

                                        <el-dropdown-item divided @click="handleDelete(row.id)">
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fa-regular fa-trash-can"></i>
                                                <span>Eliminar registro</span>
                                            </div>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </template>
                    <template #empty>
                        <div class="py-16 text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                                <i class="fa-solid fa-flask-vial text-2xl"></i>
                            </div>

                            <h3 class="mt-4 text-sm font-bold text-slate-900">
                                No hay cotizaciones registradas
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Ajusta los filtros o registra una nueva cotización para continuar.
                            </p>

                            <el-button class="mt-4 !rounded-xl !font-semibold" plain>
                                Limpiar filtros
                            </el-button>
                        </div>
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

                <el-tabs type="card">
                    <el-tab-pane>
                        <template #label>
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-7 w-7 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-sky-200">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wide text-slate-700">
                                        Datos de cadena de custodia
                                    </h4>
                                    <p class="-mt-0.5 text-[10px] text-slate-500">
                                        Datos operativos y de trazabilidad del registro.
                                    </p>
                                </div>
                            </div>
                        </template>

                        <section class="rounded-2xl border border-slate-200 bg-white p-4 md:p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">N° Cadena de custodia</label>
                                    <el-input v-model="form.number_chain" clearable size="large"
                                        placeholder="Ingrese cadena" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">N° Informe de ensayo</label>
                                    <el-input v-model="form.number_report" clearable size="large"
                                        placeholder="Ingrese informe" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Tipo de muestra</label>
                                    <el-select v-model="form.type_of_sample_id" clearable size="large"
                                        placeholder="Seleccionar" class="w-full" filterable>
                                        <el-option :label="row.description" :value="row.id"
                                            v-for="row in typesSampling" />
                                    </el-select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Matriz</label>
                                    <el-select v-model="form.matrix_id" clearable size="large" filterable
                                        placeholder="Seleccionar">
                                        <el-option :label="row.description" :value="row.id"
                                            v-for="row in matrixs"></el-option>
                                    </el-select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Muestra N°</label>
                                    <el-input v-model="form.number_sample" clearable size="large" :min="1"
                                        class="w-full" placeholder="Ingrese número" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">N° de ensayos</label>
                                    <el-input v-model="form.number_essays" clearable size="large" :min="0"
                                        class="w-full" placeholder="Ingrese cantidad" />
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

                                        <el-date-picker style="width: 100%;" v-model="form.date_sampling_init_date"
                                            type="date" placeholder="Seleccionar fecha" format="DD/MM/YYYY"
                                            value-format="YYYY-MM-DD" clearable size="large" class="w-full" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700">
                                            Hora muestreo (Inicio)
                                        </label>

                                        <el-time-picker style="width: 100%;" v-model="form.date_sampling_init_time"
                                            placeholder="Seleccionar hora" format="HH:mm" value-format="HH:mm:ss"
                                            clearable size="large" class="w-full" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700">
                                            Fecha muestreo (Fin)
                                        </label>

                                        <el-date-picker style="width: 100%;" v-model="form.date_sampling_end_date"
                                            type="date" placeholder="Seleccionar fecha" format="DD/MM/YYYY"
                                            value-format="YYYY-MM-DD" clearable size="large" class="w-full" />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700">
                                            Hora muestreo (Fin)
                                        </label>

                                        <el-time-picker style="width: 100%;" v-model="form.date_sampling_end_time"
                                            placeholder="Seleccionar hora" format="HH:mm" value-format="HH:mm:ss"
                                            clearable size="large" class="w-full" />
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
                                    <el-input v-model="form.code_lab" clearable size="large"
                                        placeholder="Ingrese código" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Código estación</label>
                                    <el-input v-model="form.code_season" clearable size="large"
                                        placeholder="Ingrese estación" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Condición del reporte</label>
                                    <el-select clearable v-model="form.condition_report" size="large"
                                        placeholder="Seleccionar" class="w-full">
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
                                        <el-option v-for="company in companies" :key="company.id"
                                            :label="company.business_name" :value="company.id" />
                                    </el-select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Codigo de la muestra</label>
                                    <el-input clearable size="large" placeholder="Ingrese el codigo de muestra"
                                        v-model="form.code_sample" />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Coordenadas (WGS-84)</label>
                                    <el-input clearable size="large" placeholder="Ingrese las coordenadas"
                                        type="textarea" v-model="form.coordinate" />
                                </div>
                            </div>
                        </section>

                        <section class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
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
                    </el-tab-pane>
                    <el-tab-pane>
                        <template #label>
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-7 w-7 items-center justify-center rounded-xl bg-violet-50 text-violet-600 ring-1 ring-violet-200">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wide text-slate-700">
                                        Detalles adicionales
                                    </h4>
                                    <p class="-mt-0.5 text-[10px] text-slate-500">
                                        Información complementaria del registro.
                                    </p>
                                </div>
                            </div>
                        </template>
                        <section class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 md:p-5">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-12">
                                <section class="col-span-12 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="mb-4 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600">
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

                                        <el-button size="" class="!rounded-lg" type="primary" plain @click="() => {
                                            visible = true
                                        }">
                                            [+]
                                            Parametros
                                        </el-button>
                                    </div>

                                    <div class="overflow-hidden rounded-xl border border-slate-200">
                                        <el-table :data="form.parameters" size="small" class="custom-parameter-table"
                                            empty-text="No hay parámetros agregados">
                                            <el-table-column label="Acreditación" width="120">
                                                <template #default="{ row }">
                                                    <div class="flex items-center gap-2">
                                                        {{ row?.condition?.description }}
                                                    </div>
                                                </template>
                                            </el-table-column>

                                            <el-table-column label="Parámetro" min-width="220">
                                                <template #default="{ row }">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="grid h-7 w-7 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                                            <i class="fa-solid fa-vial text-xs"></i>
                                                        </span>

                                                        {{ row?.parameter?.description }}
                                                    </div>
                                                </template>
                                            </el-table-column>

                                            <el-table-column label="Unidad de Medida" min-width="220">
                                                <template #default="{ row }">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="grid h-7 w-7 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                                            <i class="fa-solid fa-ruler-combined text-xs"></i>
                                                        </span>

                                                        {{ row?.unit_measurement?.description }}
                                                    </div>
                                                </template>
                                            </el-table-column>

                                            <el-table-column label="LCM" min-width="120">
                                                <template #default="{ row }">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="grid h-7 w-7 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                                            <i class="fa-solid fa-gauge-simple-high text-xs"></i>
                                                        </span>

                                                        {{ row?.lcm }}
                                                    </div>
                                                </template>
                                            </el-table-column>

                                            <el-table-column label="Acción" width="95" align="center">
                                                <template #default="scope">
                                                    <el-tooltip content="Eliminar parámetro" placement="top">
                                                        <el-button @click="remove(scope.$index)" type="danger" plain
                                                            circle
                                                            class="!h-8 !w-8 !rounded-xl hover:scale-105 transition-all duration-200">
                                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                                        </el-button>
                                                    </el-tooltip>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </div>
                                </section>
                            </div>
                        </section>
                    </el-tab-pane>
                </el-tabs>
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

    <el-dialog v-model="visible" width="1020px" align-center class="!rounded-2xl" destroy-on-close>
        <template #header>
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500">
                    <i class="fa-solid fa-sliders"></i>
                </div>

                <div class="flex flex-col gap-1">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Seleccionar parámetros
                    </h2>
                    <p class="-mt-2 text-sm text-slate-500">
                        Busca y revisa los parámetros disponibles.
                    </p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
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
                    <div class="col-span-12 md:col-span-3">
                        <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-100">
                                <i class="fa-solid fa-certificate text-teal-500 text-xs"></i>
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

                    <div class="col-span-12 md:col-span-3">
                        <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </div>
                            <span>Tipo de muestra</span>
                        </div>
                        <el-select disabled v-model="form.type_of_sample_id" clearable filterable size="large"
                            placeholder="Seleccionar tipo" class="w-full">
                            <template #prefix>
                                <i class="fa-solid fa-tags text-slate-400"></i>
                            </template>
                            <el-option :label="row.description" :value="row.id"
                                v-for="row in typesSampling"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </div>
                            <span>Matriz</span>
                        </div>

                        <el-select disabled v-model="form.matrix_id" clearable filterable size="large"
                            placeholder="Seleccionar tipo" class="w-full">
                            <template #prefix>
                                <i class="fa-solid fa-tags text-slate-400"></i>
                            </template>
                            <el-option :label="row.description" :value="row.id" v-for="row in matrixs"></el-option>
                        </el-select>
                    </div>

                    <div class="col-span-12 md:col-span-3">
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
                <el-table size="small" v-loading="loadingParameter" :data="parameters" height="280" stripe
                    empty-text="No se encontraron parámetros" class="parameter-table" @row-click="toggleItem"
                    :row-class-name="tableRowClassName">
                    <el-table-column fixed="left" label="" width="70" align="center">
                        <template #default="{ row }">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full border transition-all duration-200"
                                :class="isSelected(row)
                                    ? 'border-cyan-600 bg-cyan-600 text-white shadow-sm shadow-cyan-200'
                                    : 'border-slate-300 bg-white text-slate-400 hover:border-cyan-500 hover:bg-cyan-50 hover:text-cyan-600'">
                                <i class="fa-solid text-xs" :class="isSelected(row) ? 'fa-check' : 'fa-plus'"></i>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="140">
                        <template #header>
                            <div class="flex items-center gap-2">
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

                    <el-table-column min-width="240">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-vial text-cyan-600"></i>
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

                    <el-table-column min-width="300">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-flask-vial text-indigo-600"></i>
                                <span>Ensayo</span>
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
                                        {{ row?.reference?.code || 'Sin descripción' }}
                                    </span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="300">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-microscope text-purple-600"></i>
                                <span>Metodología</span>
                            </div>
                        </template>

                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ row?.reference?.title || 'Sin descripción' }}
                                    </span>
                                    <p v-if="row?.type" class="text-xs text-slate-400 mt-0.5">
                                        Tipo: {{ row?.type }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="200">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-ruler-combined text-orange-600"></i>
                                <span>Unidad</span>
                            </div>
                        </template>

                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                    <i class="fa-solid fa-ruler-combined text-sm"></i>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ row?.unit_measurement?.description || row?.unitMeasurement?.description ||
                                            'Sin descripción' }}
                                    </span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column min-width="140">
                        <template #header>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-gauge-simple-high text-rose-600"></i>
                                <span>LCM</span>
                            </div>
                        </template>

                        <template #default="{ row }">
                            <div class="flex items-center gap-3 py-1">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                    <i class="fa-solid fa-gauge-simple-high text-sm"></i>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ row?.lcm || 'Sin descripción' }}
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
                    @current-change="(page) => listStore.getParameters(page, form.matrix_id, form.type_of_sample_id, condition, type_of_analysis, form.order_id)" />
            </div>
        </div>
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
import CustomHeader from '../../../components/tenants/CustomHeader.vue';

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
    condition_report: 'Normal',
    other_company_id: null,
    parameters: [],
    observations: null,
    code_sample: null,
    coordinate: 'E:\nN:'
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
    form.code_sample = row.code_sample
    form.coordinate = row.coordinate
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

const formatDate = (iso) => {
    const d = new Date(iso);
    return d.toLocaleDateString("es-PE", { year: "numeric", month: "short", day: "2-digit" });
}

const formatTime = (iso) => {
    const d = new Date(iso);
    return d.toLocaleTimeString("es-PE", { hour: "2-digit", minute: "2-digit" });
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
        listStore.getParameters(1, matrixId, typeOfSampleId, conditionId, type_of_analysisId, form.order_id)
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
    /* border-color: #f59e0b; */
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

:deep(.el-select__wrapper) {
    border-radius: 12px !important;
}
</style>
