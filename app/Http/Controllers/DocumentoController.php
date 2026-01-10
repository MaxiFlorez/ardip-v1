<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:panel-carga')->only(['create', 'store', 'destroy']);
        $this->middleware('can:panel-consulta')->only(['index', 'download']);
    }

    /**
     * Lista documentos paginados
     */
    public function index()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->paginate(10);
        return view('documentos.index', compact('documentos'));
    }

    /**
     * Formulario de subida
     */
    public function create()
    {
        return view('documentos.create');
    }

    /**
     * Almacena el archivo y registra el documento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            // Validación por MIME type real, no solo extensión
            'archivo' => 'required|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:20480',
            'descripcion' => 'nullable|string',
        ]);

        $file = $request->file('archivo');
        
        // Guardar en storage/app/public/biblioteca
        $path = $file->store('biblioteca', 'public');

        $documento = Documento::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'archivo_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('documentos.index')
                         ->with('success', 'Documento subido correctamente.');
    }

    /**
     * Descarga el archivo
     */
    public function download(Documento $documento)
    {
        // 1. Validar acceso: solo panel-carga o el usuario que subió el documento
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->can('panel-carga') && $documento->user_id !== $user->id) {
            abort(403, 'No tienes permiso para descargar este documento.');
        }

        // 2. Verificar si el archivo físico existe
        if (!Storage::disk('public')->exists($documento->archivo_path)) {
            return back()->with('error', 'El archivo físico no se encuentra en el servidor.');
        }

        // 3. Generar un nombre limpio para la descarga (Ej: acta-allanamiento.pdf)
        $extension = pathinfo($documento->archivo_path, PATHINFO_EXTENSION);
        $nombreDescarga = Str::slug($documento->titulo) . '.' . $extension;

        // 4. Forzar descarga
        $filePath = Storage::disk('public')->path($documento->archivo_path);
        return response()->download($filePath, $nombreDescarga);
    }

    /**
     * Elimina archivo y registro
     */
    public function destroy(Documento $documento)
    {
        // Borrar el archivo físico si existe
        if (Storage::disk('public')->exists($documento->archivo_path)) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        // Borrar el registro de la base de datos
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}