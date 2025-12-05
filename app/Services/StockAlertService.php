<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockAlert;
use App\Models\Warehouse;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockAlertService
{
    /**
     * Vérifier et créer des alertes pour tous les stocks
     */
    public function checkAllStocks()
    {
        $stocks = Stock::with(['warehouse', 'stockType'])
            ->where('is_active', true)
            ->get();

        $alertsCreated = 0;

        foreach ($stocks as $stock) {
            $alerts = $this->checkStock($stock);
            $alertsCreated += count($alerts);
        }

        Log::info("StockAlertService: {$alertsCreated} alertes créées lors de la vérification des stocks");

        return $alertsCreated;
    }

    /**
     * Vérifier un stock spécifique et créer les alertes nécessaires
     */
    public function checkStock(Stock $stock)
    {
        $alerts = [];

        // Vérifier le stock faible
        if ($this->isLowStock($stock)) {
            $alert = $this->createLowStockAlert($stock);
            if ($alert) {
                $alerts[] = $alert;
            }
        }

        // Vérifier l'expiration
        if ($this->isExpired($stock)) {
            $alert = $this->createExpiredAlert($stock);
            if ($alert) {
                $alerts[] = $alert;
            }
        } elseif ($this->isExpiringSoon($stock)) {
            $alert = $this->createExpiringSoonAlert($stock);
            if ($alert) {
                $alerts[] = $alert;
            }
        }

        return $alerts;
    }

    /**
     * Vérifier si le stock est faible
     */
    private function isLowStock(Stock $stock): bool
    {
        return $stock->min_quantity > 0 && $stock->quantity <= $stock->min_quantity;
    }

    /**
     * Vérifier si le produit est expiré
     */
    private function isExpired(Stock $stock): bool
    {
        return $stock->expiry_date && $stock->expiry_date->isPast();
    }

    /**
     * Vérifier si le produit expire bientôt (dans les 30 jours)
     */
    private function isExpiringSoon(Stock $stock): bool
    {
        return $stock->expiry_date && 
               $stock->expiry_date->isFuture() && 
               $stock->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Créer une alerte de stock faible
     */
    private function createLowStockAlert(Stock $stock): ?StockAlert
    {
        // Vérifier s'il n'y a pas déjà une alerte active pour ce stock
        $existingAlert = StockAlert::where('stock_id', $stock->id)
            ->where('alert_type', 'low_stock')
            ->where('is_resolved', false)
            ->first();

        if ($existingAlert) {
            return null;
        }

        $severity = $this->calculateLowStockSeverity($stock);
        $message = $this->generateLowStockMessage($stock);

        $alert = StockAlert::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $stock->warehouse_id,
            'alert_type' => 'low_stock',
            'severity' => $severity,
            'message' => $message,
            'current_quantity' => $stock->quantity,
            'threshold_quantity' => $stock->min_quantity,
        ]);

        // Envoyer une notification SMS
        try {
            $smsService = app(SmsService::class);
            $smsService->sendStockAlertNotification($alert);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification SMS pour l\'alerte de stock', [
                'alert_id' => $alert->id,
                'error' => $e->getMessage()
            ]);
        }

        return $alert;
    }

    /**
     * Créer une alerte de produit expiré
     */
    private function createExpiredAlert(Stock $stock): ?StockAlert
    {
        $existingAlert = StockAlert::where('stock_id', $stock->id)
            ->where('alert_type', 'expired')
            ->where('is_resolved', false)
            ->first();

        if ($existingAlert) {
            return null;
        }

        $message = "Le produit '{$stock->item_name}' a expiré le {$stock->expiry_date->format('d/m/Y')}";

        return StockAlert::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $stock->warehouse_id,
            'alert_type' => 'expired',
            'severity' => 'critical',
            'message' => $message,
            'current_quantity' => $stock->quantity,
            'threshold_quantity' => 0,
        ]);
    }

    /**
     * Créer une alerte de produit expirant bientôt
     */
    private function createExpiringSoonAlert(Stock $stock): ?StockAlert
    {
        $existingAlert = StockAlert::where('stock_id', $stock->id)
            ->where('alert_type', 'expiring_soon')
            ->where('is_resolved', false)
            ->first();

        if ($existingAlert) {
            return null;
        }

        $daysLeft = $stock->expiry_date->diffInDays(now());
        $severity = $daysLeft <= 7 ? 'high' : ($daysLeft <= 15 ? 'medium' : 'low');
        
        $message = "Le produit '{$stock->item_name}' expire dans {$daysLeft} jour(s) ({$stock->expiry_date->format('d/m/Y')})";

        return StockAlert::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $stock->warehouse_id,
            'alert_type' => 'expiring_soon',
            'severity' => $severity,
            'message' => $message,
            'current_quantity' => $stock->quantity,
            'threshold_quantity' => 0,
        ]);
    }

    /**
     * Calculer la sévérité d'une alerte de stock faible
     */
    private function calculateLowStockSeverity(Stock $stock): string
    {
        if ($stock->quantity <= 0) {
            return 'critical';
        }

        $percentage = ($stock->quantity / $stock->min_quantity) * 100;

        if ($percentage <= 10) {
            return 'critical';
        } elseif ($percentage <= 25) {
            return 'high';
        } elseif ($percentage <= 50) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Générer le message pour une alerte de stock faible
     */
    private function generateLowStockMessage(Stock $stock): string
    {
        $warehouse = $stock->warehouse->name ?? 'Entrepôt inconnu';
        $percentage = $stock->min_quantity > 0 ? 
            round(($stock->quantity / $stock->min_quantity) * 100, 1) : 0;

        return "Stock faible pour '{$stock->item_name}' dans {$warehouse}. " .
               "Quantité actuelle: {$stock->quantity}, Seuil minimum: {$stock->min_quantity} " .
               "({$percentage}% du seuil minimum)";
    }

    /**
     * Résoudre une alerte
     */
    public function resolveAlert(StockAlert $alert, $userId = null)
    {
        $alert->resolve($userId);
        
        Log::info("Alerte de stock résolue: {$alert->id} par utilisateur: {$userId}");
    }

    /**
     * Obtenir les statistiques des alertes
     */
    public function getAlertStats()
    {
        return [
            'total' => StockAlert::count(),
            'active' => StockAlert::where('is_resolved', false)->count(),
            'critical' => StockAlert::where('is_resolved', false)->where('severity', 'critical')->count(),
            'high' => StockAlert::where('is_resolved', false)->where('severity', 'high')->count(),
            'medium' => StockAlert::where('is_resolved', false)->where('severity', 'medium')->count(),
            'low' => StockAlert::where('is_resolved', false)->where('severity', 'low')->count(),
            'low_stock' => StockAlert::where('is_resolved', false)->where('alert_type', 'low_stock')->count(),
            'expired' => StockAlert::where('is_resolved', false)->where('alert_type', 'expired')->count(),
            'expiring_soon' => StockAlert::where('is_resolved', false)->where('alert_type', 'expiring_soon')->count(),
        ];
    }
}
