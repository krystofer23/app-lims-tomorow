<template>
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div
                class="flex flex-col gap-4 border-b border-slate-200 p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800">Gestión de Muestras</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Registro y control de cadenas de custodia, informes y muestras.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <el-input v-model="filters.search" placeholder="Buscar por razón social, cadena o informe" clearable
                        class="!w-full sm:!w-[320px]">
                        <template #prefix>
                            <el-icon>
                                <Search />
                            </el-icon>
                        </template>
                    </el-input>

                    <el-button type="primary" class="!rounded-xl !px-5" @click="openCreateModal">
                        <el-icon class="mr-1">
                            <Plus />
                        </el-icon>
                        Agregar registro
                    </el-button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Tipo de muestra</label>
                    <el-select v-model="filters.tipoMuestra" placeholder="Seleccionar" clearable class="!w-full">
                        <el-option label="Aire" value="Aire" />
                        <el-option label="Suelo" value="Suelo" />
                        <el-option label="Agua" value="Agua" />
                    </el-select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Matriz</label>
                    <el-select v-model="filters.matriz" placeholder="Seleccionar" clearable class="!w-full">
                        <el-option label="Aire (AIR)" value="Aire (AIR)" />
                        <el-option label="Suelo (SU)" value="Suelo (SU)" />
                        <el-option label="Agua" value="Agua" />
                    </el-select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Condición</label>
                    <el-select v-model="filters.condicion" placeholder="Seleccionar" clearable class="!w-full">
                        <el-option label="Normal" value="Normal" />
                        <el-option label="Observado" value="Observado" />
                        <el-option label="Urgente" value="Urgente" />
                    </el-select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Fecha recepción</label>
                    <el-date-picker v-model="filters.fechaRecepcion" type="date" placeholder="Seleccionar fecha"
                        format="DD/MM/YYYY" value-format="YYYY-MM-DD" class="!w-full" />
                </div>

                <div class="flex items-end">
                    <el-button class="!w-full !rounded-xl" @click="clearFilters">Limpiar filtros</el-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total registros</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">{{ filteredData.length }}</h3>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Muestras Aire</p>
                <h3 class="mt-2 text-2xl font-bold text-sky-600">{{ airCount }}</h3>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Muestras Suelo</p>
                <h3 class="mt-2 text-2xl font-bold text-emerald-600">{{ soilCount }}</h3>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Condición Normal</p>
                <h3 class="mt-2 text-2xl font-bold text-violet-600">{{ normalCount }}</h3>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 p-5">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Listado de registros</h2>
                    <p class="text-sm text-slate-500">Vista resumida basada en tu Excel.</p>
                </div>
            </div>

            <el-table :data="paginatedData" stripe style="width: 100%" class="custom-table">
                <el-table-column prop="razonSocial" label="Razón social" min-width="220" show-overflow-tooltip />
                <el-table-column prop="solicitante" label="Solicitante" min-width="200" show-overflow-tooltip />
                <el-table-column prop="numeroCadena" label="N° Cadena" min-width="130" />
                <el-table-column prop="numeroInforme" label="N° Informe" min-width="130" />
                <el-table-column prop="tipoMuestra" label="Tipo muestra" min-width="130" />
                <el-table-column prop="matriz" label="Matriz" min-width="140" />
                <el-table-column prop="muestraNumero" label="Muestra N°" min-width="110" align="center" />
                <el-table-column prop="fechaRecepcion" label="Fecha recepción" min-width="140" />
                <el-table-column prop="codigoEstacion" label="Estación" min-width="140" />
                <el-table-column prop="condicionReporte" label="Condición" min-width="140">
                    <template #default="scope">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                            :class="getConditionClass(scope.row.condicionReporte)">
                            {{ scope.row.condicionReporte }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column prop="analisisRequeridos" label="Análisis requeridos" min-width="240"
                    show-overflow-tooltip />
                <el-table-column label="Acciones" fixed="right" min-width="150" align="center">
                    <template #default="scope">
                        <div class="flex items-center justify-center gap-2">
                            <!-- <el-button size="small" @click="editRow(scope.row)">Editar</el-button>
                            <el-button size="small" type="danger" plain
                                @click="removeRow(scope.row.id)">Eliminar</el-button> -->

                            <el-button-group>
                                <el-button v-tippy="'Editar'" size="small" type="warning">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </el-button>

                                <el-button v-tippy="'Eliminar'" size="small" type="danger">
                                    <i class="fa-regular fa-trash-can"></i>
                                </el-button>
                            </el-button-group>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end border-t border-slate-200 p-4">
                <el-pagination v-model:current-page="pagination.page" v-model:page-size="pagination.pageSize"
                    :page-sizes="[5, 10, 20, 50]" background layout="total, sizes, prev, pager, next"
                    :total="filteredData.length" />
            </div>
        </div>
    </div>

    <el-dialog top="2vh" :style="{ width: computedDialogWidth }" class="!rounded-xl" v-model="dialogVisible"
        width="900px" destroy-on-close>
        <template #header>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-amber-50 rounded-xl">
                        <i class="fa-solid fa-person-digging text-amber-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">
                            {{ form.id ? 'Editar registro' : 'Agregar registro' }}
                        </h3>
                    </div>
                </div>
            </div>
        </template>

        <div class="px-3 pb-3">
            <div class="grid grid-cols-3 gap-3">
                <div class="">
                    <label for="">Empresa</label>
                    <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                        v-model="form.company_id" filterable class="w-full" placeholder="Selecciona una empresa"
                        size="large">
                        <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                            :value="company.id" />
                    </el-select>
                </div>

                <div class="">
                    <label for="">Solicitante</label>
                    <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                        v-model="form.application_id" filterable class="w-full" placeholder="Selecciona una empresa"
                        size="large">
                        <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                            :value="company.id" />
                    </el-select>
                </div>

                <div>
                    <label for="">N° Orden de servicio</label>
                    <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                        v-model="form.application_id" filterable class="w-full" placeholder="Selecciona una empresa"
                        size="large">
                        <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                            :value="company.id" />
                    </el-select>
                </div>

                <el-divider class="col-span-3">
                    <p class="text-xs uppercase">Datos de cadena de custodia</p>
                </el-divider>

                <div>
                    <label for="">N° Cadena de custodia</label>
                    <el-input clearable size="large" v-model="form.number_chain" placeholder="Ingrese cadena" />
                </div>

                <div>
                    <label for="">N° Informe de ensayo</label>
                    <el-input clearable size="large" v-model="form.number_report" placeholder="Ingrese informe" />
                </div>

                <div>
                    <label for="">Tipo de muestra</label>
                    <el-select clearable size="large" v-model="form.type_sample" placeholder="Seleccionar"
                        class="!w-full">
                        <el-option label="Aire" value="Aire" />
                        <el-option label="Suelo" value="Suelo" />
                        <el-option label="Agua" value="Agua" />
                    </el-select>
                </div>

                <div>
                    <label for="">Matriz</label>
                    <el-input clearable size="large" v-model="form.matriz" placeholder="Ej: Aire (AIR)" />
                </div>

                <div>
                    <label for="">Muestra N°</label>
                    <el-input clearable size="large" v-model="form.number_sample" :min="1" class="!w-full" />
                </div>

                <div>
                    <label for="">N° de ensayos</label>
                    <el-input clearable size="large" v-model="form.number_essays" :min="0" class="!w-full" />
                </div>

                <div>
                    <label for="">Fecha recepción</label>
                    <el-date-picker clearable size="large" v-model="form.date_reception" type="date"
                        placeholder="Seleccionar fecha" format="DD/MM/YYYY" value-format="YYYY-MM-DD" class="!w-full" />
                </div>

                <div>
                    <label for="">Código laboratorio</label>
                    <el-input clearable size="large" v-model="form.code_lab" placeholder="Ingrese código" />
                </div>

                <div>
                    <label for="">Código estación</label>
                    <el-input clearable size="large" v-model="form.code_season" placeholder="Ingrese estación" />
                </div>

                <div>
                    <label for="">Condición del reporte</label>
                    <el-select size="large" v-model="form.condicionReporte" placeholder="Seleccionar" class="!w-full">
                        <el-option label="Normal" value="Normal" />
                        <el-option label="Observado" value="Observado" />
                        <el-option label="Urgente" value="Urgente" />
                    </el-select>
                </div>

                <div>
                    <label for="">Laboratorio Sub-Contrata</label>
                    <el-select clearable :remote-method="remoteMethodCompany" :loading="loadingCompany"
                        v-model="form.other_company_id" filterable class="w-full" placeholder="Selecciona una empresa"
                        size="large">
                        <el-option v-for="company in companies" :key="company.id" :label="company.business_name"
                            :value="company.id" />
                    </el-select>
                </div>

                <div class="md:col-span-2">
                    <label for="">Análisis requeridos</label>
                    <el-input v-model="form.analisisRequeridos" type="textarea" :rows="3"
                        placeholder="Ingrese los análisis requeridos" />
                </div>

                <div class="md:col-span-1">
                    <label for="">Observaciones</label>
                    <el-input v-model="form.observaciones" type="textarea" :rows="3"
                        placeholder="Ingrese observaciones" />
                </div>
            </div>
        </div>

        <template #footer>
            <div class="px-3 pb-3">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <el-button class="!rounded-xl !m-0" @click="handleClose">
                        Cancelar
                    </el-button>
                    <el-button :loading="loadingSave" type="primary" class="!rounded-xl !m-0" @click="saveRecord">
                        <i class="fa-solid fa-cloud-arrow-up me-1.5"></i>
                        Guardar Relaciones
                    </el-button>
                </div>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Plus, Search } from '@element-plus/icons-vue'
