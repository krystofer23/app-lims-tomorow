<template>
    <section class="min-h-screen bg-slate-100 p-4 md:p-8">
        <div class="mx-auto max-w-6xl rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">

            <!-- HEADER -->
            <div class="grid grid-cols-1 md:grid-cols-12 border-b border-slate-300">
                <div class="md:col-span-3 min-h-24 border-r border-slate-300 flex items-center justify-center p-4">
                    <span class="text-slate-400 text-sm">Logo</span>
                </div>

                <div class="md:col-span-6 bg-lime-500 flex items-center justify-center p-6 border-r border-slate-300">
                    <h1 class="text-xl md:text-2xl font-bold text-black text-center">
                        INFORME DE MANTENIMIENTO
                    </h1>
                </div>

                <div class="md:col-span-3 p-4 text-sm text-slate-700 space-y-2">
                    <p><strong>Identificación:</strong> F-PM-01-1</p>
                    <p><strong>Revisión:</strong> 04</p>
                    <p><strong>Inicio de Vigencia:</strong> 2025-09-01</p>
                </div>
            </div>

            <el-form ref="formRef" :model="form" label-position="top" class="p-5 md:p-8 space-y-6">
                <!-- TIPO DE MANTENIMIENTO -->
                <div class="rounded-xl border border-slate-300 overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <label v-for="item in tiposMantenimiento" :key="item.value"
                            class="cursor-pointer border-b md:border-b-0 md:border-r last:border-r-0 border-slate-300">
                            <input v-model="form.tipoMantenimiento" type="radio" :value="item.value" class="hidden" />

                            <div class="h-full p-4 text-center font-medium transition" :class="form.tipoMantenimiento === item.value
                                ? 'bg-lime-500 text-black'
                                : 'bg-white hover:bg-lime-50 text-slate-700'">
                                {{ item.label }}
                            </div>
                        </label>
                    </div>
                </div>

                <!-- DATOS INICIALES -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <el-form-item label="Informe N°">
                        <el-input v-model="form.informeNumero" placeholder="Ingrese número de informe" />
                    </el-form-item>

                    <el-form-item label="Fecha de Ingreso">
                        <el-date-picker v-model="form.fechaIngresoPrincipal" type="date" placeholder="Seleccione fecha"
                            class="!w-full" format="DD/MM/YYYY" value-format="YYYY-MM-DD" />
                    </el-form-item>

                    <el-form-item label="Fecha de Emisión">
                        <el-date-picker v-model="form.fechaEmision" type="date" placeholder="Seleccione fecha"
                            class="!w-full" format="DD/MM/YYYY" value-format="YYYY-MM-DD" />
                    </el-form-item>
                </div>

                <!-- 1. DATOS GENERALES -->
                <FormSection title="1. Datos Generales">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <el-form-item label="Cliente">
                            <el-input v-model="form.cliente" placeholder="Nombre del cliente" />
                        </el-form-item>

                        <el-form-item label="Dirección">
                            <el-input v-model="form.direccion" placeholder="Dirección del cliente" />
                        </el-form-item>

                        <el-form-item label="Fecha de Ingreso">
                            <el-date-picker v-model="form.fechaIngresoDatos" type="date" placeholder="Seleccione fecha"
                                class="!w-full" format="DD/MM/YYYY" value-format="YYYY-MM-DD" />
                        </el-form-item>
                    </div>
                </FormSection>

                <!-- 2. EQUIPO -->
                <FormSection title="2. Equipo">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <el-form-item label="Instrumento de Medición">
                            <el-input v-model="form.instrumentoMedicion" />
                        </el-form-item>

                        <el-form-item label="Marca / Fabricante">
                            <el-input v-model="form.marcaFabricante" />
                        </el-form-item>

                        <el-form-item label="Modelo">
                            <el-input v-model="form.modelo" />
                        </el-form-item>

                        <el-form-item label="N° serie">
                            <el-input v-model="form.numeroSerie" />
                        </el-form-item>

                        <el-form-item label="Código">
                            <el-input v-model="form.codigo" />
                        </el-form-item>

                        <el-form-item label="Clase o Tipo">
                            <el-input v-model="form.claseTipo" />
                        </el-form-item>

                        <el-form-item label="Voltaje">
                            <el-input v-model="form.voltaje" />
                        </el-form-item>
                    </div>
                </FormSection>

                <!-- 3. LUGAR -->
                <FormSection title="3. Lugar de Mantenimiento y/o Diagnóstico">
                    <el-form-item label="Lugar">
                        <el-input v-model="form.lugarMantenimiento" type="textarea" :rows="3"
                            placeholder="Ingrese el lugar donde se realizó el mantenimiento o diagnóstico" />
                    </el-form-item>
                </FormSection>

                <!-- 4. FECHA MANTENIMIENTO -->
                <FormSection title="4. Fecha de Mantenimiento y/o Diagnóstico">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <el-form-item label="Fecha de Inicio">
                            <el-date-picker v-model="form.fechaMantenimientoInicio" type="date" class="!w-full"
                                format="DD/MM/YYYY" value-format="YYYY-MM-DD" />
                        </el-form-item>

                        <el-form-item label="Fecha de Fin">
                            <el-date-picker v-model="form.fechaMantenimientoFin" type="date" class="!w-full"
                                format="DD/MM/YYYY" value-format="YYYY-MM-DD" />
                        </el-form-item>
                    </div>
                </FormSection>

                <!-- 5. DESCRIPCIÓN -->
                <FormSection title="5. Descripción del Mantenimiento y/o Diagnóstico">
                    <el-form-item label="Descripción">
                        <el-input v-model="form.descripcion" type="textarea" :rows="4"
                            placeholder="Detalle el mantenimiento o diagnóstico realizado" />
                    </el-form-item>
                </FormSection>

                <!-- 5.1 -->
                <FormSection title="5.1. Tipo de intervención (modificación o reparación)">
                    <el-form-item label="Tipo de intervención">
                        <el-input v-model="form.tipoIntervencion" type="textarea" :rows="3"
                            placeholder="Describa la modificación o reparación realizada" />
                    </el-form-item>
                </FormSection>

                <!-- 5.2 -->
                <FormSection title="5.2. Repuestos usados (en caso aplique)">
                    <el-form-item label="Repuestos usados">
                        <el-input v-model="form.repuestosUsados" type="textarea" :rows="3"
                            placeholder="Detalle los repuestos usados" />
                    </el-form-item>
                </FormSection>

                <!-- 5.3 -->
                <FormSection title="5.3. Pruebas realizadas después de la reparación o modificación">
                    <el-form-item label="Pruebas realizadas">
                        <el-input v-model="form.pruebasRealizadas" type="textarea" :rows="3"
                            placeholder="Detalle las pruebas realizadas" />
                    </el-form-item>
                </FormSection>

                <!-- 6. OBSERVACIONES -->
                <FormSection title="6. Observaciones y Recomendaciones">
                    <el-form-item label="Observaciones y recomendaciones">
                        <el-input v-model="form.observaciones" type="textarea" :rows="4"
                            placeholder="Ingrese observaciones y recomendaciones" />
                    </el-form-item>
                </FormSection>

                <!-- 7. IMÁGENES -->
                <FormSection title="7. Imágenes">
                    <el-upload v-model:file-list="form.imagenes" drag multiple list-type="picture-card"
                        :auto-upload="false" accept="image/*">
                        <div class="text-slate-500 text-sm">
                            Arrastre imágenes o haga clic para seleccionar
                        </div>
                    </el-upload>
                </FormSection>

                <!-- 8. CONCLUSIÓN -->
                <FormSection title="8. Conclusión">
                    <el-form-item label="Conclusión">
                        <el-input v-model="form.conclusion" type="textarea" :rows="4"
                            placeholder="Ingrese la conclusión del mantenimiento" />
                    </el-form-item>
                </FormSection>

                <!-- 9. RESPONSABLE -->
                <FormSection title="9. Responsable técnico">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <el-form-item label="Responsable técnico">
                            <el-input v-model="form.responsableTecnico" placeholder="Nombre del responsable" />
                        </el-form-item>

                        <el-form-item label="Cargo">
                            <el-input v-model="form.cargoResponsable" placeholder="Cargo del responsable" />
                        </el-form-item>
                    </div>

                    <div class="mt-8 flex flex-col items-center">
                        <div class="w-72 border-t border-slate-500"></div>
                        <p class="text-sm text-slate-600 mt-2">Firma del responsable técnico</p>
                    </div>
                </FormSection>

                <!-- BOTONES -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-slate-200">
                    <el-button @click="limpiarFormulario">
                        Limpiar
                    </el-button>

                    <el-button type="primary" @click="guardarFormulario">
                        Guardar informe
                    </el-button>
                </div>
            </el-form>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'

