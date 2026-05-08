<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadFileResolucion($filename) {
        // Check if file exists
        $realPath = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), 'uploads', $filename));
        if (!file_exists($realPath)) {
            abort(404, 'File not found.');
        }

        // Get file path
        $path = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), 'uploads', $filename));

        // Get file mime type
        $mimeType = mime_content_type($path);

        // Download file
        return response()->download($path, $filename, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function downloadFileAcuerdo($filename) {
        // Check if file exists
        $realPath = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), 'facturacion/acuerdos', $filename));
        if (!file_exists($realPath)) {
            abort(404, 'File not found.');
        }

        // Get file path
        $path = join(DIRECTORY_SEPARATOR, array(Storage::path('public'), 'facturacion/acuerdos', $filename));

        // Get file mime type
        $mimeType = mime_content_type($path);

        // Download file
        return response()->download($path, $filename, [
            'Content-Type' => $mimeType,
        ]);
    }
}
