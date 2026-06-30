<template>
    <el-dialog :model-value="visible" @close="handleClose">

    </el-dialog>
</template>

<script setup>
import { handleErrorsExeption } from '../../../../stores/handleErrorsExeption';

const props = defineProps({
    visible: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits(['close'])

const handleClose = async () => {
    emits('close')
}
</script>
