<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key_name',
        'value',
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
