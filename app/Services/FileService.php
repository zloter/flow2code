<?php


namespace App\Services;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Imagick;

class FileService
{
    const DIRECTORY = 'movies/posters';

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @return string path to file
     */
    public function storeImage(UploadedFile $file): string
    {
        $image = new Imagick($file->getPathname());
        $proportions = $image->getImageGeometry();
        $width = $proportions['width'];
        $height = $proportions['height'];
        if ($width > $height) {
            $newHeight = 300;
            $newWidth = (300 / $height) * $width;
        } else {
            $newWidth = 300;
            $newHeight = (300 / $width) * $height;
        }
        $image->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
        $path = config('movies.backend_prefix_path') . config('movies.poster_path');;
        $this->createDirIfNotExist(storage_path($path));
        $fileName =  \Str::random(40) . '.' . $file->getClientOriginalExtension();
        $image->writeImage(storage_path($path . $fileName));
        return config('movies.poster_path') . $fileName;
    }

    /**
     * @param string $path
     */
    public function removeFile(string $path)
    {
        $storagePath = storage_path(config('movies.backend_prefix_path') . $path);
        unlink($storagePath);
    }

    /**
     * @param string $dir
     */
    private function createDirIfNotExist(string $dir): void
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}
