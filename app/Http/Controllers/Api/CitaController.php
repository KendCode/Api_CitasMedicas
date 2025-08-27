<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        // Solo citas del usuario autenticado
        $citas = Cita::where('user_id', $request->user()->id)->get();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'medico' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $cita = Cita::create([
            'user_id' => $request->user()->id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'medico' => $request->medico,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($cita, 201);
    }


    public function show(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();
        return response()->json($cita);
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $cita->update($request->only(['fecha', 'hora', 'medico', 'descripcion']));

        return response()->json($cita);
    }

    public function destroy(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();
        $cita->delete();
        return response()->json(['message' => 'Cita cancelada']);
    }
}
