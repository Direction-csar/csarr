<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'read',
        'data',
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'read_at'
    ];

    protected $casts = [
        'read' => 'boolean',
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    /**
     * Relation polymorphique avec l'entité concernée (demande, message, etc.)
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope pour les notifications lues
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Scope pour les notifications d'un type spécifique
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }


    /**
     * Marquer la notification comme lue
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Marquer la notification comme non lue
     */
    public function markAsUnread()
    {
        $this->update([
            'read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Créer une notification
     */
    public static function createNotification($title, $message, $type = 'info', $data = [], $notifiable = null, $icon = null, $actionUrl = null)
    {
        $notificationData = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
            'icon' => $icon ?? static::getDefaultIcon($type),
            'action_url' => $actionUrl
        ];

        if ($notifiable) {
            $notificationData['notifiable_type'] = get_class($notifiable);
            $notificationData['notifiable_id'] = $notifiable->id;
        }

        return static::create($notificationData);
    }

    /**
     * Obtenir l'icône par défaut selon le type
     */
    public static function getDefaultIcon($type)
    {
        $icons = [
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'alert-triangle',
            'info' => 'info',
            'demande' => 'file-text',
            'message' => 'mail',
            'newsletter' => 'send',
            'communication' => 'megaphone'
        ];

        return $icons[$type] ?? 'bell';
    }

    /**
     * Obtenir le temps écoulé formaté
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Obtenir les statistiques des notifications
     */
    public static function getStats()
    {
        return [
            'total' => static::count(),
            'unread' => static::unread()->count(),
            'read' => static::read()->count(),
            'info' => static::type('info')->count(),
            'success' => static::type('success')->count(),
            'warning' => static::type('warning')->count(),
            'error' => static::type('error')->count(),
            'demande' => static::type('demande')->count(),
            'message' => static::type('message')->count(),
            'newsletter' => static::type('newsletter')->count(),
            'communication' => static::type('communication')->count(),
            'new_today' => static::whereDate('created_at', today())->count(),
            'new_this_week' => static::where('created_at', '>=', now()->startOfWeek())->count(),
            'new_this_month' => static::where('created_at', '>=', now()->startOfMonth())->count()
        ];
    }

    /**
     * Obtenir les notifications récentes
     */
    public static function getRecent($limit = 10)
    {
        return static::orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * Obtenir les notifications non lues
     */
    public static function getUnread($limit = 10)
    {
        return static::unread()->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public static function markAllAsRead()
    {
        return static::unread()->update([
            'read' => true,
            'read_at' => now()
        ]);
    }
}