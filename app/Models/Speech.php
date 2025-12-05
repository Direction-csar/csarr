<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'title',
        'excerpt',
        'content',
        'portrait',
        'date',
        'function',
        'location',
        'summary'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('d/m/Y') : '';
    }

    public function getShortExcerptAttribute()
    {
        return $this->excerpt ? \Str::limit($this->excerpt, 150) : \Str::limit($this->content, 150);
    }
}
