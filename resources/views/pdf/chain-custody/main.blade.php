<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Trabajo</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 25px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
        }

        .logo-box {
            width: 220px;
            height: 65px;
            border: 1px solid #d1d5db;
            text-align: center;
            vertical-align: middle;
            font-size: 20px;
            color: #16a34a;
            font-weight: bold;
        }

        .meta {
            text-align: right;
            font-size: 10px;
            line-height: 1.5;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 18px 0 20px;
        }

        .info-table {
            width: 100%;
            margin: 0 auto 25px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 7px 5px;
        }

        .info-label {
            width: 40%;
            font-weight: bold;
        }

        .info-separator {
            width: 5%;
            text-align: center;
        }

        .info-value {
            border-bottom: 1px solid #6b7280;
        }

        .note {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .main-table th {
            background: #92d050;
            border: 1px solid #111827;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        .main-table td {
            border: 1px solid #111827;
            padding: 7px;
            text-align: center;
            vertical-align: middle;
        }

        .code-col {
            width: 25%;
            font-weight: bold;
        }

        .stamp {
            margin-top: 35px;
            text-align: center;
            font-weight: bold;
            color: #dc2626;
            font-size: 18px;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 25px;
            right: 25px;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo-box">

                    </div>
                </td>
                <td class="meta">
                    <strong>Identificación:</strong> F-PR-01-2<br>
                    <strong>Revisión:</strong> 01<br>
                    <strong>Inicio de Vigencia:</strong> {{ $created_at ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Orden de Trabajo</div>

    <table class="info-table">
        <tr>
            <td class="info-label">Orden de servicio</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $os ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Informe de Ensayo</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $number_report ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Cadena de Custodia</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $number_chain ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Matriz</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $matriz ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Fecha de entrega</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $date_agreed ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Hora</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $hour ?? '' }}</td>
        </tr>
    </table>

    <div class="note">
        * Este documento debe ser entregado junto con los siguientes análisis requeridos *
    </div>



    <div class="footer">
        Documento generado automáticamente - GreenLab Perú S.A.C.
    </div>

</body>

</html>
