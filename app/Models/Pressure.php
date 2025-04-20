<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pressure extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'pressure_id',
        'name',
        'description',
    ];

    public $translatable = ['name', 'description'];


    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public function eq_blueprints(): BelongsToMany
    {
        return $this->belongsToMany(EqBlueprint::class)->withTimestamps();
    }
}