import { useWindowSize } from '@vueuse/core';
import { useListStore } from '../../../stores/list'

const { width: windowWidth } = useWindowSize();

const listStore = useListStore();
const dialogVisible = ref(false)
const loadingSave = ref(false)

const loadingCompany = ref(false)
const companies = computed(() => listStore.companies)

const remoteMethodCompany = async (q) => {
    loadingCompany.value = true
    await listStore.getCompanies(q)
    loadingCompany.value = false
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
    search: '',
    tipoMuestra: '',
    matriz: '',
    condicion: '',
    fechaRecepcion: ''
})

const pagination = reactive({
    page: 1,
    pageSize: 10
})

const initialData = [
    {
        id: 1,
        razonSocial: 'ENVIROTOOL EIRL',
        solicitante: 'ENVIROTOOL EIRL',
        numeroOrdenServicio: '',
        numeroCadena: '2603-01',
        numeroInforme: '2603-01',
        tipoMuestra: 'Aire',
        matriz: 'Aire (AIR)',
        muestraNumero: 1,
        numeroEnsayos: 0,
        fechaRecepcion: '2026-03-01',
        codigoLaboratorio: '2603-01-01',
        codigoEstacion: 'CA-01',
        condicionReporte: 'Normal',
        laboratorioSubcontrata: 'NO APLICA',
        analisisRequeridos: '',
        observaciones: ''
    },
    {
        id: 2,
        razonSocial: 'CONSORCIO WICHAYPAMPA',
        solicitante: 'VIGU & EMI GESTION AMBIENTAL SAC',
        numeroOrdenServicio: 'OS 2602-63',
        numeroCadena: '2603-08',
        numeroInforme: '2603-08',
        tipoMuestra: 'Suelo',
        matriz: 'Suelo (SU)',
        muestraNumero: 1,
        numeroEnsayos: 4,
        fechaRecepcion: '2026-03-02',
        codigoLaboratorio: '2603-08-01',
        codigoEstacion: 'MA-SUE-01',
        condicionReporte: 'Normal',
        laboratorioSubcontrata: 'NO APLICA',
        analisisRequeridos: 'Fracción 1 (C6-C10), Cromo Hexavalente - Suelo, Cianuro Total, Metales Totales (ICP AES)',
        observaciones: ''
    },
    {
        id: 3,
        razonSocial: 'CONSORCIO CONSTRUCTOR M2 LIMA',
        solicitante: 'CONSORCIO CONSTRUCTOR M2 LIMA',
        numeroOrdenServicio: 'OS 2602-01',
        numeroCadena: '2603-09',
        numeroInforme: '2603-09',
        tipoMuestra: 'Aire',
        matriz: 'Aire (AIR)',
        muestraNumero: 1,
        numeroEnsayos: 8,
        fechaRecepcion: '2026-03-02',
        codigoLaboratorio: '2603-09-01',
        codigoEstacion: 'CA-01',
        condicionReporte: 'Normal',
        laboratorioSubcontrata: 'NO APLICA',
        analisisRequeridos: 'PM10 HV, Metales PM10 HV, PM2.5 LV, SO2, NO2, CO, H2S, Meteorología',
        observaciones: ''
    }
]

