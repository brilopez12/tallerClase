<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Cache;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 2);
        $page = (int) $request->query('page', 1);

        // Limitar tamaño de página
        $perPage = $perPage > 0 ? min($perPage, 100) : 10;

        // Clave única por página
        $cacheKey = "estudiantes_page_{$page}_per_{$perPage}";

        // Cachear por 60 segundos (puedes cambiar a minutos: now()->addMinutes(5))
        $paginated = Cache::remember($cacheKey, 60, function () use ($perPage, $page) {
            return Estudiante::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $page);
        });

        // Estructura estándar de respuesta
        $response = [
            'success' => true,
            'data' => $paginated->items(),
            'meta' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ],
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function createTen()
    {
        $created = [];

        for ($i = 1; $i <= 10; $i++) {
            $t = Estudiante::create([
                'nombre' => ' nombre automatico ' . now()->format('YmdHis') . '_' . $i,
                'estudios' => ' estudios ' . now()->format('YmdHis') . '_' . $i,
            ]);

            $created[] = $t;
        }

        return response()->json([
            'success' => true,
            'data' => $created,
            'message' => '10 usuarios creadas exitosamente'
        ], 201);
    }
}
