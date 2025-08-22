<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ServeStorage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si la ruta es para archivos de storage
        if (str_starts_with($request->path(), 'storage/')) {
            $path = str_replace('storage/', '', $request->path());
            
            // Verificar si el archivo existe
            if (Storage::disk('public')->exists($path)) {
                $file = Storage::disk('public')->get($path);
                $mimeType = Storage::disk('public')->mimeType($path);
                
                return response($file, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
            
            // Si el archivo no existe, devolver 404
            return response('File not found', 404);
        }

        return $next($request);
    }
}
