<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Encargo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    // Carga la vista del calendario central
    public function showCalendar()
    {
        return view('content.citas.calendario');
    }

    // Devuelve todos los eventos (revisiones y entregas) para el calendario JS
    public function getEvents()
    {
        $eventos = [];

        // 1. Citas de revisión (trabajos nuevos)
        $revisiones = Encargo::with('vehiculo.cliente')
            ->whereNotNull('cita_revision')
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->get();
            
        foreach ($revisiones as $rev) {
            $eventos[] = [
                'title' => "Revisión: " . $rev->vehiculo->marca . " (" . $rev->vehiculo->cliente->nombre . ")",
                'start' => $rev->cita_revision . 'T' . ($rev->hora_cita ?? '08:00:00'),
                'color' => '#696cff', // Azul Materio
                'url' => route('encargos.recepcion')
            ];
        }

        // 2. Entregas previstas (producción)
        $entregas = Encargo::with('vehiculo.cliente')
            ->whereNotNull('cita_recogida')
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->get();
            
        foreach ($entregas as $ent) {
            $eventos[] = [
                'title' => "ENTREGA: " . $ent->vehiculo->marca,
                'start' => $ent->cita_recogida,
                'color' => '#71dd37', // Verde Success
                'allDay' => true,
                'url' => route('encargos.produccion')
            ];
        }

        return response()->json($eventos);
    }

    // Comprueba qué horas están pilladas para un día concreto
    public function checkAvailability(Request $request)
    {
        $fecha = $request->get('date');
        if (!$fecha) return response()->json([]);

        $ocupadas = [];

        // Buscamos recepciones en encargos
        $encargos = Encargo::with('vehiculo.cliente')
            ->whereDate('cita_revision', $fecha)
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->get();

        foreach ($encargos as $enc) {
            if ($enc->hora_cita) {
                $ocupadas[] = [
                    'hora' => substr($enc->hora_cita, 0, 5),
                    'cliente' => $enc->vehiculo->cliente->nombre ?? 'S/N',
                    'tipo' => 'recepcion'
                ];
            }
        }

        // Buscamos trabajos en la tabla de citas
        $citas = Cita::with('encargo.vehiculo.cliente')
            ->whereDate('fecha', $fecha)
            ->get();

        foreach ($citas as $c) {
            if ($c->hora) {
                $ocupadas[] = [
                    'hora' => substr($c->hora, 0, 5),
                    'cliente' => $c->encargo->vehiculo->cliente->nombre ?? 'S/N',
                    'tipo' => 'produccion'
                ];
            }
        }

        // Ordenamos por hora para que salga bonito
        usort($ocupadas, fn($a, $b) => strcmp($a['hora'], $b['hora']));

        return response()->json($ocupadas);
    }

    // Listado simple de la agenda
    public function index()
    {
        $citas = Cita::with('encargo.vehiculo.cliente')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();  

        return view('content.citas.index', compact('citas'));
    }

    public function create()
    {
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.citas.create', compact('encargos'));
    }

    public function store(Request $request)
    {
        Cita::create($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita guardada.');
    }

    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $encargos = Encargo::with('vehiculo.cliente')->get();
        return view('content.citas.edit', compact('cita', 'encargos'));
    }

    public function update(Request $request, $id)
    {
        Cita::findOrFail($id)->update($request->all());
        return redirect()->route('citas.index')->with('success', 'Cita actualizada.');
    }

    public function destroy($id)
    {
        Cita::findOrFail($id)->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada.');
    }

    // Saca todos los días que tienen algo (para pintar puntos rojos en el calendario)
    public function getMonthlyAvailability()
    {
        $fechasRevision = Encargo::whereNotNull('cita_revision')
            ->whereNotIn('estado', ['Cancelado', 'Entregado'])
            ->pluck('cita_revision')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $fechasCitas = Cita::pluck('fecha')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        return response()->json(array_values(array_unique(array_merge($fechasRevision, $fechasCitas))));
    }
}