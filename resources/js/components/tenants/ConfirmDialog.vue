<template>
    <el-dialog v-model="visible" @close="handleCancel" top="35vh" :title="undefined" :close-on-press-escape="!loading"
        :close-on-click-modal="!loading" :show-close="false" style="max-width: 420px; width: 90%;"
        class="!rounded-2xl [&_.el-dialog__header]:hidden">
        <div class="flex items-start gap-3 -mt-3">
            <div class="shrink-0 w-10 h-10 grid place-items-center rounded-xl" :class="iconWrapperClass">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" class="w-5 h-5">
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                    <path
                        d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
            </div>

            <div class="min-w-0">
                <h3 class="text-base font-semibold text-gray-900">{{ state.title }}</h3>
                <p class="mt-1 text-sm text-gray-600">{{ state.message }}</p>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-end">
                <el-button class="!rounded-xl" :disabled="loading" @click="handleCancel">
                    {{ state.cancelText }}
                </el-button>
                <el-button type="primary" class="!rounded-xl" :loading="loading" @click="handleConfirm">
                    {{ state.confirmText }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'

type DialogType = 'warning' | 'danger' | 'info'

type OpenOptions = {
    title?: string
    message?: string
    confirmText?: string
    cancelText?: string
    type?: DialogType
}

const props = defineProps({
    loading: { type: Boolean, default: false },
})

const visible = ref(false)

const state = reactive({
    title: '¿Estás seguro?',
    message: 'Esta acción no se puede deshacer.',
    confirmText: 'Confirmar',
    cancelText: 'Cancelar',
    type: 'warning' as DialogType,
})

let resolve: ((v: boolean) => void) | null = null

function open(options: OpenOptions = {}) {
    state.title = options.title ?? '¿Estás seguro?'
    state.message = options.message ?? 'Esta acción no se puede deshacer.'
    state.confirmText = options.confirmText ?? 'Confirmar'
    state.cancelText = options.cancelText ?? 'Cancelar'
    state.type = (options.type ?? 'warning') as DialogType

    visible.value = true

    return new Promise<boolean>((_resolve) => {
        resolve = _resolve
    })
}

function close() {
    visible.value = false
}

const iconWrapperClass = computed(() => {
    if (state.type === 'danger') return 'bg-red-50 text-red-600 ring-1 ring-red-200'
    if (state.type === 'info') return 'bg-blue-50 text-blue-600 ring-1 ring-blue-200'
    return 'bg-amber-50 text-amber-600 ring-1 ring-amber-200'
})

function handleCancel() {
    if (props.loading) return
    close()
    resolve?.(false)
    resolve = null
}

function handleConfirm() {
    if (props.loading) return
    close()
    resolve?.(true)
    resolve = null
}

defineExpose({ open, close })
</script>