const formRef = ref(null)

const tiposMantenimiento = [
    {
        label: 'Mantenimiento Correctivo',
        value: 'correctivo',
    },
    {
        label: 'Mantenimiento Preventivo',
        value: 'preventivo',
    },
    {
        label: 'Diagnóstico',
        value: 'diagnostico',
    },
]

const initialForm = () => ({
    tipoMantenimiento: '',

    informeNumero: '',
    fechaIngresoPrincipal: '',
    fechaEmision: '',

    cliente: '',
    direccion: '',
    fechaIngresoDatos: '',

    instrumentoMedicion: '',
    marcaFabricante: '',
    modelo: '',
    numeroSerie: '',
    codigo: '',
    claseTipo: '',
    voltaje: '',

    lugarMantenimiento: '',

    fechaMantenimientoInicio: '',
    fechaMantenimientoFin: '',

    descripcion: '',
    tipoIntervencion: '',
    repuestosUsados: '',
    pruebasRealizadas: '',
    observaciones: '',

    imagenes: [],

    conclusion: '',

    responsableTecnico: '',
    cargoResponsable: '',
})

const form = ref(initialForm())

const limpiarFormulario = () => {
    form.value = initialForm()
}

const guardarFormulario = () => {
    console.log('Formulario enviado:', form.value)

    // Aquí puedes llamar a tu API Laravel:
    // await axios.post('/api/informes-mantenimiento', form.value)
}
</script>
