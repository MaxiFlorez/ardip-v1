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
        // Define permisos: Solo Carga/Admin puede subir y borrar. Consulta puede ver y bajar.
        $this->middleware('can:panel-carga')->only(['create', 'store', 'destroy']);
        $this->middleware('can:panel-consulta')->only(['index', 'download']);
    }

    /**
     * Lista documentos paginados
     */
    public function index()
    {
        $this->authorize('panel-consulta');
        $documentos = Documento::orderBy('created_at', 'desc')->paginate(10);
        return view('documentos.index', compact('documentos'));
    }

    /**
     * Formulario de subida
     */
    public function create()
    {
        $this->authorize('panel-carga');
        return view('documentos.create');
    }

    /**
     * Almacena el archivo y registra el documento
     */
    public function store(Request $request)
    {
        $this->authorize('panel-carga');
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240', // Máx 10MB
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
        $this->authorize('panel-consulta');
        // 1. Verificar si el archivo físico existe
        if (!Storage::disk('public')->exists($documento->archivo_path)) {
            return back()->with('error', 'El archivo físico no se encuentra en el servidor.');
        }

        // 2. Generar un nombre limpio para la descarga (Ej: acta-allanamiento.pdf)
        $extension = pathinfo($documento->archivo_path, PATHINFO_EXTENSION);
        $nombreDescarga = Str::slug($documento->titulo) . '.' . $extension;

        // 3. Forzar descarga
        $filePath = Storage::disk('public')->path($documento->archivo_path);
        return response()->download($filePath, $nombreDescarga);
    }

    /**
     * Elimina archivo y registro
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('panel-carga');
        // Borrar el archivo físico si existe
        if (Storage::disk('public')->exists($documento->archivo_path)) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        // Borrar el registro de la base de datos
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}