<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'min_threshold',
        'max_capacity',
        'last_updated'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_threshold' => 'decimal:2',
        'max_capacity' => 'decimal:2',
        'last_updated' => 'datetime'
    ];

    // Relations
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= min_threshold');
    }

    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Méthodes
    public function updateQuantity($newQuantity, $movementType = null)
    {
        $this->quantity = $newQuantity;
        $this->last_updated = now();
        $this->save();
    }

    public function addQuantity($amount)
    {
        $this->updateQuantity($this->quantity + $amount);
    }

    public function subtractQuantity($amount)
    {
        $newQuantity = max(0, $this->quantity - $amount);
        $this->updateQuantity($newQuantity);
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_threshold;
    }

    public function getStockStatusAttribute()
    {
        if ($this->isLowStock()) {
            return 'low';
        } elseif ($this->max_capacity && $this->quantity >= $this->max_capacity * 0.9) {
            return 'high';
        }
        return 'normal';
    }
}
