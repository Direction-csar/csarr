<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkAttendance extends Model
{
    use HasFactory;

    protected $table = 'work_attendance';

    protected $fillable = [
        'personnel_id',
        'date',
        'heure_arrivee',
        'heure_depart',
        'statut',
        'justification',
        'heures_travaillees',
        'valide',
        'valide_par',
        'date_validation'
    ];

    protected $casts = [
        'date' => 'date',
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
        'date_validation' => 'datetime',
        'heures_travaillees' => 'integer',
        'valide' => 'boolean'
    ];

    // Relations
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
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
        return $query->whereBetween('date', [$debut, $fin]);
    }

    public function scopeValides($query)
    {
        return $query->where('valide', true);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Accesseurs
    public function getStatutLabelAttribute()
    {
        $statuts = [
            'present' => 'Présent',
            'absent' => 'Absent',
            'retard' => 'Retard',
            'congé' => 'Congé',
            'maladie' => 'Maladie',
            'formation' => 'Formation',
            'mission' => 'Mission'
        ];
        
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getHeuresTravailleesFormateesAttribute()
    {
        $heures = floor($this->heures_travaillees / 60);
        $minutes = $this->heures_travaillees % 60;
        
        return sprintf('%02d:%02d', $heures, $minutes);
    }

    public function getDureePresenceAttribute()
    {
        if ($this->heure_arrivee && $this->heure_depart) {
            $arrivee = Carbon::parse($this->heure_arrivee);
            $depart = Carbon::parse($this->heure_depart);
            return $arrivee->diffInMinutes($depart);
        }
        
        return $this->heures_travaillees;
    }

    // Méthodes
    public function calculerHeuresTravaillees()
    {
        if ($this->heure_arrivee && $this->heure_depart) {
            $arrivee = Carbon::parse($this->heure_arrivee);
            $depart = Carbon::parse($this->heure_depart);
            $this->heures_travaillees = $arrivee->diffInMinutes($depart);
            $this->save();
        }
    }

    public function valider($userId)
    {
        $this->update([
            'valide' => true,
            'valide_par' => $userId,
            'date_validation' => now()
        ]);
    }

    public function invalider($userId)
    {
        $this->update([
            'valide' => false,
            'valide_par' => $userId,
            'date_validation' => now()
        ]);
    }

    public static function calculerStatistiques($personnelId, $debut, $fin)
    {
        $presences = self::parPersonnel($personnelId)
                        ->parPeriode($debut, $fin)
                        ->valides()
                        ->get();

        $stats = [
            'jours_presents' => $presences->where('statut', 'present')->count(),
            'jours_absents' => $presences->where('statut', 'absent')->count(),
            'jours_conges' => $presences->where('statut', 'congé')->count(),
            'jours_maladie' => $presences->where('statut', 'maladie')->count(),
            'jours_formation' => $presences->where('statut', 'formation')->count(),
            'jours_mission' => $presences->where('statut', 'mission')->count(),
            'retards' => $presences->where('statut', 'retard')->count(),
            'total_heures' => $presences->sum('heures_travaillees'),
            'moyenne_quotidienne' => $presences->where('statut', 'present')->avg('heures_travaillees') ?? 0
        ];

        return $stats;
    }
}
