<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    /**
     * Sube un archivo al disco especificado y retorna la ruta.
     *
     * @param UploadedFile $file El archivo a subir
     * @param string $folder La carpeta donde se guardará (ej: 'fotos_personas')
     * @param string $disk El disco de almacenamiento (por defecto 'public')
     * @return string La ruta del archivo guardado
     */
    public function uploadFile(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        return $file->store($folder, $disk);
    }

    /**
     * Elimina un archivo del disco si existe.
     *
     * @param string|null $path La ruta del archivo a eliminar
     * @param string $disk El disco de almacenamiento (por defecto 'public')
     * @return bool True si se eliminó, false si no existía
     */
    public function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Actualiza un archivo: elimina el anterior (si existe) y sube el nuevo.
     *
     * @param UploadedFile $newFile El nuevo archivo a subir
     * @param string|null $oldPath La ruta del archivo anterior a eliminar
     * @param string $folder La carpeta donde se guardará el nuevo archivo
     * @param string $disk El disco de almacenamiento (por defecto 'public')
     * @return string La ruta del nuevo archivo guardado
     */
    public function updateFile(UploadedFile $newFile, ?string $oldPath, string $folder, string $disk = 'public'): string
    {
        // Eliminar archivo anterior si existe
        $this->deleteFile($oldPath, $disk);

        // Subir nuevo archivo
        return $this->uploadFile($newFile, $folder, $disk);
    }
}
