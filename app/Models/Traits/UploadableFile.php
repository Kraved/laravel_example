<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Observers\UploadFileObserver;
use App\Services\UploadFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Rocky\Eloquent\HasDynamicRelation;

/**
 * Trait UploadableFile
 *
 * To make documents upload works, just call $this->setUploadedFile($value) on your model attribute mutator
 *
 * @package App\Models\Traits
 */
trait UploadableFile
{
    use HasDynamicRelation;

    /**
     * Hook into the Eloquent model events to upload or delete elements
     */
    public static function bootUploadableFile()
    {
        static::retrieved(function ($model) {
            $attributes = Arr::pluck($model->uploadableFiles(), 'name');

            foreach ($attributes as $attribute) {
                static::addDynamicRelation($attribute, function ($entity) use ($attribute) {
                    return $entity->morphOne(Attachment::class, 'attachable')->where('field', $attribute);
                });
            }
        });

        static::creating(function ($model) {
            $attributes = Arr::pluck($model->uploadableFiles(), 'name');

            foreach ($attributes as $attribute) {
                static::addDynamicRelation($attribute, function ($entity) use ($attribute) {
                    return $entity->morphOne(Attachment::class, 'attachable')->where('field', $attribute);
                });
            }
        });

        // We need to create a shared instance of Observer (only on this model) to keep some information at each fired events
        $observer = new UploadFileObserver(new UploadFileService());
        app()->instance(UploadFileObserver::class, $observer);

        // Observe Model Events with same instance of observer
        static::observe(app(UploadFileObserver::class));
    }

    public function getAttribute($key) {
        $attributes = Arr::pluck($this->uploadableFiles(), 'name');

        if (in_array($key, $attributes)) {
            $value = parent::getAttribute($key);

            if (is_a($value, UploadedFile::class)) {
                return $value;
            }

            return Attachment::find($value);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get model attributes name for files upload
     * Simple example: return ['name' => 'document', slug' => 'title'];
     * With multiple files: return [['name' => 'document', slug' => 'title'], ['name' => 'image']];
     *
     * @return array
     */
    abstract public function uploadableFiles(): array;
}
