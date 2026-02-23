<template>
    <main class="mx-auto max-w-7xl p-6 space-y-5">
        <header class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Tenants</h1>
                <p class="text-sm text-slate-600">Crea subdominio + tenant para empezar a trabajar.</p>
            </div>

            <el-button type="primary" class="!rounded-2xl" @click="openCreate">
                Crear tenant
            </el-button>
        </header>

        <section class="rounded-3xl border border-slate-200 bg-white p-4">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <el-input v-model="q" placeholder="Buscar por ID o nombre..." class="!w-full md:!w-96"
                    @keyup.enter="fetchTenants" clearable />
                <div class="flex gap-2">
                    <el-button @click="fetchTenants" :loading="loading">Buscar</el-button>
                    <el-button @click="reset">Limpiar</el-button>
                </div>
            </div>

            <div class="mt-4">
                <el-table :data="rows" v-loading="loading" class="!rounded-2xl overflow-hidden">
                    <el-table-column prop="id" label="Tenant ID" min-width="220" />
                    <el-table-column label="Nombre" min-width="220">
                        <template #default="{ row }">
                            <div class="font-semibold text-slate-900">{{ row?.data?.name || '—' }}</div>
                            <div class="text-xs text-slate-500">{{ row?.data?.subdomain || '—' }}</div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Dominios" min-width="260">
                        <template #default="{ row }">
                            <div class="space-y-1">
                                <div v-for="d in (row.domains || [])" :key="d.id"
                                    class="inline-flex items-center rounded-xl bg-slate-100 px-2 py-1 text-xs text-slate-700">
                                    {{ d.domain }}
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Acciones" width="160" align="right">
                        <template #default="{ row }">
                            <el-popconfirm title="¿Eliminar tenant?" @confirm="destroy(row.id)">
                                <template #reference>
                                    <el-button type="danger" plain class="!rounded-2xl">Eliminar</el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>

                <div class="mt-4 flex justify-end">
                    <el-pagination background layout="prev, pager, next" :total="total" :page-size="perPage"
                        :current-page="page" @current-change="(p) => { page = p; fetchTenants(); }" />
                </div>
            </div>
        </section>

        <!-- MODAL CREAR -->
        <el-dialog v-model="createModal" title="Crear tenant" width="520px" class="!rounded-2xl">
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-700">Nombre</label>
                        <el-input v-model="form.name" placeholder="Ej: GreenLab - Cliente X" />
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700">Subdominio</label>
                        <el-input v-model="form.subdomain" placeholder="ej: cliente-x" />
                        <p class="text-xs text-slate-500 mt-1">Se creará: <b>{{ form.subdomain || '...' }}</b>.{{
                            baseDomain }}
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700">Tenant ID (opcional)</label>
                        <el-input v-model="form.tenant_id" placeholder="Si lo dejas vacío se genera UUID" />
                    </div>
                </div>

                <el-alert v-if="errorMsg" type="error" :closable="false" :title="errorMsg" />
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <el-button class="!rounded-2xl" @click="createModal = false">Cancelar</el-button>
                    <el-button type="primary" class="!rounded-2xl" :loading="saving" @click="create">
                        Crear
                    </el-button>
                </div>
            </template>
        </el-dialog>
    </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const baseDomain = import.meta.env.VITE_BASE_DOMAIN || 'tudominio.com'

const q = ref('')
const loading = ref(false)
const rows = ref([])
const total = ref(0)
const perPage = ref(15)
const page = ref(1)

const createModal = ref(false)
const saving = ref(false)
const errorMsg = ref('')

const form = ref({ name: '', subdomain: '', tenant_id: '' })

function openCreate() {
    errorMsg.value = ''
    form.value = { name: '', subdomain: '', tenant_id: '' }
    createModal.value = true
}

async function fetchTenants() {
    loading.value = true
    try {
        const { data } = await axios.get('/api/central/tenants', {
            params: { q: q.value, per_page: perPage.value, page: page.value }
        })
        rows.value = data.data
        total.value = data.total
        perPage.value = data.per_page
        page.value = data.current_page
    } finally {
        loading.value = false
    }
}

function reset() {
    q.value = ''
    page.value = 1
    fetchTenants()
}

async function create() {
    errorMsg.value = ''
    saving.value = true
    
    try {
        await axios.post('/api/central/tenants', form.value)
        createModal.value = false
        page.value = 1
        await fetchTenants()
    } catch (e) {
        errorMsg.value = e?.response?.data?.message || 'Error creando tenant'
    } finally {
        saving.value = false
    }
}

async function destroy(id) {
    loading.value = true
    try {
        await axios.delete(`/api/central/tenants/${id}`)
        await fetchTenants()
    } finally {
        loading.value = false
    }
}

fetchTenants()
</script>
