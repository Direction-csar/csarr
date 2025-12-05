<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyAgenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_type', // 'meeting', 'delivery', 'visit', 'task', 'instruction'
        'start_date',
        'end_date',
        'location',
        'participants',
        'assigned_to',
        'created_by',
        'priority',
        'status', // 'scheduled', 'in_progress', 'completed', 'cancelled'
        'notes',
        'attachments',
        'reminder_sent',
        'category',
        'tags'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'participants' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'reminder_sent' => 'boolean',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeThisWeek($query)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        return $query->whereBetween('start_date', [$startOfWeek, $endOfWeek]);
    }

    public function scopeNextWeek($query)
    {
        $startOfNextWeek = now()->addWeek()->startOfWeek();
        $endOfNextWeek = now()->addWeek()->endOfWeek();
        return $query->whereBetween('start_date', [$startOfNextWeek, $endOfNextWeek]);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_date', today());
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('start_date', '>=', now())
                    ->where('start_date', '<=', now()->addDays($days));
    }

    public function getEventTypeLabelAttribute()
    {
        return match($this->event_type) {
            'meeting' => 'Réunion',
            'delivery' => 'Livraison',
            'visit' => 'Visite',
            'task' => 'Tâche',
            'instruction' => 'Instruction',
            default => ucfirst($this->event_type)
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed' => 'success',
            'in_progress' => 'warning',
            'scheduled' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function isToday()
    {
        return $this->start_date->isToday();
    }

    public function isUpcoming()
    {
        return $this->start_date->isFuture();
    }

    public function isPast()
    {
        return $this->start_date->isPast();
    }
}
 
 
 
 
 
 