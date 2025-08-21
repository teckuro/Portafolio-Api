<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:500',
            'image_url' => 'required|string',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'required|array',
            'features' => 'required|array',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'order' => 'integer'
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image_url' => $request->image_url,
            'project_url' => $request->project_url,
            'github_url' => $request->github_url,
            'tech_stack' => $request->tech_stack,
            'features' => $request->features,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'order' => $request->order ?? 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proyecto creado exitosamente',
            'data' => $project
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'short_description' => 'sometimes|required|string|max:500',
            'image_url' => 'sometimes|required|string',
            'project_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tech_stack' => 'sometimes|required|array',
            'features' => 'sometimes|required|array',
            'status' => 'sometimes|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'order' => 'integer'
        ]);

        $project->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado exitosamente',
            'data' => $project
        ]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }

    public function toggleFeatured($id)
    {
        $project = Project::findOrFail($id);
        $project->is_featured = !$project->is_featured;
        $project->save();

        return response()->json([
            'success' => true,
            'message' => $project->is_featured ? 'Proyecto marcado como destacado' : 'Proyecto removido de destacados',
            'data' => $project
        ]);
    }

    public function stats()
    {
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'inactive' => Project::where('status', 'inactive')->count(),
            'draft' => Project::where('status', 'draft')->count(),
            'featured' => Project::where('is_featured', true)->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
