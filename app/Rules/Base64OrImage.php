<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64OrImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is a valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $value)) {
            $imageData = substr($value, strpos($value, ',') + 1);
            $decodedImage = base64_decode($imageData, true);
            if ($decodedImage && $this->isValidImage($decodedImage)) {
                return;
            }
        }
        // If not a base64 string, check if it's a valid uploaded file
        if (!is_string($value) && !is_a($value, 'Illuminate\Http\UploadedFile')) {
            $fail('The ' . $attribute . ' must be either a valid base64 encoded image or an uploaded image file.');
        }
    }

    private function isValidImage($data)
    {
        // Write your validation logic here
        // For example, you can use getimagesize or imagecreatefromstring
        // Here's a simple example using getimagesize:
        $imageInfo = getimagesizefromstring($data);

        // Compute the size of the image data
        $fileSize = strlen($data);

        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

        return $imageInfo !== false && $fileSize <= $maxFileSize;
    }
}
