<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'descripcion' => 'nullable|string',
        ]);

        $file = $request->file('archivo');
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
     * Forzamos la descarga usando el Facade Storage
     */
    public function download(Documento $documento)
    {
        return Storage::disk('public')->download($documento->archivo_path, $documento->titulo);
    }

    /**
     * Elimina archivo y registro
     */
    public function destroy(Documento $documento)
    {
        if (Storage::disk('public')->exists($documento->archivo_path)) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}
