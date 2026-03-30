<table>
    <tr>
        <td colspan="7" style="text-align:center; font-weight:bold; font-size:16px; background-color:#1F4E78; color:#FFFFFF;">
            GASTOS LOGÍSTICOS
        </td>
    </tr>

    <tr></tr>

    <tr>
        <td style="font-weight:bold;">Sede</td>
        <td colspan="6">{{ $quote['site'] }}</td>
    </tr>

    <tr></tr>

    <tr style="font-weight:bold; background-color:#D9D9D9; text-align:center;">
        <td colspan="3">ACTIVIDADES</td>
        <td>DÍAS</td>
        <td>CANTIDAD</td>
        <td>COSTO UNIT.</td>
        <td>COSTO TOTAL</td>
    </tr>

    @foreach($logistics as $item)
    <tr>
        <td colspan="3">{{ $item['activity'] }}</td>
        <td>{{ $item['days'] }}</td>
        <td>{{ $item['quantity'] }}</td>
        <td>{{ $item['unit_cost'] }}</td>
        <td>{{ $item['days'] * $item['quantity'] * $item['unit_cost'] }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="5"></td>
        <td style="font-weight:bold;">TOTAL</td>
        <td style="font-weight:bold;">{{ $totalLogistics }}</td>
    </tr>
</table>