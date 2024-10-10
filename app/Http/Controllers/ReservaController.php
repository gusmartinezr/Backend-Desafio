<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     summary="Obtener todas las reservas del usuario autenticado",
     *     description="Retorna una lista de las reservas realizadas por el usuario autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas"
     *     )
     * )
     */
    public function index()
    {
        $reservas = Auth::user()->reservas;
        return response()->json($reservas);
    }


    /**
     * @OA\Post(
     *     path="/api/reservas",
     *     tags={"Reservas"},
     *     summary="Crear una nueva reserva",
     *     description="Registrar una nueva reserva para un espacio",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="espacio_id", type="integer", example=1),
     *             @OA\Property(property="nombre_evento", type="string", example="Conferencia Anual"),
     *             @OA\Property(property="fecha_inicio", type="string", format="date-time", example="2024-12-01T10:00:00"),
     *             @OA\Property(property="fecha_fin", type="string", format="date-time", example="2024-12-01T12:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflicto de horarios"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'espacio_id' => 'required|exists:espacios,id',
            'nombre_evento' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $conflictingReservation = Reserva::where('espacio_id', $validated['espacio_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('fecha_inicio', [$validated['fecha_inicio'], $validated['fecha_fin']])
                    ->orWhereBetween('fecha_fin', [$validated['fecha_inicio'], $validated['fecha_fin']]);
            })->exists();

        if ($conflictingReservation) {
            return response()->json(['error' => 'El espacio ya está reservado en ese horario.'], 409);
        }

        $reserva = Reserva::create([
            'user_id' => Auth::id(),
            'espacio_id' => $validated['espacio_id'],
            'nombre_evento' => $validated['nombre_evento'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
        ]);

        return response()->json($reserva, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/reservas/{id}",
     *     tags={"Reservas"},
     *     summary="Obtener una reserva por ID",
     *     description="Retorna los detalles de una reserva específica del usuario autenticado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la reserva"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la reserva"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $reserva = Auth::user()->reservas()->findOrFail($id);
        return response()->json($reserva);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre_evento' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $reserva = Auth::user()->reservas()->findOrFail($id);
        $reserva->update($validated);

        return response()->json($reserva);
    }

    public function destroy($id)
    {
        $reserva = Auth::user()->reservas()->findOrFail($id);
        $reserva->delete();

        return response()->json(null, 204);
    }
}
