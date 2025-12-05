<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::logAudit('create', $model, null, $model->getAuditableAttributes());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
            
            if (!empty($newValues)) {
                static::logAudit('update', $model, $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            static::logAudit('delete', $model, $model->getAuditableAttributes(), null);
        });
    }

    protected static function logAudit($action, $model, $oldValues = null, $newValues = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return; // Ne pas logger si aucun utilisateur connecté
        }

        $description = static::generateDescription($action, $model);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id ?? null,
            'description' => $description,
            'old_values' => $oldValues ? static::filterAuditableValues($oldValues) : null,
            'new_values' => $newValues ? static::filterAuditableValues($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function generateDescription($action, $model)
    {
        $modelName = class_basename($model);
        $modelId = $model->id ?? 'nouveau';

        switch ($action) {
            case 'create':
                return "Création d'un nouveau {$modelName} (ID: {$modelId})";
            case 'update':
                return "Modification du {$modelName} (ID: {$modelId})";
            case 'delete':
                return "Suppression du {$modelName} (ID: {$modelId})";
            default:
                return "Action {$action} sur {$modelName} (ID: {$modelId})";
        }
    }

    protected function getAuditableAttributes()
    {
        // Si le modèle définit des attributs auditables spécifiques
        if (property_exists($this, 'auditableAttributes')) {
            return collect($this->getAttributes())
                ->only($this->auditableAttributes)
                ->toArray();
        }

        // Sinon, exclure les attributs sensibles par défaut
        $excluded = [
            'password',
            'remember_token',
            'email_verified_at',
            'created_at',
            'updated_at'
        ];

        if (property_exists($this, 'excludeFromAudit')) {
            $excluded = array_merge($excluded, $this->excludeFromAudit);
        }

        return collect($this->getAttributes())
            ->except($excluded)
            ->toArray();
    }

    protected static function filterAuditableValues($values)
    {
        // Filtrer les valeurs sensibles
        $excluded = [
            'password',
            'remember_token',
            'email_verified_at'
        ];

        return collect($values)
            ->except($excluded)
            ->toArray();
    }

    public static function logUserAction($action, $description = null, $modelType = null, $modelId = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return;
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description ?: "Action {$action} effectuée par l'utilisateur",
            'old_values' => null,
            'new_values' => null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}

