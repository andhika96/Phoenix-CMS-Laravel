<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class Helper
{
    public static function generateUniqueSlug(
        string $slug,
        $model,
        $currentModelId = null
    ): string {
        throw_if(
            !is_subclass_of($model, Model::class),
            new \InvalidArgumentException('Invalid model passed')
        );

        $baseSlug = $slug; // Store the original slug without count
        $count = 1;

        $query = $model::where('slug', $slug);

        // Exclude the current model's ID from the uniqueness check
        if ($currentModelId !== null) {
            $query->where('id', '!=', $currentModelId);
        }

        $existingCount = $query->count();

        while ($existingCount > 0) {
            // Append the count to the original slug
            $slug = $baseSlug . '-' . $count;

            // Re-run the query to check the new slug's uniqueness
            $existingCount = $model::where('slug', $slug)
                ->where('id', '!=', $currentModelId) // Exclude current model's ID
                ->count();

            $count++;
        }

        return $slug;
    }

    public static function getURI($data_path): string
    {
        $baseURL = env('APP_URL', 'http://localhost') . "/" . "storage/";
        return $baseURL . $data_path;
    }

    public static function getFileType($file): string
    {
        $pos  = strpos($file, ';');
        return explode(':', substr($file, 0, $pos))[1];
    }

    public static function decodeBase64($base64, $path = "media", $filename = "tmp", $ext = null): string
    {
        // Check if Intervention Image is available
        if (class_exists('Intervention\Image\Laravel\Facades\Image')) {
            return self::convertAndCompressWithIntervention($base64, $path, $filename, $ext);
        } else {
            // Fall back to original code
            return self::decodeBase64Original($base64, $path, $filename, $ext);
        }
    }

    private static function convertAndCompressWithIntervention($base64, $path, $filename, $ext, $maxSize = 0.5)
    {
        $filetype = self::getFileType($base64);
        $extension = $ext ?? explode('/', $filetype)[1];
        $newFilename = "{$filename}-" . date('YmdHis') . ".webp";
        $newPath = $path . '/' . $newFilename;

        $data = substr($base64, strpos($base64, ',') + 1);
        $data = base64_decode($data);

        // Save the original image
        Storage::put("public/{$newPath}", $data, 'public');

        // Convert the image to WebP format
        $image = \Intervention\Image\Laravel\Facades\Image::read($data);

        if ($image->width() > 2048 || $image->height() > 2048) {
            $image->scale(width:2048, height:2048);
        }
    
        $image->toWebp();

        // Compress the image until its size is below or equal to 0.5MB
        $maxFileSize = $maxSize * 1024 * 1024; // 0.5MB in bytes by default
        $quality = 90; // Initial quality

        do {
            $image->save(storage_path("app/public/{$newPath}"), $quality);
            $fileSize = filesize(storage_path("app/public/{$newPath}"));
            $quality -= 5;
        } while ($fileSize > $maxFileSize && $quality >= 50);

        Storage::delete("public/{$path}/{$filename}.{$extension}");

        return $newFilename;
    }

    private static function decodeBase64Original($base64, $path, $filename, $ext)
    {
        $filetype = self::getFileType($base64);
        $extension = $ext ?? explode('/', $filetype)[1];
        $newFilename = "{$filename}-" . date('YmdHis') . "." . $extension;
        $newPath = $path . '/' . $newFilename;

        if (
            preg_match('/^data:image\/(\w+);base64,/', $base64)
            || preg_match('/^data:application\/(\w+);base64,/', $base64)
        ) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $data = base64_decode($data);

            // Save the original image
            Storage::put("public/{$newPath}", $data, 'public');

            return $newFilename;
        }
    }

    public static function imgTob64($img): string
    {
        // $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = pathinfo($img, PATHINFO_EXTENSION);
        // throw_unless(
        //     in_array($extension, $allowed),
        //     "File type not supported"
        // );

        $file = file_get_contents($img);
        $b64 = base64_encode($file);
        return 'data:image/' . $extension . ';base64,' . $b64;
    }

    public static function encodeBase64(string $imagePath): string
    {
        $img = "";

        // Check if the file exists
        if (file_exists($imagePath)) {
            // Read the contents of the image file
            $imageData = file_get_contents($imagePath);

            // Convert the image data to base64
            $base64 = base64_encode($imageData);

            // Construct the base64 data URI
            $img = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $base64;
        }

        return $img;
    }

    public static function formatDate($date, $hour = true): string
    {
        $format = $hour ? 'j/M/Y H:i' : 'j/M/Y';
        return (Carbon::parse($date))->format($format);
    }

    public static function formatOptions(array $options): array
    {
        return array_map(function ($item) {
            return ucwords(strtolower($item));
        }, $options);
    }

    public static function formatPrice($price): string
    {
        return "Rp. " . number_format($price ?? 0, 0, ',', '.');
    }


    public static function encryptData($data): string
    {
        $encrypter = self::getEncrypter();

        return $encrypter->encrypt($data);
    }

    public static function decryptData($data): string
    {
        $encrypter = self::getEncrypter();

        return $encrypter->decrypt($data);
    }

    public static function maybeUnserialize($data)
    {
        if (self::isSerialized($data)) { // Don't attempt to unserialize data that wasn't serialized going in.
            return @unserialize(trim($data));
        }

        return $data;
    }

    public static function isSerialized($data, $strict = true)
    {
        // If it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (!str_contains($data, '"')) {
                    return false;
                }
                // Or else fall through.
            case 'a':
            case 'O':
            case 'E':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
        }
        return false;
    }

    private static function getEncrypter(): Encrypter
    {
        return new Encrypter(env("EBY_SALT", "Eby:HANDOVER2024"), 'AES-256-CBC');
    }

    public static function applyWatermark($imagePath, $wmImg = '')
    {
        try {
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            $watermarkedImagePath = "storage/wm/{$imagePath}";
            $webpWatermarkedImagePath = str_replace(".{$extension}", ".webp", $watermarkedImagePath);

            
            if (file_exists(public_path($webpWatermarkedImagePath))) {
                return $webpWatermarkedImagePath; // Watermarked image already exists, return its path
            }

            // Check if the image file exists
            if (!file_exists(public_path("storage/{$imagePath}"))) {
                return null; // File doesn't exist, return null
            }

            if (empty($wmImg)) {
                $watermarkPath = public_path('img/logos/Logo Handover-05.png');
            }

            // Extract the directory path from the image path
            $imageDirectory = dirname($webpWatermarkedImagePath);

            // Create the directory for the watermarked image if it doesn't exist
            $watermarkDirectory = public_path($imageDirectory);
            if (!file_exists($watermarkDirectory)) {
                mkdir($watermarkDirectory, 0777, true);
            }

            // Load the user's image
            $img = Image::read(public_path("storage/{$imagePath}"));

            // Load the watermark image
            $watermark = Image::read($watermarkPath);

            // Define the maximum watermark size as a percentage of the main image's dimensions
            $maxWatermarkSizePercent = 20; // 20% of the main image's dimensions

            // Calculate the maximum dimensions of the watermark
            $maxWatermarkWidth = $img->width() * $maxWatermarkSizePercent / 100;
            $maxWatermarkHeight = $img->height() * $maxWatermarkSizePercent / 100;

            // Resize the watermark while maintaining aspect ratio and ensuring it fits within the maximum dimensions
            $watermark->scale($maxWatermarkWidth, $maxWatermarkHeight);

            // Calculate the position to center the watermark on the main image
            // $watermarkX = ($img->width() - $watermark->width()) / 2;
            // $watermarkY = ($img->height() - $watermark->height()) / 2;
            $watermarkX = 0;
            $watermarkY = 0;

            // Overlay watermark onto the main image with transparency
            $img->toWebp()->save(public_path($webpWatermarkedImagePath));

            $webPimg = Image::read(public_path($webpWatermarkedImagePath));
            $webPimg->place($watermark, 'center', $watermarkX, $watermarkY, 50);

            // Save the resulting watermarked image
            $webPimg->save(public_path($webpWatermarkedImagePath));

            return $webpWatermarkedImagePath;
        } catch (\Throwable $th) {
            return null;
        }
        // error_reporting(E_ALL & ~E_WARNING);
        // Log::debug($imagePath);
        // Check if the watermarked image already exists

    }

    public static function isWebpAnimated($src)
    {
        $webpContents = file_get_contents($src);
        $where = strpos($webpContents, "ANMF");
        if ($where !== FALSE) {
            // animated
            $isAnimated = true;
        } else {
            // non animated
            $isAnimated = false;
        }
        return $isAnimated;
    }
}
