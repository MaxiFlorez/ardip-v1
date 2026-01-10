<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Http\Requests\StoreDocumentoRequest;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    use HandlesFileUploads;
    public function __construct()
    {
        $this->middleware('can:operativo-escritura')->only(['create', 'store', 'destroy']);
        $this->middleware('can:acceso-operativo')->only(['index', 'download']);
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
    public function store(StoreDocumentoRequest $request)
    {
        $validated = $request->validated();
        $file = $request->file('archivo');

        // Guardar archivo usando el trait
        $path = $this->uploadFile($file, 'biblioteca');

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
        // Borrar archivo físico usando el trait
        $this->deleteFile($documento->archivo_path);

        // Borrar el registro de la base de datos
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}