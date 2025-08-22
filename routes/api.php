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
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta de prueba para verificar que la API funciona
Route::get('/test', function () {
    return response()->json([
        'message' => 'API del Portafolio funcionando correctamente',
        'status' => 'success',
        'timestamp' => now()->toISOString()
    ]);
});

// Ruta temporal para ejecutar comandos de corrección de imágenes
Route::post('/fix-images', function () {
    try {
        // Ejecutar comando para generar imágenes placeholder
        \Artisan::call('images:generate-placeholders');
        $generateOutput = \Artisan::output();
        
        // Ejecutar comando para corregir URLs
        \Artisan::call('images:fix-urls');
        $fixOutput = \Artisan::output();
        
        // Limpiar cache
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Comandos ejecutados correctamente',
            'generate_output' => $generateOutput,
            'fix_output' => $fixOutput,
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error ejecutando comandos: ' . $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
});

// Ruta para ejecutar comandos específicos en Railway
Route::post('/execute-command', function (Request $request) {
    try {
        $command = $request->input('command');
        
        if (!$command) {
            return response()->json([
                'success' => false,
                'message' => 'Comando no especificado'
            ], 400);
        }
        
        $exitCode = \Artisan::call($command);
        $output = \Artisan::output();
        
        return response()->json([
            'success' => true,
            'command' => $command,
            'exit_code' => $exitCode,
            'output' => $output,
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error ejecutando comando: ' . $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
});

// Ruta de prueba para servir un archivo específico
Route::get('/test-file', function () {
    $path = 'assets/uploads/projects/placeholder1.svg';
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

// Ruta de prueba alternativa para servir archivos
Route::get('/serve-file', function () {
    $path = 'assets/uploads/projects/placeholder1.svg';
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

// Rutas específicas para imágenes placeholder (que sabemos que funcionan)
Route::get('/placeholder/projects/1', function () {
    $path = 'assets/uploads/projects/placeholder1.svg';
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/placeholder/projects/2', function () {
    $path = 'assets/uploads/projects/placeholder2.svg';
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/placeholder/works/1', function () {
    $path = 'assets/uploads/works/placeholder1.svg';
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

// Rutas alternativas para servir archivos (usando patrón diferente)
Route::get('/images/projects/{filename}', function ($filename) {
    $path = 'assets/uploads/projects/' . $filename;
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/images/works/{filename}', function ($filename) {
    $path = 'assets/uploads/works/' . $filename;
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/images/temp/{filename}', function ($filename) {
    $path = 'assets/uploads/temp/' . $filename;
    
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $path
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
});

// Ruta dinámica para servir archivos (DEBE ir DESPUÉS de las rutas específicas)
Route::get('/files/{path}', function ($path) {
    // La ruta llega como /files/projects/filename.png
    // Necesitamos construir la ruta completa para storage
    $fullPath = 'assets/uploads/' . $path;
    
    if (!Storage::disk('public')->exists($fullPath)) {
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado: ' . $fullPath
        ], 404);
    }
    
    try {
        $file = Storage::disk('public')->get($fullPath);
        $mimeType = Storage::disk('public')->mimeType($fullPath);
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Error al leer el archivo'
            ], 500);
        }
        
        return response($file, 200, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
            'Access-Control-Allow-Origin' => '*',
            'Content-Length' => Storage::disk('public')->size($fullPath),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al servir el archivo: ' . $e->getMessage()
        ], 500);
    }
})->where('path', '.*');

// Ruta de diagnóstico para verificar la base de datos
Route::get('/debug', function () {
    try {
        // Verificar conexión a la base de datos
        \DB::connection()->getPdo();
        
        // Verificar si la tabla works existe
        $tableExists = \Schema::hasTable('works');
        
        // Contar registros en la tabla works
        $worksCount = $tableExists ? \App\Models\Work::count() : 0;
        
        return response()->json([
            'database_connected' => true,
            'works_table_exists' => $tableExists,
            'works_count' => $worksCount,
            'database_name' => \DB::connection()->getDatabaseName(),
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'database_connected' => false,
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
});

// Rutas públicas para el frontend (sin autenticación)
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/featured', [ProjectController::class, 'featured']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

Route::get('/works', [WorkController::class, 'index']);
Route::get('/works/current', [WorkController::class, 'current']);
Route::get('/works/{id}', [WorkController::class, 'show']);

// Rutas públicas de administración (solo lectura, sin autenticación)
Route::prefix('admin')->group(function () {
    // Rutas de solo lectura para el panel de administración
    Route::get('/projects/stats', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'stats']);
    Route::get('/projects', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'index']);
    Route::get('/projects/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'show']);

    Route::get('/works/stats', [\App\Http\Controllers\Api\Admin\WorkController::class, 'stats']);
    Route::get('/works', [\App\Http\Controllers\Api\Admin\WorkController::class, 'index']);
    Route::get('/works/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'show']);
});

// Rutas públicas de autenticación
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas públicas de upload (sin autenticación)
Route::prefix('admin')->group(function () {
    Route::post('/upload', [\App\Http\Controllers\Api\Admin\UploadController::class, 'upload']);
    Route::get('/upload', [\App\Http\Controllers\Api\Admin\UploadController::class, 'list']);
    Route::delete('/upload/{filename}', [\App\Http\Controllers\Api\Admin\UploadController::class, 'delete']);
    Route::get('/upload/health', [\App\Http\Controllers\Api\Admin\UploadController::class, 'health']);
});

// Rutas protegidas de administración (requieren autenticación)
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Gestión de proyectos (CRUD)
    Route::post('/projects', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'store']);
    Route::put('/projects/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'destroy']);
    Route::post('projects/{id}/toggle-featured', [\App\Http\Controllers\Api\Admin\ProjectController::class, 'toggleFeatured']);

    // Gestión de experiencia laboral (CRUD)
    Route::post('/works', [\App\Http\Controllers\Api\Admin\WorkController::class, 'store']);
    Route::put('/works/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'update']);
    Route::delete('/works/{id}', [\App\Http\Controllers\Api\Admin\WorkController::class, 'destroy']);
    Route::post('works/{id}/toggle-current', [\App\Http\Controllers\Api\Admin\WorkController::class, 'toggleCurrent']);
});

// Rutas públicas para el frontend (con prefijo portfolio como alternativa)
Route::prefix('portfolio')->group(function () {
    // Proyectos
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/featured', [ProjectController::class, 'featured']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);

    // Experiencia laboral
    Route::get('/works', [WorkController::class, 'index']);
    Route::get('/works/current', [WorkController::class, 'current']);
    Route::get('/works/{id}', [WorkController::class, 'show']);
});
