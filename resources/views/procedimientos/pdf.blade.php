<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Procedimiento</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; margin: 32px; color: #222; }
        h1 { font-size: 22px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .section { margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
        .subtitle { font-size: 14px; margin: 0 0 8px 0; font-weight: bold; }
        ul { margin: 0; padding-left: 18px; font-size: 12px; }
        .meta { font-size: 12px; color: #555; margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>Ficha de Procedimiento</h1>
    <div class="meta">Generado: {{ now()->format('d/m/Y H:i') }}</div>

    <div class="section">
        <p class="subtitle">Datos del Caso</p>
        <table>
            <tr>
                <th>Legajo</th>
                <td>{{ $procedimiento->legajo_fiscal ?? 'N/D' }}</td>
                <th>Fecha</th>
                <td>{{ optional($procedimiento->fecha_hecho ?? $procedimiento->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>UFI</th>
                <td colspan="3">{{ $procedimiento->ufi->nombre ?? 'N/D' }}</td>
            </tr>
            <tr>
                <th>Brigada</th>
                <td>{{ $procedimiento->brigada->nombre ?? 'N/D' }}</td>
                <th>Responsable</th>
                <td>{{ $procedimiento->user->name ?? 'N/D' }}</td>
            </tr>
            <tr>
                <th>Carátula</th>
                <td colspan="3">{{ $procedimiento->caratula ?? 'N/D' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="subtitle">Personas Vinculadas</p>
        @if($procedimiento->personas->isEmpty())
            <p style="font-size:12px; margin:0;">No hay personas vinculadas.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Situación Procesal</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procedimiento->personas as $persona)
                        <tr>
                            <td>{{ $persona->apellidos ?? '' }} {{ $persona->nombres ?? '' }}</td>
                            <td>{{ $persona->documento ?? 'N/D' }}</td>
                            <td>{{ $persona->pivot->situacion_procesal ?? 'N/D' }}</td>
                            <td>{{ $persona->pivot->observaciones ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section">
        <p class="subtitle">Domicilios Vinculados</p>
        @if($procedimiento->domicilios->isEmpty())
            <p style="font-size:12px; margin:0;">No hay domicilios vinculados.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Calle</th>
                        <th>Número</th>
                        <th>Barrio</th>
                        <th>Departamento/Provincia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procedimiento->domicilios as $domicilio)
                        <tr>
                            <td>{{ $domicilio->calle ?? 'N/D' }}</td>
                            <td>{{ $domicilio->numero ?? $domicilio->altura ?? 'N/D' }}</td>
                            <td>{{ $domicilio->barrio ?? 'N/D' }}</td>
                            <td>{{ $domicilio->departamento ?? 'N/D' }} / {{ $domicilio->provincia ?? 'N/D' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
