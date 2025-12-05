<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'region',
        'city',
        'phone',
        'email',
        'is_active',
        'capacity',
        'current_stock',
        'status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Relation avec les utilisateurs (responsables)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les mouvements de stock
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
} 