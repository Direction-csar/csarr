<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_enabled',
        'task_assignments',
        'request_updates',
        'price_alerts',
        'news_updates',
        'system_notifications',
        'weekly_digest'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'task_assignments' => 'boolean',
        'request_updates' => 'boolean',
        'price_alerts' => 'boolean',
        'news_updates' => 'boolean',
        'system_notifications' => 'boolean',
        'weekly_digest' => 'boolean'
    ];

    protected $attributes = [
        'email_enabled' => true,
        'task_assignments' => true,
        'request_updates' => true,
        'price_alerts' => true,
        'news_updates' => false,
        'system_notifications' => true,
        'weekly_digest' => false
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si un type de notification est activé
     */
    public function isEnabled($type)
    {
        return $this->email_enabled && $this->{$type};
    }

    /**
     * Créer les préférences par défaut pour un utilisateur
     */
    public static function createDefaultForUser($userId)
    {
        return static::create([
            'user_id' => $userId,
            'email_enabled' => true,
            'task_assignments' => true,
            'request_updates' => true,
            'price_alerts' => true,
            'news_updates' => false,
            'system_notifications' => true,
            'weekly_digest' => false
        ]);
    }
}

