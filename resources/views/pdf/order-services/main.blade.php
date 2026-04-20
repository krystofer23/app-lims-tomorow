@php
use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de servicio {{ $orderService->code ?? $orderService->id }}</title>
    <style>
        @page { margin: 18px 18px 22px 18px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #000; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td, th { border: 1px solid #000; padding: 4px 5px; vertical-align: middle; word-wrap: break-word; }
        .title { text-align: center; font-weight: bold; font-size: 16px; background-color: #92d050; }
        .section-green { font-weight: bold; background-color: #92d050; }
        .label-green { font-weight: bold; background-color: #d9ead3; }
        .header-soft { background-color: #ebf1de; font-weight: bold; text-align: center; }
        .spacer td { border: none !important; height: 8px; padding: 0; }
        .merge-start { vertical-align: top; border-bottom: none !important; }
        .merge-mid { vertical-align: top; border-top: none !important; border-bottom: none !important; }
        .merge-end { vertical-align: top; border-top: none !important; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10" class="title">ORDEN DE SERVICIO</td>
        </tr>
        <tr>
            <td colspan="2" class="section-green">ORDEN Nº:</td>
            <td colspan="2">{{ $orderService->code ?? $orderService->id ?? '-' }}</td>
            <td colspan="2" class="section-green">Fecha de elaboración:</td>
            <td colspan="2">{{ Carbon::make($orderService->created_at)->format('Y-m-d') }}</td>
            <td class="section-green">Fecha de atención:</td>
            <td>{{ $orderService->date_attention ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="10" class="section-green">INFORMACIÓN DEL CLIENTE</td>
        </tr>
        <tr>
            <td class="label-green">Razón social:</td>
            <td colspan="9">{{ $company->business_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Dirección:</td>
            <td colspan="9">{{ $orderService->direction ?? $company->direction ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Contacto:</td>
            <td colspan="4">{{ $contact?->user?->full_name ?? '-' }}</td>
            <td class="label-green">Teléfono/Celular:</td>
            <td colspan="4">{{ $contact?->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Email:</td>
            <td colspan="6">{{ $contact?->email ?? '-' }}</td>
            <td class="label-green">R.U.C.:</td>
            <td colspan="2">{{ $company->ruc ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Referencia:</td>
            <td colspan="9">{{ $orderService->reference ?? '-' }}</td>
        </tr>

        <tr class="spacer"><td colspan="10"></td></tr>

        <tr>
            <td colspan="10" class="section-green">DATOS DE MONITOREO</td>
        </tr>
        <tr>
            <td class="label-green">Procedencia:</td>
            <td colspan="4">{{ $orderService->origin ?? '-' }}</td>
            <td class="label-green">Proyecto:</td>
            <td colspan="4">{{ $orderService->project ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Fecha de salida:</td>
            <td colspan="2">{{ $orderService->date_output ?? '-' }}</td>
            <td class="label-green">Fecha de inducción:</td>
            <td colspan="2">{{ $orderService->date_induction ?? '-' }}</td>
            <td class="label-green">Inicio monitoreo:</td>
            <td>{{ $orderService->date_monitoring_init ?? '-' }}</td>
            <td class="label-green">Fin monitoreo:</td>
            <td>{{ $orderService->date_monitoring_end ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Detalle:</td>
            <td colspan="9">{{ $orderService->details ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Estaciones:</td>
            <td colspan="9">{{ $orderService->stations_monitoring ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Proyecto monitoreo:</td>
            <td colspan="9">{{ $orderService->project_monitoring ?? '-' }}</td>
        </tr>

        <tr class="spacer"><td colspan="10"></td></tr>

        @foreach($groupedMatrices as $group)
            <tr>
                <td colspan="10" class="section-green">
                    MATRIZ: {{ strtoupper($group['description']) }}
                    @if(!empty($group['frequency_label']))
                        [{{ $group['frequency_label'] }}]
                    @endif
                </td>
            </tr>
            <tr>
                <td class="header-soft">Matriz</td>
                <td class="header-soft">Ensayo</td>
                <td colspan="2" class="header-soft">Metodología</td>
                <td class="header-soft">LCM</td>
                <td class="header-soft">Unidad</td>
                <td class="header-soft">N° Muestras</td>
                <td class="header-soft">Condición</td>
                <td class="header-soft">Frecuencia</td>
                <td class="header-soft">Observación</td>
            </tr>
            @foreach($group['items'] as $matriz)
                @php
                    $essays = data_get($matriz, 'item.essays', []);
                    $matrizDescription = data_get($matriz, 'item.description', '-');
                    $methodology = data_get($matriz, 'item.methodologie.description', '-');
                    $samples = data_get($matriz, 'item.number_samples', '-');
                    $condition = collect($essays)
                        ->pluck('condition.description')
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: '-';
                    $frequencyLabel = data_get($matriz, 'item.frequency_label', '-');
                    $observation = data_get($matriz, 'item.observation', '-');
                    $rowspan = max(count($essays), 1);
                    $essayTextLength = collect($essays)->sum(function ($essay) {
                        return strlen((string) data_get($essay, 'description', ''));
                    });
                    $contentLength = strlen((string) $matrizDescription)
                        + strlen((string) $methodology)
                        + strlen((string) $condition)
                        + strlen((string) $frequencyLabel)
                        + strlen((string) $observation)
                        + $essayTextLength;
                    $useRealRowspan = count($essays) <= 6 && $contentLength <= 220;
                @endphp
                @if(count($essays))
                    @if($useRealRowspan)
                        @foreach($essays as $index => $essay)
                            <tr>
                                @if($index === 0)
                                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $matrizDescription }}</td>
                                @endif
                                <td>{{ data_get($essay, 'description', '-') }}</td>
                                @if($index === 0)
                                    <td colspan="2" rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $methodology }}</td>
                                @endif
                                <td>{{ data_get($essay, 'lcm', '-') }}</td>
                                <td>{{ data_get($essay, 'units_measurement.description', '-') }}</td>
                                @if($index === 0)
                                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $samples }}</td>
                                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $condition }}</td>
                                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $frequencyLabel }}</td>
                                    <td rowspan="{{ $rowspan }}" style="vertical-align: top;">{{ $observation }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        @foreach($essays as $index => $essay)
                            @php
                                $isFirst = ($index === 0);
                                $isLast = ($index === count($essays) - 1);
                                $mergeClass = $isFirst ? 'merge-start' : ($isLast ? 'merge-end' : 'merge-mid');
                            @endphp
                            <tr>
                                <td class="{{ $mergeClass }}">
                                    {{ $isFirst ? $matrizDescription : '' }}
                                </td>
                                <td>{{ data_get($essay, 'description', '-') }}</td>
                                <td colspan="2" class="{{ $mergeClass }}">
                                    {{ $isFirst ? $methodology : '' }}
                                </td>
                                <td>{{ data_get($essay, 'lcm', '-') }}</td>
                                <td>{{ data_get($essay, 'units_measurement.description', '-') }}</td>
                                <td class="{{ $mergeClass }}">
                                    {{ $isFirst ? $samples : '' }}
                                </td>
                                <td class="{{ $mergeClass }}">
                                    {{ $isFirst ? $condition : '' }}
                                </td>
                                <td class="{{ $mergeClass }}">
                                    {{ $isFirst ? $frequencyLabel : '' }}
                                </td>
                                <td class="{{ $mergeClass }}">
                                    {{ $isFirst ? $observation : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td style="vertical-align: top;">{{ $matrizDescription }}</td>
                        <td>-</td>
                        <td colspan="2">{{ $methodology }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>{{ $samples }}</td>
                        <td>{{ $condition }}</td>
                        <td>{{ $frequencyLabel }}</td>
                        <td>{{ $observation }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="spacer"><td colspan="10"></td></tr>
        @endforeach

        <tr>
            <td colspan="10" class="section-green">SERVICIOS</td>
        </tr>
        <tr>
            <td colspan="6" class="header-soft">Actividad</td>
            <td class="header-soft">Días</td>
            <td class="header-soft">Cantidad</td>
            <td class="header-soft">Frecuencia</td>
            <td class="header-soft">Observación</td>
        </tr>
        @forelse($services as $service)
            <tr>
                <td colspan="6">{{ data_get($service, 'item.description', '-') }}</td>
                <td>{{ data_get($service, 'item.days', '-') }}</td>
                <td>{{ data_get($service, 'item.amount', '-') }}</td>
                <td>{{ data_get($service, 'item.frequency_label', '-') }}</td>
                <td>{{ data_get($service, 'item.observation', '-') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No hay servicios registrados.</td>
            </tr>
        @endforelse

        <tr class="spacer"><td colspan="10"></td></tr>

        <tr>
            <td colspan="2" class="section-green">Observaciones:</td>
            <td colspan="8">{{ $orderService->observations ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="section-green">Realizado por:</td>
            <td colspan="3">{{ $orderService->user?->full_name ?? '-' }}</td>
            <td colspan="2" class="section-green">Código cotización:</td>
            <td colspan="3">{{ $orderService->quote?->code ?? $orderService->quote_id ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
