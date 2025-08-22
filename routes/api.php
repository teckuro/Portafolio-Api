<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\WorkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas limpias y organizadas para la API del portafolio
|
*/

// ================================
// RUTAS DE SALUD Y PRUEBA
// ================================

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Portfolio API',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// ================================
// RUTAS DE ARCHIVOS ESTÁTICOS
// ================================

// Servir archivos de forma dinámica
Route::get('/files/{path}', function ($path) {
    // Validar ruta
    if (strpos($path, '..') !== false) {
        return response()->json(['error' => 'Invalid path'], 400);
    }
    
    $fullPath = 'assets/uploads/' . $path;
    
    if (!Storage::disk('public')->exists($fullPath)) {
        return response()->json(['error' => 'File not found'], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($fullPath);
        $mimeType = Storage::disk('public')->mimeType($fullPath);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to serve file'], 500);
    }
})->where('path', '.*');

// Placeholders específicos
Route::get('/placeholder/{category}/{number}', function ($category, $number) {
    $path = "assets/uploads/{$category}/placeholder{$number}.svg";
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json(['error' => 'Placeholder not found'], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to serve placeholder'], 500);
    }
})->where(['category' => 'projects|works|temp', 'number' => '[0-9]+']);

// ================================
// RUTAS PÚBLICAS DEL PORTAFOLIO
// ================================

// Proyectos
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/featured', [ProjectController::class, 'featured']);
    Route::get('/{id}', [ProjectController::class, 'show'])->where('id', '[0-9]+');
});

// Experiencia laboral
Route::prefix('works')->group(function () {
    Route::get('/', [WorkController::class, 'index']);
    Route::get('/current', [WorkController::class, 'current']);
    Route::get('/{id}', [WorkController::class, 'show'])->where('id', '[0-9]+');
});

// ================================
// RUTAS DE ADMINISTRACIÓN PÚBLICAS
// ================================

Route::prefix('admin')->group(function () {
    // Autenticación
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Upload de archivos (público para facilitar desarrollo)
    Route::post('/upload', [\App\Http\Controllers\Api\Admin\UploadController::class, 'upload']);
    Route::get('/upload/health', [\App\Http\Controllers\Api\Admin\UploadController::class, 'health']);
    
    // Estadísticas públicas (solo lectura)
    Route::get('/projects/stats', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'stats']);
    Route::get('/works/stats', [\App\Http\Controllers\Api\Admin\WorkController::class, 'stats']);
});

// ================================
// RUTAS DE ADMINISTRACIÓN PROTEGIDAS
// ================================

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Perfil y sesión
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // CRUD Proyectos
    Route::prefix('projects')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'destroy']);
        Route::post('/{id}/toggle-featured', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'toggleFeatured']);
    });
    
    // CRUD Experiencia laboral
    Route::prefix('works')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\WorkController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Admin\WorkController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'destroy']);
        Route::post('/{id}/toggle-current', [\App\Http\Controllers\Api\Admin\WorkController::class, 'toggleCurrent']);
    });
    
    // Gestión de archivos
    Route::prefix('upload')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\UploadController::class, 'list']);
        Route::delete('/{filename}', [\App\Http\Controllers\Api\Admin\UploadController::class, 'delete']);
    });
});

// ================================
// RUTAS DE USUARIO AUTENTICADO
// ================================

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});