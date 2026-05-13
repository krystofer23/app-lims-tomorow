<template>
    <div
        class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-5 py-4   lg:flex-row lg:items-center lg:justify-between lg:px-6">
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                            {{ form.id ? 'Editar cotización' : 'Registrar cotización' }}
                        </h1>
                    </div>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        Completa la información general, agrega conceptos y revisa el resumen económico antes de
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
                <i class="fa-solid fa-file-invoice-dollar mr-2"></i>
                {{ form.id ? 'Guardar cambios' : 'Guardar cotización' }}
            </el-button>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 space-y-6">
        <div class="w-full space-y-6">
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-12 xl:col-span-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                                <i class="fa-solid fa-building-circle-check text-sm"></i>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-slate-800">
                                    Datos generales
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Información principal de la cotización
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-600">
                                    Empresa
                                </label>

                                <el-select :remote-method="remoteMethodCompany" :loading="loadingCompany"
                                    v-model="form.company_id" filterable remote class="!w-full" placeholder="Empresa"
                                    size="default">
                                    <el-option v-for="company in companies" :key="company.id"
                                        :label="company.business_name" :value="company.id" />
                                </el-select>
                            </div>

                            <!-- Contacto -->
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-600">
                                    Contacto
                                </label>

                                <el-select :loading="loadingContacts" clearable v-model="form.contact_id" filterable
                                    class="!w-full" placeholder="Contacto" size="default">
                                    <el-option v-for="contact in contacts" :key="contact.id"
                                        :label="contact?.user?.full_name + ' | ' + contact?.type" :value="contact.id" />
                                </el-select>
                            </div>

                            <!-- Dirección -->
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-600">
                                    Dirección
                                </label>

                                <el-input v-model="form.direction" placeholder="Dirección" size="default" />
                            </div>

                            <!-- Fecha -->
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-600">
                                    Fecha de atención
                                </label>

                                <el-date-picker v-model="form.date_attention" type="date" class="!w-full"
                                    placeholder="Fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" size="default" />
                            </div>

                            <!-- Referencia -->
                            <div class="space-y-1 md:col-span-2 lg:col-span-4">
                                <label class="text-xs font-semibold text-slate-600">
                                    Referencia
                                </label>

                                <el-input v-model="form.reference" type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 3 }"
                                    placeholder="Detalle breve o referencia de la cotización" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 xl:col-span-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-100 text-emerald-600">
                                <i class="fa-solid fa-wallet text-xs"></i>
                            </div>

                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-700">
                                    Resumen económico
                                </h3>
                                <p class="text-[11px] text-slate-500">
                                    Totales de la cotización
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="divide-y divide-slate-100">
                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs font-medium text-slate-500">
                                    Matrices
                                </span>

                                <span class="text-xs font-semibold text-slate-800">
                                    S/ {{ formatMoney(itemsTotal) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs font-medium text-slate-500">
                                    Servicios
                                </span>

                                <span class="text-xs font-semibold text-slate-800">
                                    S/ {{ formatMoney(servicesTotal) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs font-medium text-slate-500">
                                    Otros gastos
                                </span>

                                <span class="text-xs font-semibold text-slate-800">
                                    S/ {{ formatMoney(otherExpensesTotal) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs font-medium text-slate-500">
                                    Subtotal
                                </span>

                                <span class="text-xs font-semibold text-slate-800">
                                    S/ {{ formatMoney(subtotal) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs font-medium text-slate-500">
                                    IGV
                                </span>

                                <span class="text-xs font-semibold text-slate-800">
                                    S/ {{ formatMoney(igv) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">
                                        Total
                                    </p>
                                    <p class="text-[11px] text-emerald-600">
                                        Importe final
                                    </p>
                                </div>

                                <span class="text-base font-black text-emerald-700">
                                    S/ {{ formatMoney(total) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                <!-- Fecha -->
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white px-4 py-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                Fecha de atención
                            </p>

                            <p class="mt-1 truncate text-sm font-bold text-slate-800">
                                {{ form.date_attention || 'Sin fecha' }}
                            </p>
                        </div>

                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                            <i class="fa-regular fa-calendar-days text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Conceptos -->
                <div class="overflow-hidden rounded-xl border border-indigo-100 bg-white px-4 py-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wide text-indigo-400">
                                Conceptos agregados
                            </p>

                            <p class="mt-1 text-lg font-black text-indigo-700">
                                {{ form.items.length + form.other_expenses.length }}
                            </p>
                        </div>

                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                            <i class="fa-solid fa-layer-group text-sm"></i>
                        </div>
                    </div>

                    <p class="mt-1 text-[11px] text-slate-400">
                        Ítems y gastos adicionales.
                    </p>
                </div>

                <!-- Total -->
                <div class="overflow-hidden rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                                Total actual
                            </p>

                            <p class="mt-1 truncate text-lg font-black text-emerald-700">
                                S/ {{ formatMoney(total) }}
                            </p>
                        </div>

                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                            <i class="fa-solid fa-coins text-sm"></i>
                        </div>
                    </div>

                    <p class="mt-1 text-[11px] text-emerald-600/80">
                        Monto calculado.
                    </p>
                </div>

                <!-- Frecuencia -->
                <div class="overflow-hidden rounded-xl border border-blue-100 bg-white px-4 py-3">
                    <div class="mb-2 flex items-center gap-2">
                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wide text-blue-600">
                                Frecuencia
                            </p>

                            <p class="truncate text-xs font-semibold text-blue-900">
                                Frecuencia de evaluación
                            </p>
                        </div>
                    </div>

                    <el-select size="small" v-model="frequency" placeholder="Selecciona frecuencia" class="!w-full"
                        clearable filterable>
                        <el-option v-for="item in frequencies" :key="item.value" :label="item.label"
                            :value="item.value" />
                    </el-select>
                </div>
            </div>

            <div class="mt-3 grid gap-4 grid-cols-12">
                <div class="col-span-12 overflow-hidden rounded-xl border border-slate-200 bg-white">
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
                                            <el-button @click.stop="itemDelete(index)" type="danger" plain size="small"
                                                class="!rounded-r-lg">
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

                <div class="col-span-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                                    <i class="fa-solid fa-clipboard-list text-sm"></i>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Servicios
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Conceptos principales de la cotización.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <!-- <el-button size="small" class="!rounded-lg" plain type="primary"
                                        @click="showMatrixModal = true">
                                        <i class="fa-solid fa-layer-group me-1"></i>
                                        Agregar matrices
                                    </el-button> -->

                                <el-button size="small" class="!rounded-lg" plain type="success"
                                    @click="showServiceModal = true">
                                    <i class="fa-solid fa-briefcase-medical me-1"></i>
                                    Agregar servicios
                                </el-button>
                            </div>
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
                                        Servicio
                                    </th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Dias
                                    </th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Cantidad
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
                                        Acción
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="(row, index) in form.services" :key="index"
                                    class="transition hover:bg-slate-50">
                                    <td class="px-3 py-2 text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 text-[11px] font-bold text-slate-600">
                                                {{ index + 1 }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-3 py-2">
                                        {{ row?.item?.description }}
                                    </td>

                                    <td class="px-3 py-2 text-slate-700">
                                        <el-input size="small" v-model="row.item.days" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input size="small" v-model="row.item.amount" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input size="small" v-model="row.item.unit_price" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        {{ row?.item.price }}
                                    </td>

                                    <td :class="row?.item?.bg" class="relative px-3 py-2 text-right">
                                        <el-button @click.stop="itemDelete(index)" type="danger" plain size="small"
                                            class="!rounded-lg">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </el-button>

                                        <div v-if="row?.item?.frequency_label"
                                            class="absolute right-2 top-1 flex items-center gap-1">
                                            <span
                                                class="max-w-[100px] truncate rounded-full bg-teal-500 px-2 py-0.5 text-[10px] font-bold text-white">
                                                {{ row?.item?.frequency_label }}
                                            </span>

                                            <el-button @click.stop="() => {
                                                row.item.select = null
                                                row.item.bg = null
                                                row.item.frequency_label = null
                                            }" plain size="small" class="!rounded-md" type="warning"
                                                v-tippy="'Remover frecuencia'">
                                                <i class="fa-solid fa-eraser text-xs"></i>
                                            </el-button>
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

                <div class="col-span-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <i class="fa-solid fa-receipt text-sm"></i>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        Otros gastos
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Movilidad, viáticos, materiales u otros conceptos.
                                    </p>
                                </div>
                            </div>

                            <el-button size="small" class="!rounded-lg" plain type="warning" @click="state = true">
                                <i class="fa-solid fa-plus me-1"></i>
                                Agregar gasto
                            </el-button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th
                                        class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        #</th>
                                    <th
                                        class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Descripción</th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Días</th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Cantidad</th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        P. Unit.</th>
                                    <th
                                        class="px-3 py-2 text-center text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Importe</th>
                                    <th
                                        class="px-3 py-2 text-right text-[11px] font-bold uppercase tracking-wide text-slate-500">
                                        Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="(expense, index) in form.other_expenses" :key="index"
                                    class="transition hover:bg-slate-50">
                                    <td class="px-3 py-2 text-slate-700">
                                        <span
                                            class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 text-[11px] font-bold text-slate-600">
                                            {{ index + 1 }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-2">
                                        <el-input v-model="expense.description" size="small"
                                            placeholder="Ej: Movilidad, viáticos, materiales..."
                                            class="!min-w-[220px]" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input v-model="expense.days" size="small" class="!w-[65px]" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input v-model="expense.amount" size="small" class="!w-[70px]" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input v-model="expense.unit_price" size="small" class="!w-[80px]" />
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <el-input v-model="expense.price" size="small" disabled class="!w-[90px]" />
                                    </td>

                                    <td class="px-3 py-2 text-right">
                                        <el-button @click="removeOtherExpense(index)" type="danger" plain size="small"
                                            class="!rounded-lg">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </el-button>
                                    </td>
                                </tr>

                                <tr v-if="form.other_expenses.length === 0">
                                    <td colspan="7" class="px-4 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div
                                                class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-400">
                                                <i class="fa-solid fa-receipt text-lg"></i>
                                            </div>

                                            <p class="text-xs font-bold text-slate-600">
                                                No hay otros gastos registrados
                                            </p>

                                            <span class="mt-1 text-[11px] text-slate-400">
                                                Agrega gastos solo si aplican a esta cotización.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-span-12 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600 ring-1 ring-violet-100">
                                <i class="fa-solid fa-note-sticky text-sm"></i>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-slate-800">
                                    Observaciones
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Indicaciones, condiciones o comentarios importantes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <el-input v-model="form.observations" type="textarea" :autosize="{ minRows: 3, maxRows: 5 }"
                            placeholder="Ej: La cotización está sujeta a disponibilidad, tiempos de atención, condiciones de muestreo u observaciones comerciales." />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <services-modal :items="form.services" :show-service-modal="showServiceModal" @close="() => {
        showServiceModal = false
    }" />

    <matriz-modal v-model:items="form.items" :show-matrix-modal="showMatrixModal" @close="() => {
        showMatrixModal = false
    }" />

    <logistic-cast-modal :items="form.other_expenses" :state="state" @close="() => {
        state = false
    }" />

    <el-dialog v-model="visibleValue" class="max-w-[300px] !rounded-lg">
        <div class="mb-3">
            <label class="font-medium mb-0.5">Unidad de medida</label>
            <el-select placeholder=""></el-select>
        </div>
        <div>
            <label class="font-medium mb-0.5">LCM</label>
            <el-select placeholder=""></el-select>
        </div>
    </el-dialog>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ServicesModal from './modal/ServicesModal.vue'
import MatrizModal from './modal/MatrizModal.vue'
import { useListStore } from '../../../stores/list'
import { watch } from 'vue'
import tenant from '../../../stores/tenant'
import { ElNotification } from 'element-plus'
import LogisticCastModal from './modal/LogisticCastModal.vue'
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption'

const visibleValue = ref(true)

const state = ref(false)
const router = useRouter()
const route = useRoute()
const loadingSubmit = ref(false)
const listStore = useListStore()
const companies = computed(() => listStore.companies)

const loadingCompany = ref(false)
const loadingContacts = computed(() => listStore.loadingContacts)
const contacts = computed(() => listStore.contacts)

const remoteMethodCompany = async (q) => {
    loadingCompany.value = true
    await listStore.getCompanies(q)
    loadingCompany.value = false
}

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
    services_total: null,
    igv: null,
    subtotal: null,
    total: null,
    reference: null,
    observations: null,
    contact_id: null,
    items: [],
    services: [],
    other_expenses: []
})

const selectedFrequency = computed(() => {
    return frequencies.find(item => item.value === frequency.value) || null
})

const applyFrequencyToSelected = () => {
    if (!selectedFrequency.value) return

    form.items.forEach(item => {
        if (item.select) {
            item.item = {
                bg: null,
                frequency: null,
                frequency_label: null,
            }

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
    form.date_attention = null
    form.version = null
    form.code = null
    form.items_total = null
    form.other_expenses_total = null
    form.services_total = null
    form.igv = null
    form.subtotal = null
    form.total = null
    form.reference = null
    form.observations = null
    form.contact_id = null
    form.items = []
    form.services = []
    form.other_expenses = []
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
        form.services_total = servicesTotal.value
        form.other_expenses_total = servicesTotal.value

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
        onCancel()
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
    return form.items.reduce((total, item) => {
        return total + Number(item.price || 0);
    }, 0);
});

const servicesTotal = computed(() => {
    return form.services.reduce((total, item) => {
        return total + Number(item.price || 0);
    }, 0);
});

const otherExpensesTotal = computed(() => {
    return form.other_expenses.reduce((acc, expense) => {
        return acc + Number(expense?.price ?? 0)
    }, 0)
})

const subtotal = computed(() => {
    return itemsTotal.value + servicesTotal.value + otherExpensesTotal.value
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
        const unitPrice = Number(row?.unit_price ?? 0)
        const quantity = row?.number_samples ?? 0

        row.price = unitPrice * quantity
    })
}, { deep: true })

watch(() => form.services, (newVal) => {
    newVal.forEach((row) => {
        const unitPrice = Number(row?.item?.unit_price ?? 0)
        const quantity = row?.item?.amount ?? 0
        const days = Number(row?.item?.days ?? 0)

        row.price = unitPrice * quantity * days
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

watch(() => form.company_id, (newVal) => {
    if (newVal) {
        listStore.getCompanies(newVal)
        listStore.getContacts(null, newVal)
    }
})

const getQuote = async (id) => {
    try {
        const { data } = await tenant.get(`quote/${id}`)

        if (data.data) {
            form.id = data.data.id
            form.company_id = data.data.company_id
            form.direction = data.data.direction
            form.date_attention = data.data.date_attention
            form.version = data.data.version
            form.code = data.data.code
            form.items_total = data.data.items_total
            form.other_expenses_total = data.data.other_expenses_total
            form.services_total = data.data.services_total
            form.igv = data.data.igv
            form.subtotal = data.data.subtotal
            form.total = data.data.total
            form.reference = data.data.reference
            form.observations = data.data.observations
            form.contact_id = data.data.contact_id
            form.items = data.data.items
            form.services = data.data.services
            form.other_expenses = data.data.other_expenses
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
}

onMounted(() => {
    listStore.getCompanies()

    const date = new Date()

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    form.date_attention = `${year}-${month}-${day}`

    if (route.params.id) {
        getQuote(route.params.id)
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
