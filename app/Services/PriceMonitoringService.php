<?php

namespace App\Services;

use App\Models\PriceAlert;
use App\Models\PriceData;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class PriceMonitoringService
{
    protected $smsService;
    protected $dataSources;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        $this->dataSources = config('price_monitoring.data_sources', []);
    }

    /**
     * Surveiller les prix de tous les produits
     */
    public function monitorAllPrices()
    {
        Log::info('Début de la surveillance des prix');

        $totalAlerts = 0;
        $processedProducts = 0;

        foreach ($this->dataSources as $source) {
            try {
                $alerts = $this->monitorDataSource($source);
                $totalAlerts += $alerts;
                $processedProducts++;
                
                Log::info("Source {$source['name']} traitée: {$alerts} alertes générées");
            } catch (Exception $e) {
                Log::error("Erreur lors de la surveillance de la source {$source['name']}: " . $e->getMessage());
            }
        }

        Log::info("Surveillance terminée: {$processedProducts} sources traitées, {$totalAlerts} alertes générées");
        
        return $totalAlerts;
    }

    /**
     * Surveiller une source de données spécifique
     */
    protected function monitorDataSource(array $source)
    {
        $alertsCount = 0;

        // Récupérer les données de prix depuis la source
        $priceData = $this->fetchPriceData($source);

        foreach ($priceData as $data) {
            // Vérifier s'il y a une alerte à générer
            $alert = $this->analyzePriceData($data, $source);
            
            if ($alert) {
                $this->createPriceAlert($alert, $source);
                $alertsCount++;
            }
        }

        return $alertsCount;
    }

    /**
     * Récupérer les données de prix depuis une source externe
     */
    protected function fetchPriceData(array $source)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders($source['headers'] ?? [])
                ->get($source['url']);

            if (!$response->successful()) {
                throw new Exception("Erreur HTTP: " . $response->status());
            }

            $data = $response->json();
            return $data[$source['data_key']] ?? [];

        } catch (Exception $e) {
            Log::error("Erreur lors de la récupération des données de prix: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Analyser les données de prix pour détecter des anomalies
     */
    protected function analyzePriceData(array $data, array $source)
    {
        $productName = $data['product_name'] ?? $data['name'] ?? 'Produit inconnu';
        $currentPrice = (float) ($data['current_price'] ?? $data['price'] ?? 0);
        $previousPrice = $this->getPreviousPrice($productName, $source['market_location']);

        if ($previousPrice === null || $currentPrice <= 0) {
            return null;
        }

        $priceChangePercentage = (($currentPrice - $previousPrice) / $previousPrice) * 100;
        $thresholdPercentage = $source['threshold_percentage'] ?? 5.0;

        // Vérifier si le changement de prix dépasse le seuil
        if (abs($priceChangePercentage) >= $thresholdPercentage) {
            return [
                'product_name' => $productName,
                'product_category' => $data['category'] ?? null,
                'market_location' => $source['market_location'],
                'current_price' => $currentPrice,
                'previous_price' => $previousPrice,
                'price_change_percentage' => $priceChangePercentage,
                'price_change_type' => $priceChangePercentage > 0 ? 'increase' : 'decrease',
                'threshold_percentage' => $thresholdPercentage,
                'alert_type' => $this->determineAlertType($priceChangePercentage, $thresholdPercentage),
                'severity' => $this->calculateSeverity($priceChangePercentage, $thresholdPercentage),
                'message' => $this->generateAlertMessage($productName, $currentPrice, $previousPrice, $priceChangePercentage),
                'data_source' => $source['name'],
                'currency' => $data['currency'] ?? $source['currency'] ?? 'XOF',
                'unit' => $data['unit'] ?? $source['unit'] ?? 'kg',
                'region' => $source['region'] ?? null,
                'market_type' => $source['market_type'] ?? 'retail'
            ];
        }

        return null;
    }

    /**
     * Obtenir le prix précédent d'un produit
     */
    protected function getPreviousPrice(string $productName, string $marketLocation)
    {
        // Rechercher dans les données de prix stockées
        $previousData = PriceData::where('product_name', $productName)
            ->where('market_location', $marketLocation)
            ->orderBy('created_at', 'desc')
            ->first();

        return $previousData ? $previousData->price : null;
    }

    /**
     * Déterminer le type d'alerte
     */
    protected function determineAlertType(float $priceChangePercentage, float $thresholdPercentage)
    {
        $isSignificant = abs($priceChangePercentage) >= ($thresholdPercentage * 2);

        if ($priceChangePercentage > 0) {
            return $isSignificant ? PriceAlert::TYPE_ABNORMAL_SPIKE : PriceAlert::TYPE_PRICE_INCREASE;
        } else {
            return $isSignificant ? PriceAlert::TYPE_ABNORMAL_DROP : PriceAlert::TYPE_PRICE_DECREASE;
        }
    }

    /**
     * Calculer la sévérité de l'alerte
     */
    protected function calculateSeverity(float $priceChangePercentage, float $thresholdPercentage)
    {
        $absPercentage = abs($priceChangePercentage);
        $thresholdMultiplier = $absPercentage / $thresholdPercentage;

        if ($thresholdMultiplier >= 3) {
            return PriceAlert::SEVERITY_CRITICAL;
        } elseif ($thresholdMultiplier >= 2) {
            return PriceAlert::SEVERITY_HIGH;
        } elseif ($thresholdMultiplier >= 1.5) {
            return PriceAlert::SEVERITY_MEDIUM;
        } else {
            return PriceAlert::SEVERITY_LOW;
        }
    }

    /**
     * Générer le message d'alerte
     */
    protected function generateAlertMessage(string $productName, float $currentPrice, float $previousPrice, float $priceChangePercentage)
    {
        $direction = $priceChangePercentage > 0 ? 'hausse' : 'baisse';
        $percentage = abs($priceChangePercentage);
        $currency = 'XOF';

        return sprintf(
            'Alerte prix: %s en %s de %.2f%% (%.2f %s -> %.2f %s)',
            $productName,
            $direction,
            $percentage,
            $previousPrice,
            $currency,
            $currentPrice,
            $currency
        );
    }

    /**
     * Créer une alerte de prix
     */
    protected function createPriceAlert(array $alertData, array $source)
    {
        try {
            $alert = PriceAlert::create($alertData);

            // Envoyer une notification SMS si configuré
            if ($source['send_sms'] ?? false) {
                $this->sendPriceAlertNotification($alert);
            }

            // Stocker les données de prix
            $this->storePriceData($alertData, $source);

            Log::info("Alerte de prix créée", [
                'alert_id' => $alert->id,
                'product' => $alert->product_name,
                'price_change' => $alert->price_change_percentage . '%'
            ]);

            return $alert;

        } catch (Exception $e) {
            Log::error("Erreur lors de la création de l'alerte de prix: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Envoyer une notification SMS pour une alerte de prix
     */
    protected function sendPriceAlertNotification(PriceAlert $alert)
    {
        try {
            // Récupérer les numéros des responsables concernés
            $phoneNumbers = $this->getRelevantPhoneNumbers($alert);

            foreach ($phoneNumbers as $phoneNumber) {
                $this->smsService->sendSms(
                    $phoneNumber,
                    $alert->message,
                    'price_alert',
                    $this->mapSeverityToPriority($alert->severity),
                    'price_alert',
                    $alert->id
                );
            }

            $alert->markAsNotified();

        } catch (Exception $e) {
            Log::error("Erreur lors de l'envoi de la notification SMS: " . $e->getMessage());
        }
    }

    /**
     * Obtenir les numéros de téléphone concernés par l'alerte
     */
    protected function getRelevantPhoneNumbers(PriceAlert $alert)
    {
        // Récupérer les responsables de la région ou du type de produit
        $users = \App\Models\User::where('role', 'admin')
            ->orWhere('role', 'dg')
            ->where(function($query) use ($alert) {
                $query->where('region', $alert->region)
                      ->orWhereNull('region');
            })
            ->get();

        return $users->pluck('phone')->filter()->toArray();
    }

    /**
     * Mapper la sévérité vers la priorité SMS
     */
    protected function mapSeverityToPriority(string $severity)
    {
        return match($severity) {
            PriceAlert::SEVERITY_CRITICAL => 'urgent',
            PriceAlert::SEVERITY_HIGH => 'high',
            PriceAlert::SEVERITY_MEDIUM => 'normal',
            PriceAlert::SEVERITY_LOW => 'low',
            default => 'normal'
        };
    }

    /**
     * Stocker les données de prix pour référence future
     */
    protected function storePriceData(array $alertData, array $source)
    {
        PriceData::create([
            'product_name' => $alertData['product_name'],
            'market_location' => $alertData['market_location'],
            'price' => $alertData['current_price'],
            'currency' => $alertData['currency'],
            'unit' => $alertData['unit'],
            'data_source' => $source['name'],
            'recorded_at' => now()
        ]);
    }

    /**
     * Obtenir les statistiques de surveillance
     */
    public function getMonitoringStats()
    {
        return [
            'total_alerts' => PriceAlert::count(),
            'unresolved_alerts' => PriceAlert::unresolved()->count(),
            'critical_alerts' => PriceAlert::unresolved()->bySeverity(PriceAlert::SEVERITY_CRITICAL)->count(),
            'high_alerts' => PriceAlert::unresolved()->bySeverity(PriceAlert::SEVERITY_HIGH)->count(),
            'recent_alerts' => PriceAlert::recent(7)->count(),
            'price_increases' => PriceAlert::recent(7)->byAlertType(PriceAlert::TYPE_PRICE_INCREASE)->count(),
            'price_decreases' => PriceAlert::recent(7)->byAlertType(PriceAlert::TYPE_PRICE_DECREASE)->count(),
        ];
    }

    /**
     * Analyser les tendances de prix
     */
    public function analyzePriceTrends($days = 30)
    {
        $trends = PriceAlert::recent($days)
            ->selectRaw('
                product_category,
                alert_type,
                AVG(price_change_percentage) as avg_change,
                COUNT(*) as alert_count
            ')
            ->groupBy('product_category', 'alert_type')
            ->get();

        return $trends;
    }
}
