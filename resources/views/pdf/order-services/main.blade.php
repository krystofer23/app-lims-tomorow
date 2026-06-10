@php
    use Carbon\Carbon;

    $val = function ($value, $default = '-') {
        if ($value === null) return $default;
        if (is_string($value) && trim($value) === '') return $default;
        return $value;
    };

    $dateFormat = function ($date, $default = '-') {
        if (!$date) return $default;

        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Throwable $e) {
            return $default;
        }
    };

    $upper = function ($value, $default = '-') use ($val) {
        return mb_strtoupper($val($value, $default), 'UTF-8');
    };

    $orderCode = $orderService?->code ?? $orderService?->id ?? '-';

    $quoteCode =
        $orderService?->quote?->code
        ?? $orderService?->quote?->correlative
        ?? $orderService?->quote?->id
        ?? $orderService?->quote_id
        ?? '-';

    $fecha =
        $orderService?->date
        ?? $orderService?->created_at
        ?? null;

    $clientName =
        $orderService?->application?->business_name
        ?? $orderService?->client_name
        ?? 'POR DEFINIR EN CAMPO';

    $contactFieldName =
        $orderService?->contactApplication?->user?->name
        ?? $orderService?->contactCompany?->user?->name
        ?? '-';

    $contactPhone =
        $orderService?->contactApplication?->phone
        ?? $orderService?->contactCompany?->phone
        ?? '-';

    $emissionCompany =
        $orderService?->companyEmission?->business_name
        ?? $orderService?->company?->business_name
        ?? '-';

    $emissionAddress =
        $orderService?->companyEmission?->direction
        ?? $orderService?->company?->direction
        ?? $orderService?->direction
        ?? '-';

    $elaboradoPor =
        $orderService?->user?->full_name
        ?? $orderService?->user?->name
        ?? '-';

    $aprobadoPor =
        $orderService?->reviewed?->full_name
        ?? $orderService?->reviewed?->name
        ?? $orderService?->reviwed?->full_name
        ?? $orderService?->reviwed?->name
        ?? '-';
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Servicio {{ $orderCode }}</title>

    <style>
        @page {
            margin: 18px 16px 18px 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .meta {
            width: 100%;
            font-size: 5.8px;
            text-align: right;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            /* border: 1px solid #000; */
            padding: 2px 3px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.15;
        }

        .border {
            border: 1px solid #000 !important;
        }

        .main-table {
            border: 2px solid #000;
        }

        .no-border {
            border: none !important;
        }

        .green {
            background: #92d050;
            font-weight: bold;
        }

        .green-dark {
            background: #8cc63f;
            font-weight: bold;
        }

        .soft {
            background: #d9ead3;
            font-weight: bold;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 6px;
        }

        .tiny {
            font-size: 5.5px;
        }

        .logo-box {
            height: 42px;
            text-align: left;
            vertical-align: middle;
            background: #fff;
        }

        .logo-text {
            font-size: 15px;
            font-weight: bold;
            color: #4caf30;
            line-height: 1;
        }

        .logo-sub {
            font-size: 10px;
            color: #4caf30;
            font-weight: bold;
            line-height: 1;
        }

        .title {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            background: #92d050;
        }

        .os-code {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            background: #92d050;
        }

        .section {
            background: #92d050;
            font-weight: bold;
            font-size: 6.5px;
            padding: 2px 3px;
        }

        .label {
            font-weight: bold;
            width: 22%;
        }

        .colon {
            width: 2%;
            text-align: center;
            font-weight: bold;
        }

        .value {
            font-weight: normal;
        }

        .matrix-title {
            background: #92d050;
            font-weight: bold;
            font-size: 7px;
        }

        .table-head {
            background: #d9ead3;
            font-weight: bold;
            text-align: center;
            font-size: 5.8px;
        }

        .analysis-row td {
            height: 38px;
            font-size: 5.8px;
        }

        .conditions td,
        .emission td {
            height: 12px;
        }

        .observations-box {
            height: 28px;
            vertical-align: top;
        }

        .sign-row td {
            height: 13px;
            text-align: center;
            font-size: 6px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="meta">
        <div>Identificación: F-PV-01-6</div>
        <div>Revisión: 03</div>
        <div>Inicio de vigencia: 2024-09-30</div>
    </div>

    <table class="main-table">
        <tr>
            <td colspan="2" class="logo-box border">
                <img src="{{ storage_path('app/public/logos/logo.jpg') }}" style="width: 105px; height: 45px;">
            </td>

            <td colspan="6" class="title">
                ORDEN DE SERVICIO N°
            </td>

            <td colspan="3" class="os-code">
                OS - {{ $orderCode }}
            </td>
        </tr>

        <tr>
            <td colspan="11" class="section border">DOCUMENTO REFERENCIA PARA EL SERVICIO</td>
        </tr>

        <tr>
            <td colspan="2" class="bold">Numero de Cotización</td>
            <td class="colon">:</td>
            <td colspan="4" class="bold">
                N° {{ $val($quoteCode) }}
            </td>

            <td class="bold center">FECHA</td>
            <td class="colon">:</td>
            <td colspan="2" class="bold center">
                {{ $dateFormat($fecha) }}
            </td>
        </tr>

        {{-- DATOS CLIENTE --}}
        <tr>
            <td colspan="11" class="section border">DATOS DEL CLIENTE</td>
        </tr>

        <tr>
            <td colspan="2" class="bold">RAZÓN SOCIAL</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->company?->business_name) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">RUC</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $val($orderService?->company?->ruc) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">DIRECCION</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->direction ?? $orderService?->company?->direction) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">CONTACTO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->contactCompany?->user?->name) }}
            </td>
        </tr>

        {{-- DATOS MONITOREO --}}
        <tr>
            <td colspan="11" class="section border">DATOS DEL MONITOREO</td>
        </tr>

        <tr>
            <td colspan="2" class="bold">NOMBRE DEL CLIENTE</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($clientName) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">NOMBRE DE CONTACTO EN CAMPO</td>
            <td class="colon">:</td>
            <td colspan="4">
                {{ $upper($contactFieldName) }}
            </td>

            <td class="bold center">CELULAR</td>
            <td class="colon">:</td>
            <td colspan="2">
                {{ $val($contactPhone) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">DEPARTAMENTO</td>
            <td class="colon">:</td>
            <td colspan="2">
                {{ $upper($orderService?->department) }}
            </td>

            <td class="bold center">PROVINCIA</td>
            <td class="colon">:</td>
            <td>
                {{ $upper($orderService?->province) }}
            </td>

            <td class="bold center">DISTRITO</td>
            <td class="colon">:</td>
            <td>
                {{ $upper($orderService?->district) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">REF. SOBRE LA UBICACIÓN</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->reference) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">PROCEDENCIA</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->origin) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">PROYECTO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->project) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">FECHA INICIO DEL SERVICIO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $dateFormat($orderService?->date_init_service) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">FECHA FIN DEL MONITOREO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $dateFormat($orderService?->date_end_monitoring) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">PERSONAL PROGRAMADO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $val($orderService?->scheduled_staff ?? $orderService?->personal_programmed) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">ESPECIFICAR (DETALLES)</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->details) }}
            </td>
        </tr>

        {{-- PUNTOS MONITOREO --}}
        <tr>
            <td colspan="11" class="section border">
                DATOS DE INFORMACIÓN SOBRE LOS PUNTOS DE MONITOREO (aumentar las filas según sea necesario)
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">ESTACIONES DE MONITOREO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->monitoring ?? $orderService?->monitoring_stations) }}
            </td>
        </tr>

        <tr>
            <td colspan="2" class="bold">PROYECTO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->projects ?? $orderService?->project) }}
            </td>
        </tr>

        {{-- MATRICES --}}
        @forelse ($groupedMatrices ?? [] as $group)
            <tr>
                <td colspan="11" class="matrix-title border">
                    MATRIZ: {{ $upper(data_get($group, 'description', 'Sin matriz')) }}
                    @if (!empty(data_get($group, 'frequency_label')))
                        {{ ' - ' . data_get($group, 'frequency_label') }}
                    @endif
                </td>
            </tr>

            <tr>
                <td class="border table-head" style="width: 5%;">Ítem</td>
                <td colspan="2" class="border table-head" style="width: 20%;">ENSAYO/ANÁLISIS</td>
                <td colspan="2" class="border table-head" style="width: 28%;">METODOLOGÍA</td>
                <td class="border table-head" style="width: 7%;">LCM</td>
                <td class="border table-head" style="width: 7%;">UNIDAD</td>
                <td class="border table-head" style="width: 7%;">N° MUESTRAS</td>
                <td class="border table-head" style="width: 9%;">SUBCONTRATA<br>(Cuando aplique)</td>
                <td class="border table-head" style="width: 9%;">EQUIPOS<br>(Cantidad)</td>
                <td class="border table-head" style="width: 8%;">OBSERVACIONES</td>
            </tr>

            <tr>
                {{-- <td class="table-head tiny"></td> --}}
                {{-- <td colspan="2" class="table-head tiny"></td> --}}
                <td colspan="11" class="table-head tiny border">ANÁLISIS</td>
                {{-- <td class="table-head tiny"></td>
                <td class="table-head tiny"></td>
                <td class="table-head tiny"></td>
                <td class="table-head tiny"></td>
                <td class="table-head tiny"></td>
                <td class="table-head tiny"></td> --}}
            </tr>

            @forelse (data_get($group, 'items', []) as $matriz)
                <tr class="analysis-row">
                    <td class="border center">
                        {{ $loop->iteration }}
                    </td>

                    <td colspan="2" class="border center">
                        {{ $val($matriz->essay_description_resolved ?? null) }}
                    </td>

                    <td colspan="2" class="border center tiny">
                        {{ $val($matriz->methodology_resolved ?? null) }}
                    </td>

                    <td class="center border">
                        {{ $val($matriz->lcm_resolved ?? null) }}
                    </td>

                    <td class="center border">
                        {{ $val($matriz->unit_resolved ?? null) }}
                    </td>

                    <td class="center border">
                        {{ $val($matriz->samples_resolved ?? null) }}
                    </td>

                    <td class="center border">
                        {{-- {{ $upper($matriz->subcontract_resolved ?? 'NO APLICA', 'NO APLICA') }} --}}
                        NO APLICA
                    </td>

                    <td class="center border">
                        {{ $upper($matriz->equipment_resolved ?? null) }}
                    </td>

                    <td class="center border">
                        {{ $upper($matriz->subcontract_resolved ?? '-', '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="center border">
                        No se registraron ensayos/análisis para esta matriz.
                    </td>
                </tr>
            @endforelse
        @empty
            <tr>
                <td colspan="11" class="matrix-title border">MATRIZ: SIN MATRIZ</td>
            </tr>
            <tr>
                <td colspan="11" class="center border">No se registraron matrices.</td>
            </tr>
        @endforelse

        <tr>
            <td colspan="11" class="section border">CONDICIONES DEL SERVICIO</td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Servicio incluye</td>
            <td class="colon">:</td>
            <td colspan="8" class="bold">
                {{ $upper($orderService?->service_includes) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Hospedaje</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->accommodation) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Viaticos</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->travel_expenses) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Dias de Servicio</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $val($orderService?->days_service) }}
                @if ($orderService?->days_service)
                    DÍA(S)
                @endif
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Trasporte de personal</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->personal_transport) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Envio de Muestra</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->send_sampling) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Vigilancia</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->surveillance) }}
            </td>
        </tr>

        <tr class="conditions">
            <td colspan="2" class="bold">Generador de Electrico</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->electric_generator, 'NO APLICA') }}
            </td>
        </tr>

        <tr>
            <td colspan="11" class="section border">DATOS PARA LA EMISION INFORME</td>
        </tr>

        <tr class="emission">
            <td colspan="2" class="bold">RAZÓN SOCIAL</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($emissionCompany) }}
            </td>
        </tr>

        <tr class="emission">
            <td colspan="2" class="bold">DIRECCION</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($emissionAddress) }}
            </td>
        </tr>

        <tr class="emission">
            <td colspan="2" class="bold">TIPO DE DOCUMENTO SOLICITADO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->type_document_required, 'INFORME DE ENSAYO') }}
            </td>
        </tr>

        <tr class="emission">
            <td colspan="2" class="bold">NÚMERO DE COPIAS IMPRESAS</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->number_copy) }}
            </td>
        </tr>

        <tr class="emission">
            <td colspan="2" class="bold">INFORME DE MONITOREO</td>
            <td class="colon">:</td>
            <td colspan="8">
                {{ $upper($orderService?->monitoring_report, 'NO APLICA') }}
            </td>
        </tr>

        <tr>
            <td colspan="11" class="section border">Observaciones:</td>
        </tr>

        <tr>
            <td colspan="11" class="observations-box border">
                {{ $val($orderService?->observations, '') }}
            </td>
        </tr>

        <tr class="sign-row">
            <td colspan="2" class="green bold">Elaborado por</td>
            <td class="green bold">:</td>
            <td colspan="4" class="border">
                {{ $upper($elaboradoPor) }}
            </td>

            <td colspan="2" class="green bold">Aprobado por</td>
            <td class="green bold">:</td>
            <td class="border">
                {{-- {{ $upper($aprobadoPor) }} --}}
                RONALD RAMIREZ
            </td>
        </tr>
    </table>

</body>

</html>
