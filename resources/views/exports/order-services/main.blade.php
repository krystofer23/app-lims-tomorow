@php
use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="10" style="text-align:center; font-weight:bold; font-size:16px; background-color:#92d050;">
            ORDEN DE SERVICIO
        </td>
    </tr>

    <tr>
        <td colspan="2" style="font-weight:bold;">ORDEN Nº:</td>
        <td colspan="2">{{ $orderService->code ?? $orderService->id ?? '-' }}</td>
        <td colspan="2" style="font-weight:bold;">Fecha de elaboración:</td>
        <td colspan="2">{{ Carbon::make($orderService->created_at)->format('Y-m-d') }}</td>
        <td style="font-weight:bold;">Fecha de atención:</td>
        <td>{{ $orderService->date_attention ?? '-' }}</td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">INFORMACIÓN DEL CLIENTE</td>
    </tr>

    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Razón social:</td>
        <td colspan="9">{{ $company->business_name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Dirección:</td>
        <td colspan="9">{{ $orderService->direction ?? $company->direction ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Contacto:</td>
        <td colspan="4">{{ $contact?->user?->full_name ?? '-' }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Teléfono/Celular:</td>
        <td colspan="4">{{ $contact?->phone ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Email:</td>
        <td colspan="6">{{ $contact?->email ?? '-' }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">R.U.C.:</td>
        <td colspan="2">{{ $company->ruc ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Referencia:</td>
        <td colspan="9">{{ $orderService->reference ?? '-' }}</td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">DATOS DE MONITOREO</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Procedencia:</td>
        <td colspan="4">{{ $orderService->origin ?? '-' }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Proyecto:</td>
        <td colspan="4">{{ $orderService->project ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Fecha de salida:</td>
        <td colspan="2">{{ $orderService->date_output ?? '-' }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Fecha de inducción:</td>
        <td colspan="2">{{ $orderService->date_induction ?? '-' }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Inicio monitoreo:</td>
        <td colspan="2">{{ $orderService->date_monitoring_init ?? '-' }}</td>
        <td>{{ $orderService->date_monitoring_end ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Detalle:</td>
        <td colspan="9">{{ $orderService->details ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Estaciones:</td>
        <td colspan="9">{{ $orderService->stations_monitoring ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Proyecto monitoreo:</td>
        <td colspan="9">{{ $orderService->project_monitoring ?? '-' }}</td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>

    @foreach($groupedMatrices as $group)
        <tr>
            <td colspan="10" style="font-weight:bold; background-color:#92d050;">
                MATRIZ: {{ strtoupper($group['description']) }}
                @if(!empty($group['frequency_label']))
                    [{{ $group['frequency_label'] }}]
                @endif
            </td>
        </tr>

        <tr>
            <td style="background:#ebf1de; font-weight:bold;">Matriz</td>
            <td style="background:#ebf1de; font-weight:bold;">Ensayo</td>
            <td colspan="2" style="background:#ebf1de; font-weight:bold;">Metodología</td>
            <td style="background:#ebf1de; font-weight:bold;">LCM</td>
            <td style="background:#ebf1de; font-weight:bold;">Unidad</td>
            <td style="background:#ebf1de; font-weight:bold;">N° Muestras</td>
            <td style="background:#ebf1de; font-weight:bold;">Condición</td>
            <td style="background:#ebf1de; font-weight:bold;">Frecuencia</td>
            <td style="background:#ebf1de; font-weight:bold;">Observación</td>
        </tr>

        @foreach($group['items'] as $matriz)
            @php
                $essays = data_get($matriz, 'item.essays', []);
                $rowspan = max(count($essays), 1);
            @endphp

            @if(count($essays))
                @foreach($essays as $index => $essay)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">{{ data_get($matriz, 'item.description', '-') }}</td>
                        @endif

                        <td>{{ data_get($essay, 'description', '-') }}</td>

                        @if($index === 0)
                            <td colspan="2" rowspan="{{ $rowspan }}">
                                {{ data_get($matriz, 'item.methodologie.description', '-') }}
                            </td>
                        @endif

                        <td>{{ data_get($essay, 'lcm', '-') }}</td>
                        <td>{{ data_get($essay, 'units_measurement.description', '-') }}</td>

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">{{ data_get($matriz, 'item.number_samples', '-') }}</td>
                            <td rowspan="{{ $rowspan }}">{{ data_get($essay, 'condition.description', '-') }}</td>
                            <td rowspan="{{ $rowspan }}">{{ data_get($matriz, 'item.frequency_label', '-') }}</td>
                            <td rowspan="{{ $rowspan }}">{{ data_get($matriz, 'item.observation', '-') }}</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ data_get($matriz, 'item.description', '-') }}</td>
                    <td>-</td>
                    <td colspan="2">{{ data_get($matriz, 'item.methodologie.description', '-') }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{ data_get($matriz, 'item.number_samples', '-') }}</td>
                    <td>-</td>
                    <td>{{ data_get($matriz, 'item.frequency_label', '-') }}</td>
                    <td>{{ data_get($matriz, 'item.observation', '-') }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="10"></td>
        </tr>
    @endforeach

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">SERVICIOS</td>
    </tr>

    <tr>
        <td colspan="6" style="background:#ebf1de; font-weight:bold;">Actividad</td>
        <td style="background:#ebf1de; font-weight:bold;">Días</td>
        <td style="background:#ebf1de; font-weight:bold;">Cantidad</td>
        <td style="background:#ebf1de; font-weight:bold;">Frecuencia</td>
        <td style="background:#ebf1de; font-weight:bold;">Observación</td>
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

    <tr>
        <td colspan="10"></td>
    </tr>

    <tr>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">Observaciones:</td>
        <td colspan="8">{{ $orderService->observations ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">Realizado por:</td>
        <td colspan="3">{{ $orderService->user?->full_name ?? '-' }}</td>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">Código cotización:</td>
        <td colspan="3">{{ $orderService->quote?->code ?? $orderService->quote_id ?? '-' }}</td>
    </tr>
</table>
