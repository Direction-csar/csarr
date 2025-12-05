<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'stock_type_id',
        'item_name',
        'description',
        'quantity',
        'min_quantity',
        'max_quantity',
        'expiry_date',
        'batch_number',
        'supplier',
        'unit_price',
        'is_active'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_quantity' => 'decimal:2',
        'max_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockType()
    {
        return $this->belongsTo(StockType::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
} 