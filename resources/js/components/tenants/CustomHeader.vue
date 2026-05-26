<template>
    <div class="px-4 py-3 bg-white border-b">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2 text-[12px] font-semibold text-slate-600">
                    <span class="inline-flex items-center gap-2">
                        <span
                            class="grid h-8 w-8 place-items-center rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white shadow-sm">
                            <i :class="icon" class="text-[12px]"></i>
                        </span>
                        <span class="truncate">
                            Sistema LIMS
                        </span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="truncate font-extrabold text-slate-900">
                        {{ title }}
                    </span>
                </div>
                <p class="mt-1 truncate text-[11px] font-medium text-slate-500">
                    {{ description }}
                </p>
            </div>
            <div>
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    description: {
        type: String,
        default: ''
    },
    icon: {
        type: String,
        default: ''
    }
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

.lims-quotes-table :deep(.el-table__header th) {
    background: #f8fafc !important;
    color: #334155;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.045em;
}

.lims-quotes-table :deep(.el-table__cell) {
    padding: 15px 0;
}

.lims-quotes-table :deep(.el-table__row) {
    transition: background-color 0.2s ease;
}

.lims-quotes-table :deep(.el-table__row:hover > td) {
    background-color: #f0fdfa !important;
}

.lims-quotes-table :deep(.el-table--striped .el-table__body tr.el-table__row--striped td) {
    background-color: #fbfdff;
}

.lims-quotes-table :deep(.el-table__fixed-right) {
    box-shadow: -10px 0 22px rgba(15, 23, 42, 0.07);
}

.lims-quotes-table :deep(.el-button.is-circle) {
    width: 32px;
    height: 32px;
    border-radius: 11px;
}

.lims-quotes-table :deep(.el-loading-mask) {
    border-radius: 16px;
}

.lims-quotes-table :deep(.cell) {
    line-height: 1.35;
}
</style>
