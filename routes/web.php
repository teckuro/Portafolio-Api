<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para servir archivos de storage
Route::get('storage/{path}', function ($path) {
    $fullPath = 'assets/uploads/' . $path;
    
    if (!Storage::disk('public')->exists($fullPath)) {
        abort(404);
    }
    
    $file = Storage::disk('public')->get($fullPath);
    $mimeType = Storage::disk('public')->mimeType($fullPath);
    
    return response($file, 200, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');
