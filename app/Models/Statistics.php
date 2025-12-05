<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    protected $fillable = [
        'key',
        'title',
        'description',
        'value',
        'icon',
        'color',
        'section',
        'order',
        'is_active',
        'notes'
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
     * Scope pour une section donnée
     */
    public function scopeForSection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope pour ordonner par ordre d'affichage
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    /**
     * Obtenir les statistiques formatées pour l'affichage
     */
    public static function getFormattedStats($section = 'about')
    {
        return self::active()
            ->forSection($section)
            ->ordered()
            ->get()
            ->mapWithKeys(function ($stat) {
                return [
                    $stat->key => [
                        'value' => $stat->value,
                        'description' => $stat->description,
                        'icon' => $stat->icon,
                        'color' => $stat->color,
                        'title' => $stat->title
                    ]
                ];
            });
    }
}
