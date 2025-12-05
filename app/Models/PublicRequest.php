<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tracking_code',
        'type',
        'status',
        'full_name',
        'phone',
        'email',
        'subject',
        'address',
        'latitude',
        'longitude',
        'region',
        'description',
        'admin_comment',
        'assigned_to',
        'request_date',
        'processed_date',
        'sms_sent',
        'is_viewed',
        'viewed_at',
        'duplicate_hash',
        'urgency',
        'preferred_contact',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'request_date' => 'date',
        'processed_date' => 'date',
        'sms_sent' => 'boolean',
        'is_viewed' => 'boolean',
        'viewed_at' => 'datetime'
    ];

    /**
     * Marquer la demande comme vue si ce n'est pas déjà fait
     */
    public function markAsViewed(): void
    {
        if (!$this->is_viewed) {
            $this->update([
                'is_viewed' => true,
                'viewed_at' => now(),
            ]);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public static function generateTrackingCode()
    {
        do {
            $code = 'CSAR-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('tracking_code', $code)->exists());

        return $code;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'completed' => 'blue',
            default => 'gray'
        };
    }
} 