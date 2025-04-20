<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weight extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'weight_id',
        'unit',
        'abbreviation',
        'value',
    ];

    public $translatable = ['unit', 'abbreviation'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public function eq_blueprints(): BelongsToMany
    {
        return $this->belongsToMany(EqBlueprint::class)->withTimestamps();
    }

}
