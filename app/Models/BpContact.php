<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BpContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'job_title',
        'department',
        'phone',
        'email',
        'linkedin_url',
        'status',
        'contactable_id',
        'contactable_type',
    ];

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
