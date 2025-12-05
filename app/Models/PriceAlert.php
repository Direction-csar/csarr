<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_category',
        'market_location',
        'current_price',
        'previous_price',
        'price_change_percentage',
        'price_change_type',
        'threshold_percentage',
        'alert_type',
        'severity',
        'message',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'notified_at',
        'data_source',
        'currency',
        'unit',
        'region',
        'market_type'
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'price_change_percentage' => 'decimal:2',
        'threshold_percentage' => 'decimal:2',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    // Types d'alertes
    const TYPE_PRICE_INCREASE = 'price_increase';
    const TYPE_PRICE_DECREASE = 'price_decrease';
    const TYPE_ABNORMAL_SPIKE = 'abnormal_spike';
    const TYPE_ABNORMAL_DROP = 'abnormal_drop';
    const TYPE_MARKET_TREND = 'market_trend';

    // Types de changement de prix
    const CHANGE_TYPE_INCREASE = 'increase';
    const CHANGE_TYPE_DECREASE = 'decrease';
    const CHANGE_TYPE_STABLE = 'stable';

    // Niveaux de sévérité
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    // Types de marché
    const MARKET_TYPE_WHOLESALE = 'wholesale';
    const MARKET_TYPE_RETAIL = 'retail';
    const MARKET_TYPE_EXPORT = 'export';
    const MARKET_TYPE_IMPORT = 'import';

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isResolved()
    {
        return $this->is_resolved;
    }

    public function markAsResolved($userId = null)
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId
        ]);
    }

    public function markAsNotified()
    {
        $this->update(['notified_at' => now()]);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByAlertType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByProductCategory($query, $category)
    {
        return $query->where('product_category', $category);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function getAlertTypeLabelAttribute()
    {
        return match($this->alert_type) {
            self::TYPE_PRICE_INCREASE => 'Hausse de Prix',
            self::TYPE_PRICE_DECREASE => 'Baisse de Prix',
            self::TYPE_ABNORMAL_SPIKE => 'Pic Anormal',
            self::TYPE_ABNORMAL_DROP => 'Chute Anormale',
            self::TYPE_MARKET_TREND => 'Tendance Marché',
            default => 'Alerte Prix'
        };
    }

    public function getSeverityLabelAttribute()
    {
        return match($this->severity) {
            self::SEVERITY_LOW => 'Faible',
            self::SEVERITY_MEDIUM => 'Moyen',
            self::SEVERITY_HIGH => 'Élevé',
            self::SEVERITY_CRITICAL => 'Critique',
            default => 'Non défini'
        };
    }

    public function isUnresolved()
    {
        return !$this->is_resolved;
    }

    public function getPriceChangeDirectionAttribute()
    {
        if ($this->price_change_percentage > 0) {
            return 'up';
        } elseif ($this->price_change_percentage < 0) {
            return 'down';
        }
        return 'stable';
    }

    public function getFormattedPriceChangeAttribute()
    {
        $direction = $this->price_change_direction;
        $percentage = abs($this->price_change_percentage);
        
        return sprintf(
            '%s %s%% (%s %s)',
            $direction === 'up' ? '+' : ($direction === 'down' ? '-' : ''),
            number_format($percentage, 2),
            $this->currency,
            number_format(abs($this->current_price - $this->previous_price), 2)
        );
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            self::SEVERITY_LOW => 'success',
            self::SEVERITY_MEDIUM => 'warning',
            self::SEVERITY_HIGH => 'danger',
            self::SEVERITY_CRITICAL => 'dark',
            default => 'secondary'
        };
    }

    public function getMarketTypeLabelAttribute()
    {
        return match($this->market_type) {
            self::MARKET_TYPE_WHOLESALE => 'Gros',
            self::MARKET_TYPE_RETAIL => 'Détail',
            self::MARKET_TYPE_EXPORT => 'Export',
            self::MARKET_TYPE_IMPORT => 'Import',
            default => 'Non défini'
        };
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeHighImpact($query)
    {
        return $query->where('price_change_percentage', '>=', 10)
                    ->orWhere('severity', '>=', self::SEVERITY_HIGH);
    }
}