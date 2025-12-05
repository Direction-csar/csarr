<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'value',
        'icon',
        'description',
        'color',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Scope pour les statistiques actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Accessor pour formater la valeur
     */
    public function getFormattedValueAttribute()
    {
        if (is_numeric($this->value)) {
            return number_format($this->value);
        }
        return $this->value;
    }
}

