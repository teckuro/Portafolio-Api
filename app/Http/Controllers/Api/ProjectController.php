<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Obtener todos los proyectos activos
     */
    public function index()
    {
        $projects = Project::where('status', 'active')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Obtener un proyecto específico
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Obtener proyectos destacados
     */
    public function featured()
    {
        $projects = Project::where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }
}
