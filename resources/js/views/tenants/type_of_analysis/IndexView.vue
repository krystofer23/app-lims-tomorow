<template>
    <custom-header title="Tipo de análisis" description="Registro y control de tipos de análisis."
        icon="fa-solid fa-receipt">
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
            <el-table class="border rounded-xl" v-loading="loading" stripe :data="quotes"
                header-cell-class-name="lims-table-header" size="small">
                <el-table-column label="N°"></el-table-column>
                <el-table-column label="Descripción"></el-table-column>
                <el-table-column label="Acciones"></el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import CustomHeader from '../../../components/tenants/CustomHeader.vue';
import { Search } from '@element-plus/icons-vue';
import { handleErrorsExeption } from '../../../stores/handleErrorsExeption.js';
import tenant from '../../../stores/tenant.js';

const filters = reactive({
    search: null,
})

const loading = ref(false)
const typesOfAnalysis = ref([])
const pagination = ref({
    current_page: 0,
    last_page: 0,
    per_page: 0,
    total: 0,
})

const getTypeOfAnalysis = async () => {
    loading.value = true

    try {
        const { data } = await tenant.get(``)
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loading.value = false
    }
}

const loadingSubmit = ref(false)
const form = ref({
    id: null,
})

const onSubmit = async () => {
    loadingSubmit.value = true

    try {
        if (form.value.id) {
            const { data } = await tenant.put(``)
        }
        else {
            const { data } = await tenant.post(``)
        }
    }
    catch (e) {
        handleErrorsExeption(e)
    }
    finally {
        loadingSubmit.value = false
    }
}
</script>

<style scoped>
:deep(.lims-table-header) {
    /* background: #fff ; */
    color: #64748a !important;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    background: linear-gradient(180deg, #f8fafc 0%, #f8fafc 100%) !important;
    height: 42px;
}

:deep(.filters-collapse) {
    border: 0;
}

:deep(.filters-collapse .el-collapse-item) {
    overflow: hidden;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: #ffffff;
    /* box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05); */
}

:deep(.filters-collapse .el-collapse-item__header) {
    height: auto;
    min-height: 52px;
    border-bottom: 1px solid #f1f5f9;
    padding: 0 12px;
    background: linear-gradient(180deg, #f8fafc 0%, #f8fafc 100%);
}

:deep(.filters-collapse .el-collapse-item__wrap) {
    border-bottom: 0;
}

:deep(.filters-collapse .el-collapse-item__content) {
    padding: 18px;
}

:deep(.filters-collapse .el-collapse-item__arrow) {
    color: #64748b;
}

:deep(.filters-collapse .el-input__wrapper),
:deep(.filters-collapse .el-select__wrapper) {
    min-height: 40px;
    border-radius: 12px;
}
</style>