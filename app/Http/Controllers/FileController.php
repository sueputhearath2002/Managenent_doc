<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class FileController extends Controller
{
    public function downloadFolder()
    {
        $folderPath = public_path('uploads'); // The folder to zip
        $zipFileName = 'files.zip'; // Name of the zip file
        $zipFilePath = public_path($zipFileName);

        // Create a new zip archive
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Scan folder and add files to the zip
            $files = glob($folderPath . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    $zip->addFile($file, basename($file));
                }
            }

            $zip->close();
        } else {
            return response()->json(['message' => 'Could not create zip file'], 500);
        }

        // Download the zip file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
