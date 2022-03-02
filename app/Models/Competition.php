<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use MaDnh\LaravelModelLabels\LabelsTrait;

class Competition extends Model
{
    use CrudTrait,
        LabelsTrait;

    protected $fillable = [
        'name', 'content', 'vote_count', 'accept_applications', 'active'
    ];

    protected static $label_path = 'competitions';

    public static function boot()
    {
        parent::boot();

        self::saved(function ($competition) {
            if ($competition->active) {
                Competition::where('id', '<>', $competition->id)->update(['active' => 0]);
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'competition_id');
    }

    public function nominations()
    {
        return $this->hasMany(Nomination::class, 'competition_id');
    }

    public function scopeActive($query)
    {
        return $query->whereActive(1);
    }

    public function scopeAcceptingApplications($query)
    {
        return $query->where('accept_applications', 1);
    }
}
