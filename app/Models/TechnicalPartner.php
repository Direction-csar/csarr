<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'organization',
        'type', // 'ong', 'agency', 'institution', 'private', 'government'
        'role',
        'intervention_zone',
        'contact_person',
        'email',
        'phone',
        'address',
        'website',
        'description',
        'partnership_type',
        'partnership_start_date',
        'partnership_end_date',
        'status', // 'active', 'inactive', 'pending'
        'is_featured',
        'position',
        'activities',
        'notes',
        'logo',
        'documents',
    ];

    protected $casts = [
        'intervention_zone' => 'array',
        'activities' => 'array',
        'documents' => 'array',
        'partnership_start_date' => 'date',
        'partnership_end_date' => 'date',
        'is_featured' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOng($query)
    {
        return $query->where('type', 'ong');
    }

    public function scopeAgency($query)
    {
        return $query->where('type', 'agency');
    }

    public function scopeInstitution($query)
    {
        return $query->where('type', 'institution');
    }

    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeGovernment($query)
    {
        return $query->where('type', 'government');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw('position IS NULL, position ASC')->orderBy('name');
    }

    public function getDisplayNameAttribute()
    {
        return $this->organization ?: $this->name;
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            'ong' => 'ONG',
            'agency' => 'Agence',
            'institution' => 'Institution',
            'private' => 'Privé',
            'government' => 'Gouvernement'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'pending' => 'En attente'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
} 
 
 
 
 
 
 