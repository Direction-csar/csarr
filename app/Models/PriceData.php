<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceData extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_category',
        'market_location',
        'price',
        'currency',
        'unit',
        'data_source',
        'recorded_at',
        'region',
        'market_type'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function scopeByProduct($query, $productName)
    {
        return $query->where('product_name', $productName);
    }

    public function scopeByMarket($query, $marketLocation)
    {
        return $query->where('market_location', $marketLocation);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('recorded_at', '>=', now()->subDays($days));
    }

    public function scopeOrderByPrice($query, $direction = 'desc')
    {
        return $query->orderBy('price', $direction);
    }
}
