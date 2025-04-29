<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

//function saveImage(UploadedFile $uploaded_file, ?string $folder, int $width = 500) : string {
//    $file_ext   = $uploaded_file->getClientOriginalExtension();
//    $directory  = ($folder ? "$folder/" : 'images/') . date('FY');
//    $file_name  = (date('d') . rand(10000, 99999) . time()) . '.' . $file_ext;
//
//    Storage::disk('public')->makeDirectory($directory);
//
//    if (strtolower($file_ext) === 'svg') {
//        $file_path = $uploaded_file->storeAs($directory, $file_name, 'public');
//    } else {
//        $manager = new ImageManager(new Driver());
//        $image = $manager->read($uploaded_file->getRealPath());
//        $image->scale(width: $width);
//        $file_path  = $directory . '/' . $file_name;
//        $image->toPng()->save(storage_path('app/public/' . $file_path));
//    }
//    return $file_path;
//}


function saveImage(UploadedFile $uploaded_file, ?string $folder, int $width = 500): string {
    $file_ext   = $uploaded_file->getClientOriginalExtension();
    $directory  = $folder ?? 'images'; // just 'models', 'labels', etc.
    $file_name  = (date('d') . rand(10000, 99999) . time()) . '.' . $file_ext;

    Storage::disk('public')->makeDirectory($directory);

    $isImage = in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp']);

    if (strtolower($file_ext) === 'svg' || !$isImage) {
        return $uploaded_file->storeAs($directory, $file_name, 'public');
    }

    $manager = new ImageManager(new Driver());
    $image = $manager->read($uploaded_file->getRealPath());
    $image->scale(width: $width);
    $file_path = $directory . '/' . $file_name;
    $image->toPng()->save(storage_path('app/public/' . $file_path));

    return $file_path;
}



function deleteImage(string $path) : void {
    if ($path && Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
    }
}
