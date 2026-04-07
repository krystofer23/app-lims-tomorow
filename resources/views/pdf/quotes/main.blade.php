@php
use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $quote->id }}</title>
    <style>
        @page {
            margin: 18px 18px 22px 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .no-border td {
            border: none !important;
            padding: 3px 0;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            background-color: #92d050;
        }

        .section-green {
            font-weight: bold;
            background-color: #92d050;
        }

        .section-yellow {
            font-weight: bold;
            background-color: #ffc000;
        }

        .label-green {
            font-weight: bold;
            background-color: #d9ead3;
        }

        .header-soft {
            background-color: #ebf1de;
            font-weight: bold;
            text-align: center;
        }

        .header-blue {
            background-color: #8db4e2;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .spacer td {
            border: none !important;
            height: 8px;
            padding: 0;
        }

        .observations {
            min-height: 70px;
            vertical-align: top;
        }

        .notes {
            font-size: 9px;
            line-height: 1.3;
            vertical-align: top;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td colspan="10" class="title">COTIZACIÓN DE SERVICIO</td>
        </tr>

        <tr>
            <td colspan="3" class="bold">COTIZACIÓN Nº:</td>
            <td colspan="2">{{ $quote->id ?? '-' }}</td>
            <td colspan="3" class="bold">Fecha de elaboración:</td>
            <td colspan="2">{{ Carbon::make($quote->created_at)->format('Y-m-d') }}</td>
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
            <td colspan="9">{{ $quote->direction ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Contacto:</td>
            <td colspan="4">{{ $contact->user?->full_name }}</td>
            <td class="label-green">Teléfono/Celular:</td>
            <td colspan="4">{{ $contact?->phone }}</td>
        </tr>
        <tr>
            <td class="label-green">Facturar a:</td>
            <td colspan="6">{{ $contact?->email }}</td>
            <td class="label-green">R.U.C.:</td>
            <td colspan="2">{{ $company->ruc ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-green">Referencia/procedencia:</td>
            <td colspan="6">{{ $quote->reference ?? '-' }}</td>
            <td class="label-green">Email:</td>
            <td colspan="2">{{ $contact?->email }}</td>
        </tr>

        <tr class="spacer">
            <td colspan="10"></td>
        </tr>

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
            <td colspan="2" class="header-soft">METODOLOGÍA</td>
            <td class="header-soft">LCM</td>
            <td class="header-soft">UNIDAD</td>
            <td class="header-soft">N° MUESTRAS</td>
            <td class="header-soft">Condición</td>
            <td class="header-soft">Precio unit. (Soles)</td>
            <td class="header-soft">Precio (Soles)</td>
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
            <td rowspan="{{ $rowspan }}" class="text-center">
                {{ data_get($matriz, 'item.number_samples', '-') }}
            </td>
            @endif

            <td>{{ data_get($essay, 'condition.description', '-') }}</td>

            @if($index === 0)
            <td rowspan="{{ $rowspan }}" class="text-right">
                {{ number_format((float) ($matriz->price_unit ?? 0), 2, ',', '.') }}
            </td>
            <td rowspan="{{ $rowspan }}" class="text-right">
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
            <td class="text-center">{{ data_get($matriz, 'item.number_samples', '-') }}</td>
            <td>-</td>
            <td class="text-right">{{ number_format((float) ($matriz->price_unit ?? 0), 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format((float) ($matriz->total ?? 0), 2, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach

        <tr>
            <td colspan="7" class="notes">
                L.C.M.: Límite de cuantificación de método.<br>
                Se identifica parámetros acreditados ante INACAL-DA.<br>
                Se identifica parámetros acreditados ante IAS.
            </td>
            <td colspan="2" class="section-green">Total {{ $loop->iteration }} (Soles):</td>
            <td class="bold text-right">
                {{ number_format((float) $group['total'], 2, ',', '.') }}
            </td>
        </tr>

        <tr class="spacer">
            <td colspan="10"></td>
        </tr>
        @endforeach

        <tr>
            <td colspan="10" class="section-green">SERVICIO DE ANÁLISIS</td>
        </tr>

        <tr>
            <td colspan="6" class="header-soft">Monitoreo y análisis</td>
            <td class="header-soft">Días</td>
            <td class="header-soft">Cantidad</td>
            <td class="header-soft">Precio unit. (Soles)</td>
            <td class="header-soft">Precio (Soles)</td>
        </tr>

        @foreach($services as $service)
        <tr>
            <td colspan="6">{{ data_get($service, 'item.description', '-') }}</td>
            <td class="text-center">{{ data_get($service, 'item.days', '-') }}</td>
            <td class="text-center">{{ data_get($service, 'item.amount', '-') }}</td>
            <td class="text-right">{{ number_format((float) data_get($service, 'item.unit_price', 0), 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format((float) data_get($service, 'item.price', $service->total ?? 0), 2, ',', '.') }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="7"></td>
            <td colspan="2" class="section-green">Total:</td>
            <td class="bold text-right">{{ number_format((float) $servicesTotal, 2, ',', '.') }}</td>
        </tr>

        <tr class="spacer">
            <td colspan="10"></td>
        </tr>

        <tr>
            <td colspan="10" class="section-yellow">GASTOS LOGÍSTICOS</td>
        </tr>

        <tr>
            <td colspan="6" class="header-blue">ACTIVIDADES</td>
            <td class="header-blue">DÍAS</td>
            <td class="header-blue">CANTIDAD</td>
            <td class="header-blue">COSTO UNIT.</td>
            <td class="header-blue">PRECIO</td>
        </tr>

        @foreach($other_expense as $otherexpense)
        <tr>
            <td colspan="6">{{ data_get($otherexpense, 'item.description', '-') }}</td>
            <td class="text-center">{{ data_get($otherexpense, 'item.days', '-') }}</td>
            <td class="text-center">{{ data_get($otherexpense, 'item.amount', '-') }}</td>
            <td class="text-right">{{ number_format((float) data_get($otherexpense, 'item.unit_price', 0), 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format((float) data_get($otherexpense, 'item.price', $otherexpense->total ?? 0), 2, ',', '.') }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="7"></td>
            <td colspan="2" class="section-green">Total:</td>
            <td class="bold text-right">{{ number_format((float) $otherExpenseTotal, 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="6" class="section-green">Observaciones o comentarios:</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="6" rowspan="3" class="observations">
                {{ $quote->observations ?? '-' }}
            </td>
            <td></td>
            <td colspan="2" class="section-green">Total SIN IGV (Soles):</td>
            <td class="text-right">{{ number_format((float) $quote->subtotal, 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td></td>
            <td colspan="2" class="section-green">IGV (Soles): 18%</td>
            <td class="text-right">{{ number_format((float) $quote->igv, 2, ',', '.') }}</td>
        </tr>

        <tr>
            <td></td>
            <td colspan="2" class="section-green bold">Total CON IGV (Soles):</td>
            <td class="bold text-right">{{ number_format((float) $quote->total, 2, ',', '.') }}</td>
        </tr>

        <tr class="spacer">
            <td colspan="10"></td>
        </tr>

        <tr>
            <td colspan="2" class="section-green">Realizado por:</td>
            <td colspan="2">{{ $quote->user?->full_name ?? '-' }}</td>
            <td colspan="6"></td>
        </tr>

        <tr>
            <td colspan="2" class="section-green">Aprobado por:</td>
            <td colspan="2">RRM</td>
            <td colspan="6"></td>
        </tr>
    </table>

</body>

</html>