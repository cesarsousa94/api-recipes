<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'title', 'description',
        'ingredients', 'steps', 'prep_time', 'yield', 'tags'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'steps'       => 'array',
        'tags'        => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->uuid = $m->uuid ?: (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
