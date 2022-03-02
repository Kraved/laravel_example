<?php

namespace App\Models;

use App\Models\Traits\HasTemplate;
use App\Models\Traits\UploadableImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use MaDnh\LaravelModelLabels\LabelsTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    use HasSlug,
        HasTemplate,
        LabelsTrait,
        CrudTrait,
        UploadableImage;

    protected static $label_path = 'articles';

    protected static $template_view = 'articles.show';

    protected $fillable = [
        'name', 'slug', 'preview_text', 'preview_picture', 'detail_text', 'detail_picture',
        'template', 'meta_title', 'meta_keywords', 'meta_description', 'active'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function identifiableName()
    {
        return $this->name;
    }

    public function getSlugOptions(): SlugOptions
    {
        $options = SlugOptions::create()
            ->generateSlugsFrom('name')
            ->slugsShouldBeNoLongerThan(200)
            ->saveSlugsTo('slug');

        if (isset($this->attributes['slug']) && $this->attributes['slug']) {
            $options->doNotGenerateSlugsOnUpdate();
        }

        return $options;
    }

    public function uploadableImages()
    {
        return [
            [
                'name' => 'preview_picture',
                'path' => 'articles/preview_pictures'
            ],
            [
                'name' => 'detail_picture',
                'path' => 'articles/detail_pictures'
            ],
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