const tableData = ref([...initialData])

const emptyForm = () => ({
    id: null,
    company_id: null,
    application_id: null,
    order_id: null,
    number_chain: '',
    number_report: '',

    tipoMuestra: '',
    matriz: '',
    muestraNumero: 1,
    numeroEnsayos: 0,
    fechaRecepcion: '',
    codigoLaboratorio: '',
    codigoEstacion: '',
    condicionReporte: 'Normal',
    laboratorioSubcontrata: 'NO APLICA',
    analisisRequeridos: '',
    observaciones: ''
})

const form = reactive(emptyForm())

const normalize = (value) => String(value ?? '').toLowerCase().trim()

const filteredData = computed(() => {
    return tableData.value.filter((item) => {
        const textMatch =
            !filters.search ||
            [item.razonSocial, item.solicitante, item.numeroCadena, item.numeroInforme, item.codigoEstacion]
                .some((field) => normalize(field).includes(normalize(filters.search)))

        const tipoMatch = !filters.tipoMuestra || item.tipoMuestra === filters.tipoMuestra
        const matrizMatch = !filters.matriz || item.matriz === filters.matriz
        const condicionMatch = !filters.condicion || item.condicionReporte === filters.condicion
        const fechaMatch = !filters.fechaRecepcion || item.fechaRecepcion === filters.fechaRecepcion

        return textMatch && tipoMatch && matrizMatch && condicionMatch && fechaMatch
    })
})

