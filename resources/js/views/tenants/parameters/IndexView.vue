<template>
    <custom-header title="Parametros" description="Registro y control de parametros." icon="fa-solid fa-receipt">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
            <el-input v-model="filters.search" placeholder="Buscar razón social, cadena o informe..." clearable
                class="!w-full sm:!w-[260px]">
                <template #prefix>
                    <el-icon class="text-slate-400">
                        <search />
                    </el-icon>
                </template>
            </el-input>

            <el-button type="primary"
                class="!h-8 !rounded-xl !border-0 !bg-gradient-to-r !from-emerald-400 !to-teal-500 !px-5 !font-medium !text-white !shadow-md !shadow-emerald-100 hover:!opacity-90">
                Agregar Registro
            </el-button>
        </div>
    </custom-header>

    <div class="bg-white p-5 space-y-4">
        <div class="overflow-x-auto">
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="parameters"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="description" label="Descripción"></el-table-column>
                <el-table-column label="Tipo de análisis">
                    <template #default="{ row }">

                    </template>
                </el-table-column>
                <el-table-column label="Acciones">
                    <template #default="{ row }">

                    </template>
                </el-table-column>
                <template #empty>
                    <div class="py-16 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                            <i class="fa-solid fa-flask-vial text-2xl"></i>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-900">
                            No hay parametros registrados
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Ajusta los filtros o registra una nuevo parametro para continuar.
                        </p>

                        <el-button class="mt-4 !rounded-xl !font-semibold" plain>
                            Limpiar filtros
                        </el-button>
                    </div>
                </template>
            </el-table>
        </div>

        <div class="px-2 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-semibold text-slate-700">{{ parameters.length }}</span> de
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span> registros
            </p>

            <el-pagination background layout="prev, pager, next, sizes" :total="pagination.total"
                v-model:page-size="pagination.per_page" v-model:current-page="pagination.current_page"
                :page-sizes="[10, 20, 50, 100]" @change="getParameters" />
        </div>
    </div>

    <el-dialog v-model="visible">

    </el-dialog>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import { handleErrorsExeption } from "../../../stores/handleErrorsExeption"
import tenant from "../../../stores/tenant"
import { ElNotification } from 'element-plus';

const visible = ref(false)

const filters = reactive({
    search: null,
})

const loading = ref(false)
const parameters = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0
})

const loadingSubmit = ref(false)

const form = ref({
    id: null,
    description: null
})

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (form.value.id) {
            const { data } = await tenant.put(`parameters/${form.value.id}`, form.value)
            ElNotification.success(data.message)
        }
        else {
            const { data } = await tenant.post(`parameters`, form.value)
            ElNotification.success(data.message)
        }

        getParameters()
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}

const getParameters = async (page = 1) => {
    loading.value = true

    try {
        const { data } = await tenant.get(`parameters?page=${page}`, {
            params: {
                filters
            }
        })

        if (data.data) {
            parameters.value = data.data.data
            pagination.value = {
                current_page: data.data.current_page,
                last_page: data.data.last_page,
                per_page: data.data.per_page,
                total: data.data.total
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

onMounted(() => {
    getParameters()
})
</script>