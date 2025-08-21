<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index()
    {
        $works = Work::orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $works
        ]);
    }

    public function show($id)
    {
        $work = Work::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $work
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'nullable|string',
            'location' => 'required|string|max:255',
            'tech' => 'required|array',
            'achievements' => 'required|array',
            'is_current' => 'boolean',
            'company_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,draft'
        ]);

        $work = Work::create([
            'company' => $request->company,
            'position' => $request->position,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'tech' => $request->tech,
            'achievements' => $request->achievements,
            'is_current' => $request->is_current ?? false,
            'company_url' => $request->company_url,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Experiencia laboral creada exitosamente',
            'data' => $work
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $work = Work::findOrFail($id);

        $request->validate([
            'company' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|string',
            'end_date' => 'nullable|string',
            'location' => 'sometimes|required|string|max:255',
            'tech' => 'sometimes|required|array',
            'achievements' => 'sometimes|required|array',
            'is_current' => 'boolean',
            'company_url' => 'nullable|url',
            'status' => 'sometimes|in:active,inactive,draft'
        ]);

        $work->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Experiencia laboral actualizada exitosamente',
            'data' => $work
        ]);
    }

    public function destroy($id)
    {
        $work = Work::findOrFail($id);
        $work->delete();

        return response()->json([
            'success' => true,
            'message' => 'Experiencia laboral eliminada exitosamente'
        ]);
    }

    public function toggleCurrent($id)
    {
        $work = Work::findOrFail($id);
        $work->is_current = !$work->is_current;
        $work->save();

        return response()->json([
            'success' => true,
            'message' => $work->is_current ? 'Marcado como trabajo actual' : 'Removido de trabajo actual',
            'data' => $work
        ]);
    }

    public function stats()
    {
        $stats = [
            'total' => Work::count(),
            'active' => Work::where('status', 'active')->count(),
            'inactive' => Work::where('status', 'inactive')->count(),
            'draft' => Work::where('status', 'draft')->count(),
            'current' => Work::where('is_current', true)->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
