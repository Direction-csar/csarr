<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory; // , SoftDeletes;

    protected $fillable = [
        'sujet',
        'contenu',
        'expediteur',
        'email_expediteur',
        'telephone_expediteur',
        'lu',
        'lu_at',
        'user_id',
        'reponse',
        'reponse_at'
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_at' => 'datetime',
        'reponse_at' => 'datetime'
    ];

    /**
     * Obtenir l'utilisateur associé au message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->where('lu', false);
    }

    /**
     * Scope pour les messages lus
     */
    public function scopeRead($query)
    {
        return $query->where('lu', true);
    }

    /**
     * Scope pour les messages non répondus
     */
    public function scopeUnreplied($query)
    {
        return $query->whereNull('reponse');
    }

    /**
     * Scope pour les messages répondus
     */
    public function scopeReplied($query)
    {
        return $query->whereNotNull('reponse');
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead()
    {
        $this->update([
            'lu' => true,
            'lu_at' => now()
        ]);
    }

    /**
     * Marquer le message comme non lu
     */
    public function markAsUnread()
    {
        $this->update([
            'lu' => false,
            'lu_at' => null
        ]);
    }

    /**
     * Répondre au message
     */
    public function reply($replyMessage, $repliedBy = null)
    {
        $this->update([
            'reponse' => $replyMessage,
            'reponse_at' => now()
        ]);
    }

    /**
     * Obtenir les statistiques des messages
     */
    public static function getStats()
    {
        return [
            'total' => static::count(),
            'unread' => static::unread()->count(),
            'read' => static::read()->count(),
            'replied' => static::replied()->count(),
            'unreplied' => static::unreplied()->count(),
            'new_today' => static::whereDate('created_at', today())->count(),
            'new_this_week' => static::where('created_at', '>=', now()->startOfWeek())->count(),
            'new_this_month' => static::where('created_at', '>=', now()->startOfMonth())->count()
        ];
    }

    /**
     * Obtenir les messages récents
     */
    public static function getRecent($limit = 5)
    {
        return static::orderBy('created_at', 'desc')->limit($limit)->get();
    }
}