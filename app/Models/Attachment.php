<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'filename', 'path', 'mime', 'size', 'attachable_type', 'attachable_id', 'field'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function ($video) {
            \Storage::delete($video->path);
        });
    }

    public function __toString()
    {
        return $this->path;
    }
}
