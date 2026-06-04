<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Ensayo</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #111;
            background-size: cover;
            background-position: center;
            background-image: url("{{ public_path('preliminar.png') }}"); 
            background-repeat: no-repeat;
            background-position: center top;
        }

        .page {
            position: relative;
            /* width: 210mm;
            min-height: 297mm; */
            padding: 38mm 18mm 18mm 18mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .title {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 17px;
            font-weight: bold;
            line-height: 1.05;
            margin: 0;
            padding: 0;
            letter-spacing: .3px;
        }

        .subtitle {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 17px;
            font-weight: bold;
            line-height: 1.05;
            margin: 0 0 13mm 0;
            padding: 0;
            letter-spacing: .3px;
        }

        .page-number {
            position: absolute;
            right: 22mm;
            bottom: 14mm;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            vertical-align: top;
            word-wrap: break-word;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .general-table {
            margin-top: 6mm;
        }

        .general-table td {
            padding: 3.4mm 2mm 0 0;
            line-height: 1.2;
        }

        .general-label {
            width: 25%;
            font-family: DejaVu Serif, serif;
            font-size: 10px;
            font-weight: bold;
        }

        .general-colon {
            width: 3%;
            text-align: center;
            font-weight: bold;
        }

        .general-value {
            width: 72%;
            font-family: DejaVu Serif, serif;
            font-size: 10px;
            text-transform: uppercase;
        }

        .note {
            margin-top: 6mm;
            border-top: 1px solid #000;
            padding-top: 1mm;
            font-family: DejaVu Serif, serif;
            font-size: 8.2px;
            line-height: 1.25;
        }

        .authorized {
            margin-top: 3mm;
            font-family: DejaVu Serif, serif;
            font-size: 9px;
            font-weight: bold;
        }

        .section-title {
            font-family: DejaVu Serif, serif;
            font-size: 11px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 1.4mm 0;
            margin-top: 8mm;
            margin-bottom: 0;
            letter-spacing: .2px;
        }

        .result-info-table {
            margin-top: 2mm;
            font-family: DejaVu Serif, serif;
            font-size: 8.6px;
        }

        .result-info-table td {
            padding: .9mm .8mm;
            line-height: 1.1;
        }

        .result-label {
            width: 25%;
            font-weight: bold;
        }

        .result-colon {
            width: 3%;
            text-align: center;
            font-weight: bold;
        }

        .result-value {
            text-align: center;
        }

        .result-main-table {
            margin-top: 4mm;
            font-family: DejaVu Serif, serif;
            font-size: 8.5px;
        }

        .result-main-table th {
            padding: 1.4mm .8mm;
            text-align: center;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .result-main-table td {
            padding: 1mm .8mm;
            line-height: 1.15;
        }

        .group-row td {
            border-bottom: 1px solid #000;
            padding-top: 1.4mm;
            padding-bottom: 1mm;
            font-weight: bold;
        }

        .result-end-line td {
            border-bottom: 1px solid #000;
        }

        .legend {
            margin-top: 1.5mm;
            font-family: DejaVu Serif, serif;
            font-size: 7px;
            line-height: 1.18;
        }

        .methods-table {
            margin-top: 4mm;
            font-family: DejaVu Serif, serif;
            font-size: 9px;
        }

        .methods-table th {
            padding: 1.4mm 1mm;
            text-align: left;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            letter-spacing: .4px;
        }

        .methods-table td {
            padding: 1mm 1mm;
            line-height: 1.2;
        }

        .methods-bottom td {
            border-bottom: 1px solid #000;
            padding-bottom: 2mm;
        }

        .observations-title {
            margin-top: 10mm;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 1.4mm 0;
            font-family: DejaVu Serif, serif;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: .2px;
        }

        .observations-box {
            margin-top: 4mm;
            margin-left: 51mm;
            width: 118mm;
            font-family: DejaVu Serif, serif;
            font-size: 9px;
            line-height: 1.25;
        }

        .observations-line {
            border-bottom: 1px solid #000;
            margin-top: 3mm;
        }

        .final {
            margin-top: 6mm;
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>

@php
    $samples = $data['samples'] ?? [];
    $sampleCount = max(count($samples), 1);

    $reportNumber = $data['report_number']
        ?? $data['number_report']
        ?? $data['inform_number']
        ?? 'XXX-XX-I';

    $category = $data['category'] ?? $data['product'] ?? '-';
    $subCategory = $data['sub_category'] ?? $data['product'] ?? '-';

    $resultColumnWidth = 46 / $sampleCount;

    if (!function_exists('pdfParseCoordinate')) {
        function pdfParseCoordinate($coordinate) {
            $coordinate = trim((string) $coordinate);

            if ($coordinate === '' || $coordinate === '-') {
                return [
                    'east' => '-',
                    'north' => '-',
                ];
            }

            $east = '-';
            $north = '-';

            if (preg_match('/E\s*:\s*([^\r\n,;]+).*?N\s*:\s*([^\r\n,;]+)/is', $coordinate, $matches)) {
                $east = trim($matches[1] ?? '-');
                $north = trim($matches[2] ?? '-');
            } elseif (preg_match('/N\s*:\s*([^\r\n,;]+).*?E\s*:\s*([^\r\n,;]+)/is', $coordinate, $matches)) {
                $north = trim($matches[1] ?? '-');
                $east = trim($matches[2] ?? '-');
            } elseif (str_contains($coordinate, ',')) {
                $parts = array_map('trim', explode(',', $coordinate));
                $east = $parts[0] ?? '-';
                $north = $parts[1] ?? '-';
            } else {
                $east = $coordinate;
            }

            return [
                'east' => $east ?: '-',
                'north' => $north ?: '-',
            ];
        }
    }

    if (!function_exists('pdfResultByIndex')) {
        function pdfResultByIndex($parameter, $sampleIndex = 0) {
            $results = array_values($parameter['results'] ?? []);

            return $results[$sampleIndex]['result'] ?? '';
        }
    }

    $methodRows = [];

    foreach (($data['analysis_groups_methodology'] ?? []) as $group) {
        foreach (($group['parameters'] ?? []) as $parameter) {
            $methodRows[] = $parameter;
        }
    }
@endphp

{{-- PÁGINA 1 --}}
<div class="page">
    <p class="title">INFORME DE ENSAYO N° {{ $reportNumber }}</p>
    <p class="subtitle">CON VALOR OFICIAL</p>

    <table class="general-table">
        <tr>
            <td class="general-label">Razón Social del cliente</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['company'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Dirección</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['direction'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Solicitado Por</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['application'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Referencia</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['reference'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Proyecto ¤</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['project'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Procedencia ¤</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['origin'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Muestreo Realizado Por</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['sampling_performed_by'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Cantidad de Muestra</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['sample_quantity'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Producto</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['product'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Plan de Muestreo</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['sampling_plan'] ?? 'NO APLICA' }}</td>
        </tr>
        <tr>
            <td class="general-label">Fecha de Recepción</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['date_of_receipt'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Hora de Recepción</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['time_of_receipt'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Periodo de Ensayo</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['test_period'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="general-label">Fecha de Emisión</td>
            <td class="general-colon">:</td>
            <td class="general-value">{{ $data['date_of_issue'] ?? '' }}</td>
        </tr>
    </table>

    <div class="note">
        Gracias por utilizar los servicios de GREENLAB PERÚ S.A.C. Póngase en contacto con el Ejecutivo de Ventas,
        si desea información adicional o cualquier aclaración que pertenezcan a este informe.
    </div>

    <div class="authorized">
        Informe Autorizado por:
    </div>

    <div class="page-number">1 de 3</div>
</div>

{{-- PÁGINA 2 --}}
<div class="page">
    <p class="title">INFORME DE ENSAYO N° {{ $reportNumber }}</p>
    <p class="subtitle">CON VALOR OFICIAL</p>

    <div class="section-title">
        I. RESULTADOS DE ANÁLISIS
    </div>

    <table class="result-info-table">
        <tr>
            <td class="result-label">Código del Laboratorio</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value" style="width: {{ $resultColumnWidth }}%;">
                    {{ $sample['code_lab'] ?? '-' }}
                </td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Código de la muestra ¤</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value">{{ $sample['code_sample'] ?? '-' }}</td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Fecha muestreo ¤</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value">{{ $sample['date_sample'] ?? '-' }}</td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Hora muestreo ¤</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value">{{ $sample['hour_sample'] ?? '-' }}</td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Categoría ¤</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value">{{ $category }}</td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Sub categoría</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                <td class="result-value">{{ $subCategory }}</td>
            @empty
                <td class="result-value">-</td>
            @endforelse
        </tr>

        <tr>
            <td class="result-label">Coordenadas (WGS-84) ¤</td>
            <td class="result-colon">:</td>

            @forelse($samples as $sample)
                @php
                    $coord = pdfParseCoordinate($sample['coordinate'] ?? '-');
                @endphp

                <td class="result-value">
                    E: {{ $coord['east'] }}
                </td>
            @empty
                <td class="result-value">E: -</td>
            @endforelse
        </tr>

        <tr>
            <td></td>
            <td></td>

            @forelse($samples as $sample)
                @php
                    $coord = pdfParseCoordinate($sample['coordinate'] ?? '-');
                @endphp

                <td class="result-value">
                    N: {{ $coord['north'] }}
                </td>
            @empty
                <td class="result-value">N: -</td>
            @endforelse
        </tr>
    </table>

    <table class="result-main-table">
        <thead>
            <tr>
                <th style="width: 29%;">Parámetros</th>
                <th style="width: 13%;">Unidad</th>
                <th style="width: 12%;">L.C.M.</th>

                @forelse($samples as $index => $sample)
                    <th style="width: {{ $resultColumnWidth }}%;">
                        {{ $index === 0 ? 'Resultados' : '' }}
                    </th>
                @empty
                    <th style="width: 46%;">Resultados</th>
                @endforelse
            </tr>
        </thead>

        <tbody>
            @foreach(($data['analysis_groups'] ?? []) as $group)
                <tr class="group-row">
                    <td colspan="{{ 3 + $sampleCount }}">
                        {{ $group['type_of_analysis'] ?? 'SIN TIPO DE ENSAYO' }}
                    </td>
                </tr>

                @foreach(($group['parameters'] ?? []) as $parameter)
                    <tr>
                        <td>{{ $parameter['parameter'] ?? '-' }}</td>
                        <td class="center">{{ $parameter['unit'] ?? '-' }}</td>
                        <td class="center">{{ $parameter['lcm'] ?? '-' }}</td>

                        @forelse($samples as $sampleIndex => $sample)
                            <td class="center">
                                {{ pdfResultByIndex($parameter, $sampleIndex) }}
                            </td>
                        @empty
                            <td class="center"></td>
                        @endforelse
                    </tr>
                @endforeach
            @endforeach

            <tr class="result-end-line">
                <td colspan="{{ 3 + $sampleCount }}"></td>
            </tr>
        </tbody>
    </table>

    <div class="legend">
        Leyenda:<br>
        L.C.M: Límite de cuantificación del Método<br>
        "&lt;": Menor que el L.C.M. indicado<br>
        Las muestras recibidas cumplen con las condiciones necesarias para realización de los análisis solicitados.<br>
        ¤: Información proporcionada en la cadena por el cliente<br>
        Los parámetros indicados están expresados en mg/Kg PS<br>
        PS: Peso seco
    </div>

    <div class="page-number">2 de 3</div>
</div>

{{-- PÁGINA 3 --}}
<div class="page">
    <p class="title">INFORME DE ENSAYO N° {{ $reportNumber }}</p>
    <p class="subtitle">CON VALOR OFICIAL</p>

    <div class="section-title">
        II. MÉTODOS Y REFERENCIAS
    </div>

    <table class="methods-table">
        <thead>
            <tr>
                <th style="width: 25%;">TIPO ENSAYO</th>
                <th style="width: 31%;">NORMA REFERENCIA</th>
                <th style="width: 44%;">TITULO</th>
            </tr>
        </thead>

        <tbody>
            @forelse($methodRows as $method)
                <tr class="{{ $loop->last ? 'methods-bottom' : '' }}">
                    <td>{{ $method['parameter'] ?? '-' }}</td>
                    <td>{{ $method['code'] ?? '-' }}</td>
                    <td>{{ $method['title'] ?? '-' }}</td>
                </tr>
            @empty
                <tr class="methods-bottom">
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="observations-title">
        III. OBSERVACIONES
    </div>

    <div class="observations-box">
        - Los resultados presentados corresponden sólo a la muestra indicada, según la cadena de custodia correspondiente.<br>
        - El tiempo custodia de la muestra es de un mes calendario desde la toma de la muestra y dependiendo del parámetro a ser analizado.<br>
        - Descripción del punto de muestreo: {{ $data['sampling_point_description'] ?? '-' }}
    </div>

    <div class="observations-line"></div>

    <div class="final">
        ***FIN DEL INFORME***
    </div>

    <div class="page-number">3 de 3</div>
</div>

</body>
</html>