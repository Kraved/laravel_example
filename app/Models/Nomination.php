<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use MaDnh\LaravelModelLabels\LabelsTrait;

class Nomination extends Model
{
    use CrudTrait,
        LabelsTrait;

    protected $fillable = [
        'competition_id', 'name', 'sort_order', 'active'
    ];

    protected static $label_path = 'nominations';

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'application_nomination');
    }

    public function scopeActive($query)
    {
        return $query->whereActive(1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public static function getList($competitionId)
    {
        return Nomination::where('competition_id', $competitionId)->ordered()->get()->pluck('name', 'id');
    }
}
