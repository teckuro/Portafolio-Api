<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    private $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    private $uploadPath = 'assets/uploads';

    /**
     * Subir una imagen
     */
    public function upload(Request $request)
    {
        try {
            // Validar la solicitud
            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB
                'category' => 'required|string|in:projects,works,temp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'filename' => '',
                        'url' => '',
                        'path' => ''
                    ],
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 400);
            }

            $file = $request->file('image');
            $category = $request->input('category', 'temp');

            // Validar tipo de archivo
            if (!in_array($file->getMimeType(), $this->allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'filename' => '',
                        'url' => '',
                        'path' => ''
                    ],
                    'message' => 'Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WebP'
                ], 400);
            }

            // Validar tamaño
            if ($file->getSize() > $this->maxFileSize) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'filename' => '',
                        'url' => '',
                        'path' => ''
                    ],
                    'message' => 'El archivo es demasiado grande. Máximo 5MB permitido'
                ], 400);
            }

            // Generar nombre único
            $extension = $file->getClientOriginalExtension();
            $filename = $category . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Ruta de almacenamiento
            $path = $this->uploadPath . '/' . $category;
            $fullPath = $path . '/' . $filename;

            // Crear directorio si no existe
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            // Guardar archivo
            $stored = Storage::disk('public')->putFileAs($path, $file, $filename);

            if (!$stored) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'filename' => '',
                        'url' => '',
                        'path' => ''
                    ],
                    'message' => 'Error al guardar el archivo en el servidor'
                ], 500);
            }

            // Verificar que el archivo se guardó correctamente
            if (!Storage::disk('public')->exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'filename' => '',
                        'url' => '',
                        'path' => ''
                    ],
                    'message' => 'El archivo no se guardó correctamente'
                ], 500);
            }

            // Generar URL pública con la URL base correcta
            $url = $this->generatePublicUrl($fullPath);

            return response()->json([
                'success' => true,
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                    'path' => $fullPath,
                    'size' => Storage::disk('public')->size($fullPath)
                ],
                'message' => 'Imagen subida exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [
                    'filename' => '',
                    'url' => '',
                    'path' => ''
                ],
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar archivos subidos
     */
    public function list(Request $request)
    {
        try {
            $category = $request->input('category', 'temp');
            $path = $this->uploadPath . '/' . $category;

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No hay archivos en esta categoría'
                ]);
            }

            $files = Storage::disk('public')->files($path);
            $fileList = [];

            foreach ($files as $file) {
                $filename = basename($file);
                $fileList[] = [
                    'filename' => $filename,
                    'url' => $this->generatePublicUrl($file),
                    'path' => $file,
                    'size' => Storage::disk('public')->size($file),
                    'modified' => Storage::disk('public')->lastModified($file)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $fileList,
                'message' => 'Archivos listados exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al listar archivos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un archivo
     */
    public function delete($filename)
    {
        try {
            // Buscar el archivo en todas las categorías
            $categories = ['projects', 'works', 'temp'];
            $fileFound = false;
            $filePath = '';

            foreach ($categories as $category) {
                $path = $this->uploadPath . '/' . $category . '/' . $filename;
                if (Storage::disk('public')->exists($path)) {
                    $fileFound = true;
                    $filePath = $path;
                    break;
                }
            }

            if (!$fileFound) {
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo no encontrado'
                ], 404);
            }

            // Eliminar archivo
            $deleted = Storage::disk('public')->delete($filePath);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el archivo'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado del servicio de upload
     */
    public function health()
    {
        try {
            // Verificar que el directorio base existe
            $basePath = $this->uploadPath;
            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            // Verificar que las subcarpetas existen
            $categories = ['projects', 'works', 'temp'];
            foreach ($categories as $category) {
                $path = $basePath . '/' . $category;
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Servicio de upload funcionando correctamente',
                'data' => [
                    'base_path' => $basePath,
                    'categories' => $categories,
                    'max_file_size' => $this->maxFileSize,
                    'allowed_types' => $this->allowedTypes
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servicio de upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar URL pública correcta según el environment
     */
    private function generatePublicUrl($path)
    {
        // Forzar URL de Railway para producción
        $baseUrl = 'https://api-portafolio.up.railway.app';
        
        // Solo usar localhost en desarrollo local
        if (app()->environment('local') && 
            !str_contains(request()->getHost(), 'railway') &&
            !str_contains(request()->getHost(), 'vercel')) {
            $baseUrl = config('app.url');
        }
        
        // Construir la URL completa usando la ruta de API
        $url = rtrim($baseUrl, '/') . '/api/files/' . $path;
        
        return $url;
    }
}
