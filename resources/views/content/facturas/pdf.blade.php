<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Zana Tapicería - #{{ $factura->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #e76f51; }
        .invoice-details { text-align: right; }
        .client-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background-color: #f8f9fa; font-weight: bold; }
        .total-row { font-weight: bold; font-size: 1.2em; color: #e76f51; }
        .footer { text-align: center; margin-top: 50px; font-size: 0.9em; color: #777; }
        
        /* Ocultar botones en impresión */
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; box-shadow: none; margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Imprimir / Guardar PDF</button>
        <a href="{{ route('facturas.index') }}" style="margin-left: 20px; color: #555;">Volver</a>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="logo">ZANA TAPICERÍA</div>
                <div>Vilanna, Bescanó (Girona)<br>Tel: 631 498 980 | tapecero65@gmail.com</div>
            </div>
            <div class="invoice-details">
                <h2>FACTURA</h2>
                <strong>Número:</strong> FAC-{{ str_pad($factura->id, 6, "0", STR_PAD_LEFT) }}<br>
                <strong>Fecha:</strong> {{ $factura->created_at->format('d/m/Y') }}<br>
                <strong>Estado:</strong> {{ $factura->pagado ? 'PAGADO (' . ($factura->fecha_pago ? \Carbon\Carbon::parse($factura->fecha_pago)->format('d/m/Y') : 'Sí') . ')' : 'PENDIENTE' }}
            </div>
        </div>

        <div class="client-info">
            <strong>Facturar a:</strong><br>
            {{ $factura->encargo->vehiculo->cliente->nombre }} {{ $factura->encargo->vehiculo->cliente->apellido }}<br>
            Teléfono: {{ $factura->encargo->vehiculo->cliente->telefono }}<br>
            Vehículo: {{ $factura->encargo->vehiculo->marca }} {{ $factura->encargo->vehiculo->modelo }} ({{ $factura->encargo->vehiculo->matricula }})
        </div>

        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Trabajo realizado:</strong> {{ $factura->encargo->descripcion }}
                    </td>
                    <td style="text-align: right;">{{ number_format($factura->importe_total, 2) }} €</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: right;" class="total-row">TOTAL:</td>
                    <td style="text-align: right;" class="total-row">{{ number_format($factura->importe_total, 2) }} €</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; font-size: 12px; color: #666;">Total incluye impuestos aplicables.</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            Gracias por confiar en Zana Tapicería.<br>
            El vehículo ha sido entregado en conformidad.
        </div>
    </div>
</body>
</html>
