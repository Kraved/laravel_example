<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Observers\UploadImageObserver;
use App\Services\UploadImageService;
use Illuminate\Support\Arr;
use Rocky\Eloquent\HasDynamicRelation;

/**
 * Trait UploadableImage
 *
 * To make image upload works, just call $this->setUploadedImage($value) on your model attribute mutator
 *
 * @package App\Models\Traits
 */
trait UploadableImage
{
    use HasDynamicRelation;

    protected $thumbSizes = [
        'thumb' => [150, 150],
        'medium' => [300, 300],
        'medium_large' => [768, 768],
        'large' => [1024, 1024],
    ];

    /**
     * Hook into the Eloquent model events to upload or delete image
     */
    public static function bootUploadableImage()
    {
        static::retrieved(function ($model) {
            $attributes = Arr::pluck($model->uploadableImages(), 'name');

            foreach ($attributes as $attribute) {
                static::addDynamicRelation($attribute, function ($entity) use ($attribute) {
                    return $entity->morphOne(Attachment::class, 'attachable')->where('field', $attribute);
                });
            }
        });

        static::creating(function ($model) {
            $attributes = Arr::pluck($model->uploadableImages(), 'name');

            foreach ($attributes as $attribute) {
                static::addDynamicRelation($attribute, function ($entity) use ($attribute) {
                    return $entity->morphOne(Attachment::class, 'attachable')->where('field', $attribute);
                });
            }
        });

        // We need to create a shared instance of Observer (only on this model) to keep some information at each fired events
        $observer = new UploadImageObserver(new UploadImageService());
        app()->instance(UploadImageObserver::class, $observer);

        // Observe Model Events with same instance of observer
        static::observe(app(UploadImageObserver::class));
    }

    /**
     * @param string $attribute
     * @param mixed $size
     *
     * @return string
     */
    public function thumbnail(string $attribute, $size)
    {
        if (is_string($size)) {
            $sizes = $this->getThumbSizes();
            $size = array_key_exists($size, $sizes) ? $sizes[$size] : false;
        }

        if (!$size) {
            return false;
        }

        if (!$this->$attribute) {
            return null;
        }

        $thumbFilePath = $this->getThumbFilePath($this->$attribute, $size);

        if (!\Storage::disk('public')->exists($thumbFilePath)) {
            return \Storage::disk('public')->url($this->$attribute->path);
        }

        return \Storage::disk('public')->url($thumbFilePath);
    }


    public function getThumbFileName($path, $size)
    {
        return sprintf(
            '%s_%sx%s.%s',
            pathinfo($path, PATHINFO_FILENAME),
            $size[0],
            $size[1],
            pathinfo($path, PATHINFO_EXTENSION));
    }

    public function getThumbDir($path)
    {
        return sprintf('%s/%s', dirname($path), 'thumbs');
    }

    public function getThumbFilePath(Attachment $attribute, $size)
    {
        return sprintf('%s/%s', $this->getThumbDir($attribute->path), $this->getThumbFileName($attribute->path, $size));
    }

    public function getThumbSizes()
    {
        return $this->thumbSizes;
    }

    /**
     * Get model attributes name for image upload
     * Simple example: return ['name' => 'image', slug' => 'title'];
     * With multiple images: return [['name' => 'image', slug' => 'title'], ['name' => 'thumbnail', 'slug' => 'title']];
     *
     * @return array
     */
    abstract public function uploadableImages(): array;
}
