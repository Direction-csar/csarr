<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDocument extends Model
{
    use HasFactory;

    protected $table = 'hr_documents';

    protected $fillable = [
        'personnel_id',
        'type',
        'titre',
        'description',
        'fichier',
        'extension',
        'taille_fichier',
        'date_emission',
        'date_expiration',
        'statut',
        'commentaires',
        'cree_par'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_expiration' => 'date',
        'taille_fichier' => 'integer'
    ];

    // Relations
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', 'expire');
    }

    public function scopeParPersonnel($query, $personnelId)
    {
        return $query->where('personnel_id', $personnelId);
    }

    // Accesseurs
    public function getFichierUrlAttribute()
    {
        return asset('storage/hr-documents/' . $this->fichier);
    }

    public function getTailleFormateeAttribute()
    {
        $bytes = $this->taille_fichier;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'contrat_travail' => 'Contrat de travail',
            'bulletin_salaire' => 'Bulletin de salaire',
            'certificat_medical' => 'Certificat médical',
            'arret_maladie' => 'Arrêt maladie',
            'attestation_travail' => 'Attestation de travail',
            'certificat_formation' => 'Certificat de formation',
            'autre' => 'Autre document'
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    public function getStatutLabelAttribute()
    {
        $statuts = [
            'actif' => 'Actif',
            'expire' => 'Expiré',
            'archivé' => 'Archivé'
        ];
        
        return $statuts[$this->statut] ?? $this->statut;
    }

    // Méthodes
    public function estExpire()
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    public function marquerCommeExpire()
    {
        if ($this->estExpire()) {
            $this->update(['statut' => 'expire']);
        }
    }

    public static function genererNumeroDocument($type, $personnelId)
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $date = now()->format('Ymd');
        $count = self::where('type', $type)
                    ->where('personnel_id', $personnelId)
                    ->whereDate('created_at', today())
                    ->count() + 1;
        
        return "{$prefix}-{$personnelId}-{$date}-{$count}";
    }
}
