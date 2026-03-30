@php
use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="10" style="text-align:center; font-weight:bold; font-size:16px; background-color:#92d050;">
            COTIZACIÓN DE SERVICIO
        </td>
    </tr>

    <tr>
        <td colspan="3" style="font-weight:bold;">COTIZACIÓN Nº:</td>
        <td colspan="2">{{ $quote->id ?? '-' }}</td>
        <td colspan="3" style="font-weight:bold;">Fecha de elaboración:</td>
        <td colspan="2">{{ Carbon::make($quote->created_at)->format('Y-m-d') }}</td>
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
        <td colspan="9">{{ $quote->direction ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Contacto:</td>
        <td colspan="4">-</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Teléfono/Celular:</td>
        <td colspan="4">-</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Facturar a:</td>
        <td colspan="6">-</td>
        <td style="font-weight:bold; background-color:#d9ead3;">R.U.C.:</td>
        <td colspan="2">{{ $company->ruc ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#d9ead3;">Referencia/procedencia:</td>
        <td colspan="6">{{ $quote->reference }}</td>
        <td style="font-weight:bold; background-color:#d9ead3;">Email:</td>
        <td colspan="2">-</td>
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
        <td style="background: #ebf1de; font-weight:bold;">Matriz</td>
        <td style="background: #ebf1de; font-weight:bold;">Ensayo</td>
        <td colspan="2" style="background: #ebf1de; font-weight:bold;">METODOLOGÍA</td>
        <td style="background: #ebf1de; font-weight:bold;">LCM</td>
        <td style="background: #ebf1de; font-weight:bold;">UNIDAD</td>
        <td style="background: #ebf1de; font-weight:bold;">N° MUESTRAS</td>
        <td style="background: #ebf1de; font-weight:bold;">Condición</td>
        <td style="background: #ebf1de; font-weight:bold;">Precio unit. (Soles)</td>
        <td style="background: #ebf1de; font-weight:bold;">Precio (Soles)</td>
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
        <td rowspan="{{ $rowspan }}">
            {{ data_get($matriz, 'item.description', '-') }}
        </td>
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
        <td rowspan="{{ $rowspan }}">
            {{ data_get($matriz, 'item.number_samples', '-') }}
        </td>
        @endif

        <td>{{ data_get($essay, 'condition.description', '-') }}</td>

        @if($index === 0)
        <td rowspan="{{ $rowspan }}">
            {{ number_format((float) ($matriz->price_unit ?? 0), 2, ',', '.') }}
        </td>
        <td rowspan="{{ $rowspan }}">
            {{ number_format((float) ($matriz->total ?? 0), 2, ',', '.') }}
        </td>
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
        <td>{{ number_format((float) ($matriz->price_unit ?? 0), 2, ',', '.') }}</td>
        <td>{{ number_format((float) ($matriz->total ?? 0), 2, ',', '.') }}</td>
    </tr>
    @endif
    @endforeach

    <tr>
        <td colspan="7" style="font-size:12px; text-align:left; vertical-align:top;">
            L.C.M.: Limite de cuantificación de Método
            Se identifica Parametros Acreditados ante INACAL-DA
            Se identifica Parametros Acreditados ante IAS (entidad conformante de ILAC, reconocida por INACAL)
        </td>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">
            Total {{ $loop->iteration }} (Soles):
        </td>
        <td style="font-weight:bold;">
            {{ number_format((float) $group['total'], 2, ',', '.') }}
        </td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>
    @endforeach

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#92d050;">
            SERVICIO DE ANÁLISIS
        </td>
    </tr>

    <tr>
        <td colspan="6" style="background: #ebf1de; font-weight:bold;">Monitoreo y análisis</td>
        <td style="background: #ebf1de; font-weight:bold;">Días</td>
        <td style="background: #ebf1de; font-weight:bold;">Cantidad</td>
        <td style="background: #ebf1de; font-weight:bold;">Precio unit. (Soles)</td>
        <td style="background: #ebf1de; font-weight:bold;">Precio (Soles)</td>
    </tr>

    @foreach($services as $service)
    <tr>
        <td colspan="6">{{ data_get($service, 'item.description', '-') }}</td>
        <td>{{ data_get($service, 'item.days', '-') }}</td>
        <td>{{ data_get($service, 'item.amount', '-') }}</td>
        <td>{{ number_format((float) data_get($service, 'item.unit_price', 0), 2, ',', '.') }}</td>
        <td>{{ number_format((float) data_get($service, 'item.price', $service->total ?? 0), 2, ',', '.') }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="7"></td>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">Total:</td>
        <td style="font-weight:bold;">{{ number_format((float) $servicesTotal, 2, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>

    <tr>
        <td colspan="10" style="font-weight:bold; background-color:#ffc000;">
            GASTOS LOGISTICOS
        </td>
    </tr>

    <tr>
        <td style="background: #8db4e2;" colspan="6">ACTIVIDADES</td>
        <td style="background: #8db4e2;">DÍAS</td>
        <td style="background: #8db4e2;">CANTIDAD</td>
        <td style="background: #8db4e2;">COSTO UNIT.</td>
        <td style="background: #8db4e2;">COSTO UNIT.</td>
    </tr>

    @foreach($other_expense as $otherexpense)
    <tr>
        <td colspan="6">{{ data_get($otherexpense, 'item.description', '-') }}</td>
        <td>{{ data_get($otherexpense, 'item.days', '-') }}</td>
        <td>{{ data_get($otherexpense, 'item.amount', '-') }}</td>
        <td>{{ number_format((float) data_get($otherexpense, 'item.unit_price', 0), 2, ',', '.') }}</td>
        <td>{{ number_format((float) data_get($otherexpense, 'item.price', $service->total ?? 0), 2, ',', '.') }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="7"></td>
        <td colspan="2" style="font-weight:bold; background-color:#92d050;">Total:</td>
        <td style="font-weight:bold;">{{ number_format((float) $otherExpenseTotal, 2, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="6" style="font-weight:bold; background-color:#92d050;">
            Observaciones o comentarios:
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td colspan="6" rowspan="3">
            {{ $quote->observations ?? '-' }}
        </td>
        <td></td>
        <td style="background: #92d050;" colspan="2">Total SIN IGV (Soles):</td>
        <td>{{ number_format((float) $quote->subtotal, 2, ',', '.') }}</td>
    </tr>

    <tr>
        <td></td>
        <td style="background: #92d050;" colspan="2">IGV (Soles): 18%</td>
        <td>{{ number_format((float) $quote->igv, 2, ',', '.') }}</td>
    </tr>

    <tr>
        <td></td>
        <td style="background: #92d050; font-weight: bold;" colspan="2">Total CON IGV (Soles):</td>
        <td>{{ number_format((float) $quote->total, 2, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>

    <tr>
        <td style="background: #92d050; font-weight: bold;" colspan="2">Realizado por: </td>
        <td colspan="2">
            {{$quote->user?->full_name}}
        </td>
    </tr>
    
    <tr>
        <td style="background: #92d050; font-weight: bold;" colspan="2">Aprobado por: </td>
        <td colspan="2">RRM</td>
    </tr>
</table>