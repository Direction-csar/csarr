<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'numero_bulletin',
        'periode_debut',
        'periode_fin',
        'salaire_brut',
        'salaire_net',
        'cnss',
        'impot',
        'autres_deductions',
        'indemnite_logement',
        'indemnite_transport',
        'indemnite_fonction',
        'autres_indemnites',
        'jours_travailles',
        'jours_conges',
        'jours_absences',
        'statut',
        'fichier_pdf',
        'commentaires',
        'cree_par',
        'valide_par',
        'date_validation',
        'date_paiement'
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'date_validation' => 'datetime',
        'date_paiement' => 'datetime',
        'salaire_brut' => 'decimal:2',
        'salaire_net' => 'decimal:2',
        'cnss' => 'decimal:2',
        'impot' => 'decimal:2',
        'autres_deductions' => 'decimal:2',
        'indemnite_logement' => 'decimal:2',
        'indemnite_transport' => 'decimal:2',
        'indemnite_fonction' => 'decimal:2',
        'autres_indemnites' => 'decimal:2',
        'jours_travailles' => 'integer',
        'jours_conges' => 'integer',
        'jours_absences' => 'integer'
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

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    // Scopes
    public function scopeParPersonnel($query, $personnelId)
    {
        return $query->where('personnel_id', $personnelId);
    }

    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('periode_debut', [$debut, $fin]);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopePayes($query)
    {
        return $query->where('statut', 'paye');
    }

    // Accesseurs
    public function getStatutLabelAttribute()
    {
        $statuts = [
            'brouillon' => 'Brouillon',
            'valide' => 'Validé',
            'paye' => 'Payé',
            'annule' => 'Annulé'
        ];
        
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getFichierPdfUrlAttribute()
    {
        if ($this->fichier_pdf) {
            return asset('storage/salary-slips/' . $this->fichier_pdf);
        }
        return null;
    }

    public function getTotalIndemnitesAttribute()
    {
        return $this->indemnite_logement + 
               $this->indemnite_transport + 
               $this->indemnite_fonction + 
               $this->autres_indemnites;
    }

    public function getTotalDeductionsAttribute()
    {
        return $this->cnss + $this->impot + $this->autres_deductions;
    }

    public function getPeriodeLabelAttribute()
    {
        return $this->periode_debut->format('d/m/Y') . ' - ' . $this->periode_fin->format('d/m/Y');
    }

    // Méthodes
    public function calculerSalaireNet()
    {
        $totalIndemnites = $this->getTotalIndemnitesAttribute();
        $totalDeductions = $this->getTotalDeductionsAttribute();
        
        $this->salaire_net = $this->salaire_brut + $totalIndemnites - $totalDeductions;
        $this->save();
        
        return $this->salaire_net;
    }

    public function valider($userId)
    {
        $this->update([
            'statut' => 'valide',
            'valide_par' => $userId,
            'date_validation' => now()
        ]);
    }

    public function marquerCommePaye()
    {
        $this->update([
            'statut' => 'paye',
            'date_paiement' => now()
        ]);
    }

    public function annuler()
    {
        $this->update(['statut' => 'annule']);
    }

    public static function genererNumeroBulletin($personnelId, $periode)
    {
        $annee = $periode->format('Y');
        $mois = $periode->format('m');
        $count = self::where('personnel_id', $personnelId)
                    ->whereYear('periode_debut', $annee)
                    ->whereMonth('periode_debut', $mois)
                    ->count() + 1;
        
        return "BULL-{$personnelId}-{$annee}{$mois}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public static function calculerStatistiques($personnelId, $annee)
    {
        $bulletins = self::parPersonnel($personnelId)
                        ->whereYear('periode_debut', $annee)
                        ->valides()
                        ->get();

        return [
            'total_salaire_brut' => $bulletins->sum('salaire_brut'),
            'total_salaire_net' => $bulletins->sum('salaire_net'),
            'total_cnss' => $bulletins->sum('cnss'),
            'total_impot' => $bulletins->sum('impot'),
            'total_indemnites' => $bulletins->sum(function($bulletin) {
                return $bulletin->getTotalIndemnitesAttribute();
            }),
            'nombre_bulletins' => $bulletins->count(),
            'moyenne_salaire_net' => $bulletins->avg('salaire_net')
        ];
    }
}
