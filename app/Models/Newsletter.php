<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subject',
        'content',
        'template',
        'status',
        'scheduled_at',
        'sent_at',
        'sent_by',
        'recipients_count',
        'delivered_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
        'unsubscribed_count',
        'open_rate',
        'click_rate',
        'metadata'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'recipients_count' => 'integer',
        'delivered_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'bounced_count' => 'integer',
        'unsubscribed_count' => 'integer',
        'open_rate' => 'decimal:2',
        'click_rate' => 'decimal:2',
        'metadata' => 'array'
    ];

    /**
     * Obtenir l'utilisateur qui a envoyé la newsletter
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scope pour les newsletters envoyées
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope pour les newsletters en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les newsletters programmées
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope pour les newsletters en cours d'envoi
     */
    public function scopeSending($query)
    {
        return $query->where('status', 'sending');
    }

    /**
     * Scope pour les newsletters échouées
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Marquer la newsletter comme envoyée
     */
    public function markAsSent($recipientsCount = 0)
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $recipientsCount
        ]);
    }

    /**
     * Marquer la newsletter comme en cours d'envoi
     */
    public function markAsSending()
    {
        $this->update(['status' => 'sending']);
    }

    /**
     * Marquer la newsletter comme échouée
     */
    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    /**
     * Programmer la newsletter
     */
    public function schedule($scheduledAt)
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt
        ]);
    }

    /**
     * Mettre à jour les statistiques
     */
    public function updateStats($delivered = 0, $opened = 0, $clicked = 0, $bounced = 0, $unsubscribed = 0)
    {
        $this->update([
            'delivered_count' => $delivered,
            'opened_count' => $opened,
            'clicked_count' => $clicked,
            'bounced_count' => $bounced,
            'unsubscribed_count' => $unsubscribed,
            'open_rate' => $delivered > 0 ? ($opened / $delivered) * 100 : 0,
            'click_rate' => $delivered > 0 ? ($clicked / $delivered) * 100 : 0
        ]);
    }

    /**
     * Obtenir les statistiques des newsletters
     */
    public static function getStats()
    {
        return [
            'total' => static::count(),
            'sent' => static::sent()->count(),
            'pending' => static::pending()->count(),
            'scheduled' => static::scheduled()->count(),
            'sending' => static::sending()->count(),
            'failed' => static::failed()->count(),
            'total_recipients' => static::sum('recipients_count'),
            'total_delivered' => static::sum('delivered_count'),
            'total_opened' => static::sum('opened_count'),
            'total_clicked' => static::sum('clicked_count'),
            'average_open_rate' => static::avg('open_rate'),
            'average_click_rate' => static::avg('click_rate'),
            'sent_this_month' => static::sent()->where('sent_at', '>=', now()->startOfMonth())->count()
        ];
    }

    /**
     * Obtenir les newsletters récentes
     */
    public static function getRecent($limit = 5)
    {
        return static::with('sender')->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    /**
     * Obtenir les newsletters programmées
     */
    public static function getScheduled()
    {
        return static::scheduled()->where('scheduled_at', '<=', now())->get();
    }

    /**
     * Créer une newsletter
     */
    public static function createNewsletter($title, $subject, $content, $template = 'default', $scheduledAt = null)
    {
        return static::create([
            'title' => $title,
            'subject' => $subject,
            'content' => $content,
            'template' => $template,
            'status' => $scheduledAt ? 'scheduled' : 'pending',
            'scheduled_at' => $scheduledAt,
            'sent_by' => auth()->id()
        ]);
    }
}