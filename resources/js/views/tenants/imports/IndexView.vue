<template>
    <div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">
            Importar clientes desde Excel
        </h2>

        <input type="file" accept=".xlsx,.xls,.csv" @change="handleFile"
            class="block w-full border rounded-lg p-2 mb-4" />

        <button @click="importarExcel" :disabled="loading"
            class="px-4 py-2 rounded-lg bg-blue-600 text-white disabled:opacity-50">
            {{ loading ? 'Importando...' : 'Importar Excel' }}
        </button>

        <div v-if="result" class="mt-5">
            <p :class="result.success ? 'text-green-600' : 'text-red-600'" class="font-semibold">
                {{ result.message }}
            </p>

            <p v-if="result.importados !== undefined" class="mt-2">
                Registros importados: {{ result.importados }}
            </p>

            <div v-if="result.errores?.length" class="mt-4">
                <h3 class="font-bold text-red-600 mb-2">
                    Errores encontrados:
                </h3>

                <ul class="space-y-2">
                    <li v-for="error in result.errores" :key="error.fila"
                        class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <strong>Fila {{ error.fila }}:</strong>
                        <ul class="list-disc ml-5">
                            <li v-for="msg in error.errores" :key="msg">
                                {{ msg }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import tenant from '../../../stores/tenant';

const file = ref(null)
const loading = ref(false)
const result = ref(null)

const handleFile = (event) => {
    file.value = event.target.files[0]
}

const importarExcel = async () => {
    if (!file.value) {
        alert('Selecciona un archivo Excel')
        return
    }

    const formData = new FormData()
    formData.append('file', file.value)

    loading.value = true
    result.value = null

    try {
        const response = await tenant.post('/import', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        result.value = response.data
    } catch (error) {
        result.value = {
            success: false,
            message: error.response?.data?.message || 'Error al importar'
        }
    } finally {
        loading.value = false
    }
}
</script>
