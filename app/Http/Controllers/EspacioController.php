<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;

class EspacioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/espacios",
     *     tags={"Espacios"},
     *     summary="Obtener todos los espacios",
     *     description="Retorna una lista de todos los espacios disponibles",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de espacios"
     *     )
     * )
     */
    public function index()
    {
        $espacios = Espacio::all();
        return response()->json($espacios);
    }


    /**
     * @OA\Post(
     *     path="/api/espacios",
     *     tags={"Espacios"},
     *     summary="Crear un nuevo espacio",
     *     description="Registrar un nuevo espacio en el sistema",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Sala A"),
     *             @OA\Property(property="descripcion", type="string", example="Sala de conferencias"),
     *             @OA\Property(property="capacidad", type="integer", example=100),
     *             @OA\Property(property="ubicacion", type="string", example="Edificio 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Espacio creado exitosamente"
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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer',
            'ubicacion' => 'nullable|string',
        ]);

        $espacio = Espacio::create($validated);
        return response()->json($espacio, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/espacios/{id}",
     *     tags={"Espacios"},
     *     summary="Obtener un espacio por ID",
     *     description="Retorna los detalles de un espacio específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del espacio"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del espacio"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Espacio no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $espacio = Espacio::findOrFail($id);
        return response()->json($espacio);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer',
            'ubicacion' => 'nullable|string',
        ]);

        $espacio = Espacio::findOrFail($id);
        $espacio->update($validated);

        return response()->json($espacio);
    }

    public function destroy($id)
    {
        $espacio = Espacio::findOrFail($id);
        $espacio->delete();

        return response()->json(null, 204);
    }
}
