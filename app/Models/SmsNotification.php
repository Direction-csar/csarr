<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'message',
        'type',
        'status',
        'sent_at',
        'delivered_at',
        'failed_at',
        'error_message',
        'related_type',
        'related_id',
        'priority',
        'retry_count',
        'max_retries'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Types de notifications
    const TYPE_REQUEST_CREATED = 'request_created';
    const TYPE_REQUEST_UPDATED = 'request_updated';
    const TYPE_REQUEST_APPROVED = 'request_approved';
    const TYPE_REQUEST_REJECTED = 'request_rejected';
    const TYPE_STOCK_ALERT = 'stock_alert';
    const TYPE_PRICE_ALERT = 'price_alert';
    const TYPE_SYSTEM_ALERT = 'system_alert';

    // Statuts
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';

    // Priorités
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    public function related()
    {
        return $this->morphTo();
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSent()
    {
        return $this->status === self::STATUS_SENT;
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function canRetry()
    {
        return $this->isFailed() && $this->retry_count < $this->max_retries;
    }

    public function markAsSent()
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now()
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now()
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now(),
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1
        ]);
    }
}
