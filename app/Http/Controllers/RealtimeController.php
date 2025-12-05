<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicRequest;
use App\Models\Stock;
use App\Models\PriceAlert;
use App\Models\Personnel;
use App\Models\SmsNotification;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RealtimeController extends Controller
{
    public function getStats()
    {
        try {
            $today = Carbon::today();
            
            $stats = [
                'today_requests' => PublicRequest::whereDate('created_at', $today)->count(),
                'available_stock' => Stock::sum('quantity'),
                'active_alerts' => PriceAlert::where('status', 'active')->count(),
                'active_personnel' => Personnel::where('is_active', true)->count(),
                'processed_requests' => PublicRequest::where('status', 'processed')->count(),
                'pending_requests' => PublicRequest::where('status', 'pending')->count(),
                'total_warehouses' => \App\Models\Warehouse::count(),
                'unread_notifications' => SmsNotification::where('user_id', Auth::id())
                    ->where('is_read', false)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getActivities($limit = 10)
    {
        try {
            $activities = AuditLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'time' => $activity->created_at->format('H:i'),
                        'user' => $activity->user ? $activity->user->name : 'Système',
                        'action' => $activity->action,
                        'details' => $activity->details,
                        'status' => $activity->status,
                        'created_at' => $activity->created_at ? $activity->created_at->diffForHumans() : 'Date inconnue'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $activities,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des activités',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getNotifications($limit = 20)
    {
        try {
            $notifications = SmsNotification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at ? $notification->created_at->diffForHumans() : 'Date inconnue',
                        'sent_at' => $notification->sent_at ? $notification->sent_at->format('H:i') : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChartData($period = '7d')
    {
        try {
            $endDate = Carbon::now();
            $startDate = $this->getStartDate($period, $endDate);

            $requests = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = [];
            $data = [];
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');
                $labels[] = $currentDate->format('d/m');
                
                $requestCount = $requests->where('date', $dateStr)->first();
                $data[] = $requestCount ? $requestCount->count : 0;
                
                $currentDate->addDay();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Demandes',
                            'data' => $data,
                            'borderColor' => 'rgb(75, 192, 192)',
                            'backgroundColor' => 'rgba(75, 192, 192, 0.2)'
                        ]
                    ]
                ],
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données de graphique',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRegionData()
    {
        try {
            $regions = PublicRequest::selectRaw('region, COUNT(*) as count')
                ->whereNotNull('region')
                ->groupBy('region')
                ->orderBy('count', 'desc')
                ->get();

            $labels = $regions->pluck('region')->toArray();
            $data = $regions->pluck('count')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'data' => $data,
                            'backgroundColor' => [
                                '#3B82F6',
                                '#10B981',
                                '#F59E0B',
                                '#EF4444',
                                '#8B5CF6',
                                '#06B6D4',
                                '#84CC16'
                            ]
                        ]
                    ]
                ],
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données régionales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markNotificationAsRead($id)
    {
        try {
            $notification = SmsNotification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notification->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marquée comme lue'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAllNotificationsAsRead()
    {
        try {
            SmsNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $count = SmsNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du compteur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getStartDate($period, $endDate)
    {
        switch ($period) {
            case '1d':
                return $endDate->copy()->subDay();
            case '7d':
                return $endDate->copy()->subWeek();
            case '30d':
                return $endDate->copy()->subMonth();
            case '90d':
                return $endDate->copy()->subMonths(3);
            default:
                return $endDate->copy()->subWeek();
        }
    }
}