const paginatedData = computed(() => {
    const start = (pagination.page - 1) * pagination.pageSize
    const end = start + pagination.pageSize
    return filteredData.value.slice(start, end)
})

const airCount = computed(() => tableData.value.filter((item) => item.tipoMuestra === 'Aire').length)
const soilCount = computed(() => tableData.value.filter((item) => item.tipoMuestra === 'Suelo').length)
const normalCount = computed(() => tableData.value.filter((item) => item.condicionReporte === 'Normal').length)

const clearFilters = () => {
    filters.search = ''
    filters.tipoMuestra = ''
    filters.matriz = ''
    filters.condicion = ''
    filters.fechaRecepcion = ''
    pagination.page = 1
}

const resetForm = () => {
    Object.assign(form, emptyForm())
}

const openCreateModal = () => {
    resetForm()
    dialogVisible.value = true
}

const editRow = (row) => {
    Object.assign(form, { ...row })
    dialogVisible.value = true
}

const saveRecord = () => {
    if (!form.razonSocial || !form.numeroCadena || !form.numeroInforme || !form.tipoMuestra) {
        ElMessage.warning('Completa los campos obligatorios principales.')
        return
    }

    if (form.id) {
        const index = tableData.value.findIndex((item) => item.id === form.id)
        if (index !== -1) {
            tableData.value[index] = { ...form }
            ElMessage.success('Registro actualizado correctamente')
        }
    } else {
        tableData.value.unshift({
            ...form,
            id: Date.now()
        })
        ElMessage.success('Registro agregado correctamente')
    }

    dialogVisible.value = false
    resetForm()
}

const removeRow = async (id) => {
    try {
        await ElMessageBox.confirm('¿Deseas eliminar este registro?', 'Confirmación', {
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            type: 'warning'
        })

        tableData.value = tableData.value.filter((item) => item.id !== id)
        ElMessage.success('Registro eliminado correctamente')
    } catch {
        // cancelado
    }
}

const getConditionClass = (condition) => {
    const classes = {
        Normal: 'bg-emerald-100 text-emerald-700',
        Observado: 'bg-amber-100 text-amber-700',
        Urgente: 'bg-rose-100 text-rose-700'
    }

    return classes[condition] || 'bg-slate-100 text-slate-700'
}
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
</style>
