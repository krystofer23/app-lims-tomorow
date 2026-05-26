@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td></td>
        <td colspan="8" style="text-align:center; font-weight:bold; font-size:16px; background-color:#92d050;">
            ORDEN DE SERVICIO N°
        </td>
        <td>{{ $orderService?->code }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            DOCUMENTO REFERENCIA PARA EL SERVICIO
        </td>
    </tr>

    <tr>
        <td colspan="2">Numero de Cotización</td>
        <td colspan="8">#{{ $orderService?->quote?->id }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            DATOS DEL CLIENTE
        </td>
    </tr>

    <tr>
        <td colspan="2">RAZÓN SOCIAL</td>
        <td colspan="8">{{ $orderService?->company?->business_name }}</td>
    </tr>

    <tr>
        <td colspan="2">RUC</td>
        <td colspan="8">{{ $orderService?->company?->ruc }}</td>
    </tr>

    <tr>
        <td colspan="2">DIRECCION</td>
        <td colspan="8">{{ $orderService?->direction }}</td>
    </tr>

    <tr>
        <td colspan="2">CONTACTO</td>
        <td colspan="8">{{ $orderService?->contactCompany?->user?->name }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            DATOS DEL MONITOREO
        </td>
    </tr>

    <tr>
        <td colspan="2">NOMBRE DEL CLIENTE</td>
        <td colspan="8">{{ $orderService?->application?->business_name }}</td>
    </tr>

    <tr>
        <td colspan="2">NOMBRE DE CONTACTO EN CAMPO</td>
        <td colspan="4">{{ $orderService?->contactApplication?->user?->name }}</td>
        <td colspan="2">CELULAR</td>
        <td colspan="2">{{ $orderService?->contactApplication?->phone }}</td>
    </tr>

    <tr>
        <td colspan="2">DEPARTAMENTO</td>
        <td colspan="2">{{ $orderService?->department }}</td>
        <td>PROVINCIA</td>
        <td colspan="2">{{ $orderService?->province }}</td>
        <td>DISTRITO</td>
        <td colspan="2">{{ $orderService?->district }}</td>
    </tr>

    <tr>
        <td colspan="2">REF. SOBRE LA UBICACIÓN</td>
        <td colspan="8">{{ $orderService?->reference }}</td>
    </tr>

    <tr>
        <td colspan="2">PROCEDENCIA</td>
        <td colspan="8">{{ $orderService?->origin }}</td>
    </tr>

    <tr>
        <td colspan="2">PROYECTO</td>
        <td colspan="8">{{ $orderService?->project }}</td>
    </tr>

    <tr>
        <td colspan="2">FECHA INICIO DEL SERVICIO</td>
        <td colspan="8">{{ $orderService?->date_init_service }}</td>
    </tr>

    <tr>
        <td colspan="2">FECHA FIN DEL MONITOREO</td>
        <td colspan="8">{{ $orderService?->date_end_monitoring }}</td>
    </tr>

    <tr>
        <td colspan="2">PERSONAL PROGRAMADO</td>
        <td colspan="8"></td>
    </tr>

    <tr>
        <td colspan="2">ESPECIFICAR (DETALLES)</td>
        <td colspan="8">{{ $orderService?->details }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            DATOS DE INFORMACIÓN SOBRE LOS PUNTOS DE MONITOREO (aumentar las filas según sea necesario)
        </td>
    </tr>

    <tr>
        <td colspan="2">ESTACIONES DE MONITOREO</td>
        <td colspan="8">{{ $orderService?->monitoring }}</td>
    </tr>

    <tr>
        <td colspan="2">PROYECTO</td>
        <td colspan="8">{{ $orderService?->projects }}</td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>

    @foreach ($groupedMatrices as $group)
        <tr>
            <td colspan="10" style="font-weight:bold; background-color:#92d050;">
                MATRIZ: {{ strtoupper($group['description']) }}
                @if (!empty($group['frequency_label']))
                    [{{ $group['frequency_label'] }}]
                @endif
            </td>
        </tr>

        <tr>
            <td style="font-weight:bold; background-color:#ebf1de;">Ítem</td>
            <td style="font-weight:bold; background-color:#ebf1de;">ENSAYO/ANÁLISIS</td>
            <td colspan="2" style="font-weight:bold; background-color:#ebf1de;">METODOLOGÍA</td>
            <td style="font-weight:bold; background-color:#ebf1de;">LCM</td>
            <td style="font-weight:bold; background-color:#ebf1de;">UNIDAD</td>
            <td style="font-weight:bold; background-color:#ebf1de;">N° MUESTRAS</td>
            <td style="font-weight:bold; background-color:#ebf1de;">SUBCONTRATA (Cuando aplique)</td>
            <td style="font-weight:bold; background-color:#ebf1de;">EQUIPOS (Cantidad)</td>
            <td style="font-weight:bold; background-color:#ebf1de;">OBSERVACIONES</td>
        </tr>

        @foreach ($group['items'] as $matriz)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $matriz->essay_description_resolved ?? '-' }}</td>
                <td colspan="2">{{ $matriz->methodology_resolved ?? '-' }}</td>
                <td>{{ $matriz->lcm_resolved ?? '-' }}</td>
                <td>{{ $matriz->unit_resolved ?? '-' }}</td>
                <td>{{ $matriz->samples_resolved ?? '-' }}</td>
                <td>-</td>
                <td>-</td>
                <td>{{ $matriz->subcontract_resolved ?? 'NO APLICA' }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="10"></td>
        </tr>
    @endforeach

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            CONDICIONES DEL SERVICIO
        </td>
    </tr>

    <tr>
        <td colspan="2">Servicio incluye</td>
        <td colspan="8">{{ $orderService?->service_includes }}</td>
    </tr>

    <tr>
        <td colspan="2">Hospedaje</td>
        <td colspan="8">{{ $orderService?->accommodation }}</td>
    </tr>

    <tr>
        <td colspan="2">Viaticos</td>
        <td colspan="8">{{ $orderService?->travel_expenses }}</td>
    </tr>

    <tr>
        <td colspan="2">Dias de Servicio</td>
        <td colspan="8">{{ $orderService?->days_service }}</td>
    </tr>

    <tr>
        <td colspan="2">Trasporte de persona</td>
        <td colspan="8">{{ $orderService?->personal_transport }}</td>
    </tr>

    <tr>
        <td colspan="2">Envio de Muestra</td>
        <td colspan="8">{{ $orderService?->send_sampling }}</td>
    </tr>

    <tr>
        <td colspan="2">Vigilancia</td>
        <td colspan="8">{{ $orderService?->surveillance }}</td>
    </tr>

    <tr>
        <td colspan="2">Generador de Electrico</td>
        <td colspan="8">{{ $orderService?->electric_generator }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            DATOS PARA LA EMISION INFORME
        </td>
    </tr>

    <tr>
        <td colspan="2">RAZÓN SOCIAL</td>
        <td colspan="8">{{ $orderService?->companyEmission?->business_name }}</td>
    </tr>

    <tr>
        <td colspan="2">DIRECCION</td>
        <td colspan="8">{{ $orderService?->companyEmission?->direction }}</td>
    </tr>

    <tr>
        <td colspan="2">TIPO DE DOCUMENTO SOLICITADO</td>
        <td colspan="8">{{ $orderService?->type_document_required }}</td>
    </tr>

    <tr>
        <td colspan="2">NÚMERO DE COPIAS IMPRESAS</td>
        <td colspan="8">{{ $orderService?->number_copy }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            Observaciones:
        </td>
    </tr>

    <tr>
        <td colspan="10">{{ $orderService->observations ?? '-' }}</td>
    </tr>

    <tr>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">
            Realizado por:
        </td>
        <td colspan="3">{{ $orderService->user?->full_name ?? '-' }}</td>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">
            Código cotización:
        </td>
        <td colspan="3">#{{ $orderService->quote_id ?? '-' }}</td>
    </tr>
</table>
