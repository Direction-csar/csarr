<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'is_active',
        'phone',
        'position',
        'department',
        'address',
        'avatar',
        'last_login_at',
        'warehouse_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec le rôle de l'utilisateur.
     */
    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the role of the user as a string.
     */
    public function getRoleAttribute()
    {
        // Si la colonne 'role' existe directement, l'utiliser
        if (isset($this->attributes['role'])) {
            return $this->attributes['role'];
        }
        
        // Sinon, utiliser role_id pour déterminer le rôle
        if (isset($this->attributes['role_id'])) {
            $roleMap = [
                1 => 'admin',
                2 => 'dg',
                3 => 'responsable',
                4 => 'agent',
                5 => 'drh'
            ];
            return $roleMap[$this->attributes['role_id']] ?? 'agent';
        }
        
        return 'agent';
    }

    /**
     * Get the public requests assigned to this user.
     */
    public function assignedRequests()
    {
        return $this->hasMany(PublicRequest::class, 'assigned_to');
    }

    /**
     * Relation avec l'entrepôt (pour les responsables)
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Nouvelles relations pour la base de données
    public function demandes()
    {
        return $this->hasMany(PublicRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class, 'warehouse_id');
    }

    /**
     * Relation avec les préférences de notification
     */
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    /**
     * Obtenir ou créer les préférences de notification
     */
    public function getNotificationPreferences()
    {
        return $this->notificationPreferences ?? NotificationPreference::createDefaultForUser($this->id);
    }

    /**
     * Vérifier si l'utilisateur souhaite recevoir un type de notification
     */
    public function wantsNotification($type)
    {
        $preferences = $this->getNotificationPreferences();
        return $preferences->isEnabled($type);
    }

    /**
     * Check if user has a specific role.
     * Accepts a role name (string) or an array of role names.
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }

        return $this->role === $role;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is DG.
     */
    public function isDG()
    {
        return $this->role === 'dg';
    }

    /**
     * Check if user is responsable.
     */
    public function isResponsable()
    {
        return $this->role === 'responsable';
    }

    /**
     * Check if user is agent.
     */
    public function isAgent()
    {
        return $this->role === 'agent';
    }
}

