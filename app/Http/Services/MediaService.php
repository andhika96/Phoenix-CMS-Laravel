<?php

namespace App\Services;

use App\Contracts\IMediaService;
use App\Helper\Helper;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductBrandVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MediaService implements IMediaService
{
    public function __construct(
        public array $data = []
    ) {
    }

    public function create(array $attr): ?Media
    {
        throw_if(!$attr['file'], new InvalidArgumentException("Missing attribute file"));

        $file = $attr['file'];

        if (is_string($attr['file'])) {
            if (!preg_match('/^data:image\/(\w+);base64,/', $file)) {
                return new Media();
            }

            $imageData = substr($file, strpos($file, ',') + 1);
            $decodedImage = base64_decode($imageData, true);

            if (!$decodedImage || !$this->isValidImage($decodedImage)) {
                return new Media();
            }

            $uploadedFile = Helper::decodeBase64($file, "media", $attr['key']);
            $filetype   = Helper::getFileType($file);
            $extension  = explode('/', $filetype)[1];
        } else {
            $uploadedFile =  $this->uploadFile($file);
            $extension = $file->extension();
        }

        $data = new Media(array(
            'key' => $attr['key'],
            'ext' => $extension,
            'path' => $uploadedFile
        ));

        if ($data->save()) {
            return $data;
        }
    }

    public function createMany(array $data): ?array
    {
        foreach ($data as $key => $item) {
            $file = $item['file'];
            if (is_string($item['file'])) {
                if (!preg_match('/^data:image\/(\w+);base64,/', $file)) {
                    $this->data[] = new Media();
                }
    
                $imageData = substr($file, strpos($file, ',') + 1);
                $decodedImage = base64_decode($imageData, true);

                if (!$decodedImage || !$this->isValidImage($decodedImage)) {
                    $this->data[] =  new Media();
                }

                $uploadedFile = Helper::decodeBase64($file, "media", $item['key']);
                $filetype   = Helper::getFileType($file);
                $extension  = explode('/', $filetype)[1];
            } else {
                $uploadedFile =  $this->uploadFile($file);
                $extension = $file->extension();
            }

            $media = Media::create([
                'key' => $item['key'],
                'ext' => $extension,
                'path' => $uploadedFile,
            ]);
            $this->data[] = $media;
        }

        return $this->data;
    }

    public function findById($id): ?Media
    {
        return Media::find($id);
    }

    public function findByIndexes(array $indexes, bool $any, $limit)
    {
        $table = (new Media)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = Product::query();

        foreach ($indexes as $column => $value) {
            if (in_array($column, $columns)) {
                $any
                    ? $query->where($column, 'like', "%{$value}%")
                    : $query->orWhere($column, 'like', "%{$value}%");
            }
        }

        if ($limit == -1) {
            $results = $query->get();
        } else {
            $perPage = $limit;
            $currentPage = request()->get('page', 1);
            $results = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        return $results;
    }

    public function sync(array $mediaData, Model $mediable): void
    {
        $this->data = [];

        foreach ($mediaData as $item) {
            $file = $item['file'];

            if (is_string($file) && !preg_match('/^data:image\/(\w+);base64,/', $file)) {
                $media = Media::find($item['id']);
                if (!$media) continue;
                $this->data[] = $media;
            }

            $imageData = substr($file, strpos($file, ',') + 1);
            $decodedImage = base64_decode($imageData, true);

            if (!$decodedImage || !$this->isValidImage($decodedImage)) {
                continue; // Skip invalid base64 images
            }

            if (is_string($file)) {
                $uploadedFile = Helper::decodeBase64($file, "media", $item['key']);
                $extension = Helper::getFileType($file);
            } else {
                $uploadedFile = $this->uploadFile($file);
                $extension = $file->extension();
            }

            $mediaAttributes = [
                'key' => $item['key'],
                'ext' => $extension,
                'path' => $uploadedFile,
            ];

            if (!empty($item['id'])) {
                // Update existing media
                $media = Media::updateOrCreate(array('id' => $item['id']), $mediaAttributes);
            } else {
                // Create new media
                $media = Media::create($mediaAttributes);
            } 
            $this->data[] = $media;
        }

        // Sync media with the mediable model
        $this->syncMedia(collect($this->data ?? []), $mediable);
    }

    /**
     * List all user.
     */
    public function list(): Collection
    {
        return Product::all();
    }

    public function delete(Media $media): ?bool
    {
        $isSuccess = false;
        DB::transaction(function () use ($media, &$isSuccess) {
            $isSuccess = $media->delete();
        });

        return $isSuccess;
    }

    public function deleteMany(array $data): int
    {
        $deletedData = 0;

        DB::transaction(function () use ($data, &$deletedData) {
            $mediaToDelete = Media::whereIn('id', $data)->get();
            $mediaToDelete->each(function (Media $item) use (&$deletedData) {
                $this->deleteFile($item->path);
                if ($item->delete()) {
                    $deletedData++;
                }
            });
        });

        return $deletedData;
    }

    protected function uploadFile($file, $type = "media")
    {
        if ($file) {
            $fileName = uniqid("{$type}_") . '.' . $file->getClientOriginalExtension();
            $file->storeAs("{$type}", $fileName, 'public');

            return $fileName;
        }

        return null;
    }

    protected function deleteFile($file, $type = "media")
    {
        if ($file) {
            Storage::disk('public')->delete("{$type}/{$file}");
        }
    }

    /**
     * Synchronize media for the model.
     *
     * @param Collection $mediaArgs The array or collection of media IDs or Media objects to synchronize
     * @return void
     */
    public function syncMedia(Collection $newMedia, Model $mediabe): void
    {
        // Normalize input to an array of media IDs
        $mediaIds = $this->normalizeMediaArgs($newMedia);

        // Get the IDs of existing media related to the model
        $existingMediaIds = $mediabe->media()->pluck('id')->toArray();

        // Determine media IDs to attach and detach
        $mediaToAttach = array_diff($mediaIds, $existingMediaIds);
        $mediaToDetach = array_diff($existingMediaIds, $mediaIds);
        // dd($newMedia);
        // Attach new media
        foreach ($mediaToAttach as $mediaId) {
            $mediabe->media()->save($newMedia->where('id', $mediaId)->first());
        }

        // Detach media no longer associated with the model
        foreach ($mediaToDetach as $mediaId) {
            $mediabe->media()->find($mediaId)->delete();
        }
    }

    /**
     * Normalize media arguments to an array of media IDs.
     *
     * @param array|Collection $mediaArgs The array or collection of media IDs or Media objects
     * @return array The array of media IDs
     */
    protected function normalizeMediaArgs($mediaArgs): array
    {
        if ($mediaArgs instanceof Collection) {
            // If $mediaArgs is a collection of Media objects, extract IDs
            return $mediaArgs->pluck('id')->toArray();
        }

        // Assume $mediaArgs is an array of media IDs
        return $mediaArgs;
    }

    private function isValidImage($data)
    {
        // Write your validation logic here
        // For example, you can use getimagesize or imagecreatefromstring
        // Here's a simple example using getimagesize:
        $imageInfo = getimagesizefromstring($data);
        return $imageInfo !== false;
    }
}
