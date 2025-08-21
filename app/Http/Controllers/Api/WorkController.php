<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Obtener todas las experiencias laborales activas
     */
    public function index()
    {
        $works = Work::where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $works
        ]);
    }

    /**
     * Obtener una experiencia laboral específica
     */
    public function show($id)
    {
        $work = Work::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $work
        ]);
    }

    /**
     * Obtener experiencia laboral actual
     */
    public function current()
    {
        $works = Work::where('status', 'active')
            ->where('is_current', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $works
        ]);
    }
}
