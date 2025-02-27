<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


function uploadImage($name, $title)
{
//    if (is_base64_string($name)) {
//        // Convert base64 to UploadedFile
//        $name = convertBase64ToUploadedFile($name);
//    }
    $path = $name->store($title, [
        'disk' => 'uploads'
    ]);

    return $path;
}

// I want function to store multiple images for attachment table

//function uploadImages($names, $title)
//{
//    $paths = [];
//
//    // Ensure $files is always an array
//    if (!is_array($names)) {
//        $files = [$names];
//    } else {
//        $files = $names;
//    }
//
//    foreach ($files as $file) {
//        if ($file) { // Ensure the file is valid
////            if (is_base64_string($file)) {
////                // Convert base64 to UploadedFile
////                $file = convertBase64ToUploadedFile($file);
////            }
//            $path = $file->store($title, [
//                'disk' => 'uploads'
//            ]);
//            $paths[] = $path;
//        }
//    }
//
//    return $paths;
//}


function upload_image($image, $folder): string
{
    $img = Image::make($image);
    $mime = $img->mime();
    $mim = explode('/', $mime)[1];
    $extension = '.' . $mim;
    $rand = rand(10, 100000);
    $name = $rand . time() . $extension;
    $upload_path = 'uploads/' . $folder;
    $image_url = $upload_path . '/' . $name;

    if (!file_exists(public_path($upload_path))) {
        mkdir(public_path($upload_path), 775, true);
    }

    $img->save(public_path($image_url));
    return $image_url;
}

function upload_video($video, $file)
{
    $videoName = time() . rand(1, 9999) . '.' . $video->getClientOriginalExtension();
    $path = $video->storeAs($file, $videoName, 'uploads');
    return 'uploads/' . $path;
}
function uploadImages($names, $title)
{
    $paths = [];

    // Ensure $files is always an array
    $files = is_array($names) ? $names : [$names];

    foreach ($files as $file) {
        if ($file instanceof \Illuminate\Http\UploadedFile) { // Check if it's an UploadedFile
            $path = $file->store($title, [
                'disk' => 'uploads'
            ]);
            $paths[] = $path;
        } elseif (is_string($file)) {
            // If it's a string, assume it's already a file path, you might want to handle this case
            $paths[] = $file; // or implement logic to copy/move the file if necessary
        } else {
            // Handle unexpected types if needed
            throw new InvalidArgumentException('Invalid file type provided.');
        }
    }

    return $paths;
}

function delete_image($path)
{
    if ($path != "uploads/default.jpg") {
        File::delete(getImageLink($path));
    }

}

function convertStringToUploadedFile($filePath): UploadedFile
{
    // Ensure the file exists
    if (!File::exists($filePath)) {
        throw new Exception("File not found: " . $filePath);
    }

    // Get file info (use File facade to handle file data)
    $file = new UploadedFile(
        $filePath,
        File::name($filePath),        // Filename without extension
        File::mimeType($filePath),    // MIME type
        null,
        true                          // Mark the file as test file (optional)
    );

    return $file;
}

function getImageLink($path)
{
    return asset("uploads/$path");
}

function deleteImageFromDisk($disk, $old_image)
{

    if (is_array($old_image)) {

        foreach ($old_image as $image) {
            Storage::disk($disk)->delete($image);
        }
    } else {

        Storage::disk($disk)->delete($old_image);
    }
}

function upload_base64_images($images, $folder): array
{
    $image_urls = [];
    foreach ($images as $key => $image) {
        $image_urls[] = upload_image_base64($image, $folder);
    }
    return $image_urls;
}

function upload_image_base64($image, $folder): ?string
{
    if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
        // preg_match('/^data:image\/(\w+);base64,/', $image, $type);
        $image = substr($image, strpos($image, ',') + 1);
        $type = strtolower($type[1]);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '_' . time() . '.' . $type;
        $upload_path = 'uploads/' . $folder;
        $image_url = $upload_path . '/' . $imageName;
        if (!file_exists($upload_path)) {
            mkdir(public_path($upload_path), 775, true);
        }
        file_put_contents($image_url, base64_decode($image));
        return $image_url;
    } else {
        return null;
    }
}

function upload_images($images, $folder): array
{
    $image_urls = [];
    foreach ($images as $key => $image) {
        $image_urls[] = upload_image($image, $folder);
    }
    return $image_urls;
}


function isPDF($file): bool
{
    return $file->getMimeType() === 'application/pdf';
}

function isImage($file): bool
{
    return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif']);
}


function upload_pdf($pdf, $file): string
{
    $pdfName = time() . rand(1, 9999) . '.' . $pdf->getClientOriginalExtension();
    $path = $pdf->storeAs($file, $pdfName, 'uploads');
    return 'uploads/' . $path;
}

function delete_video($path): void
{
    if ($path != "uploads/default.jpg") {
        File::delete(public_path($path));
    }
}

function is_base64_string($string)
{
    $string = trim($string);
    return preg_match('/^data:image\/(\w+);base64,/', $string) ||
        (base64_decode($string, true) !== false && preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $string));
}

function is_video($file): bool
{
    if ($file instanceof UploadedFile) {
        return str_starts_with($file->getMimeType(), 'video/');
    }
    $videoExtensions = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'webm', 'wmv'];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return in_array($extension, $videoExtensions, true);
}


