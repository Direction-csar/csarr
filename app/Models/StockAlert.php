<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'warehouse_id',
        'alert_type', // 'low_stock', 'expired', 'expiring_soon'
        'severity', // 'low', 'medium', 'high', 'critical'
        'message',
        'current_quantity',
        'threshold_quantity',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'notified_at',
        'created_at'
    ];

    protected $casts = [
        'current_quantity' => 'decimal:2',
        'threshold_quantity' => 'decimal:2',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'notified_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeHigh($query)
    {
        return $query->where('severity', 'high');
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    public function getSeverityIconAttribute()
    {
        return match($this->severity) {
            'critical' => 'fas fa-exclamation-triangle',
            'high' => 'fas fa-exclamation-circle',
            'medium' => 'fas fa-info-circle',
            'low' => 'fas fa-info',
            default => 'fas fa-circle'
        };
    }

    public function getTypeDisplayAttribute()
    {
        return match($this->alert_type) {
            'low_stock' => 'Stock Faible',
            'expired' => 'Produit Expiré',
            'expiring_soon' => 'Expiration Proche',
            default => $this->alert_type
        };
    }

    public function resolve($userId = null)
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId
        ]);
    }
}