<?php

namespace App\Models;

use App\Models\Traits\UploadableFile;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MaDnh\LaravelModelLabels\LabelsTrait;

class Application extends Model
{
    use CrudTrait,
        LabelsTrait,
        SoftDeletes,
        UploadableFile;

    protected $fillable = [
        'name', 'competition_id', 'extras', 'video', 'video_description', 'video_youtube',
        'nomination_id', 'viewed_at', 'semifinal_video', 'final_video'
    ];

    protected $dates = [
        'viewed_at'
    ];

    protected $fakeColumns = ['extras'];

    protected static $label_path = 'applications';

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function uploadableFiles()
    {
        return [
            [
                'name' => 'video',
                'path' => 'applications'
            ],
            [
                'name' => 'video_description',
                'path' => 'applications'
            ],
            [
                'name' => 'semifinal_video',
                'path' => 'applications'
            ],
            [
                'name' => 'final_video',
                'path' => 'applications'
            ],
        ];
    }

    public function getVideoLink()
    {
        $link = null;

        if ($this->video) {
            $link = link_to(\Storage::url($this->video->path), $this->video->filename, ['target' => '_blank']);
        }

        return $link;
    }

    public function getVideoDescriptionLink()
    {
        $link = null;

        if ($this->video_description) {
            $link = link_to(\Storage::url($this->video_description->path), $this->video_description->filename, ['target' => '_blank']);
        }

        return $link;
    }

    public function getFinalVideoLink()
    {
        $link = null;

        if ($this->final_video) {
            $link = link_to(\Storage::url($this->final_video->path), $this->final_video->filename, ['target' => '_blank']);
        }

        return $link;
    }

    public function getSemiFinalVideoLink()
    {
        $link = null;

        if ($this->semifinal_video) {
            $link = link_to(\Storage::url($this->semifinal_video->path), $this->semifinal_video->filename, ['target' => '_blank']);
        }

        return $link;
    }


    public function getVideoYoutubeEmbed()
    {
        $html = null;

        $youtubePattern = '/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/';

        if (preg_match($youtubePattern, $this->video_youtube, $mch)) {
            $html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $mch[1] . '" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>';
        }

        return $html;
    }

    /**
     * Связь с таблицей attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class,  'attachable_id');
    }

    public function nominations()
    {
        return $this->belongsToMany(Nomination::class, 'application_nomination');
    }

    public function stages()
    {
        return $this->belongsToMany(
            Stage::class,
            'applications_stages',
            'application_id',
            'nomination_stage_id'
        );
    }
}
