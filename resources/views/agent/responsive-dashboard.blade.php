@extends('layouts.responsive-admin', ['role' => 'agent'])

@section('title', 'Tableau de Bord Agent - CSAR Responsive')

@section('page-title', 'Mon Tableau de Bord')
@section('page-subtitle', 'Espace personnel CSAR')

@section('sidebar-navigation')
<!-- Navigation principale -->
<div class="nav-section">
    <div class="nav-section-title">Mon espace</div>
    
    <a href="{{ route('agent.dashboard') }}" class="nav-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <span class="nav-text">Tableau de bord</span>
    </a>
    
    <a href="{{ route('agent.profile') }}" class="nav-item {{ request()->routeIs('agent.profile.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <span class="nav-text">Mon profil</span>
    </a>
    
    <a href="{{ route('agent.documents') }}" class="nav-item {{ request()->routeIs('agent.documents.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-file-alt"></i>
        <span class="nav-text">Mes documents</span>
    </a>
</div>

<!-- RH -->
<div class="nav-section">
    <div class="nav-section-title">Ressources humaines</div>
    
    <a href="{{ route('agent.attendance') }}" class="nav-item {{ request()->routeIs('agent.attendance.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clock"></i>
        <span class="nav-text">Pointage</span>
    </a>
    
    <a href="{{ route('agent.salary') }}" class="nav-item {{ request()->routeIs('agent.salary.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-bill-wave"></i>
        <span class="nav-text">Bulletins de salaire</span>
    </a>
    
    <a href="{{ route('agent.leaves') }}" class="nav-item {{ request()->routeIs('agent.leaves.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-times"></i>
        <span class="nav-text">Congés</span>
    </a>
</div>

<!-- Communication -->
<div class="nav-section">
    <div class="nav-section-title">Communication</div>
    
    <a href="{{ route('agent.messages') }}" class="nav-item {{ request()->routeIs('agent.messages.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-envelope"></i>
        <span class="nav-text">Messages</span>
        @php($unreadMessages = \App\Models\ContactMessage::where('recipient_id', auth()->id())->where('is_read', false)->count())
        @if($unreadMessages)
            <span class="nav-badge">{{ $unreadMessages }}</span>
        @endif
    </a>
    
    <a href="{{ route('agent.announcements') }}" class="nav-item {{ request()->routeIs('agent.announcements.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bullhorn"></i>
        <span class="nav-text">Annonces</span>
    </a>
</div>
@endsection

@section('mobile-navigation')
<a href="{{ route('agent.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-home"></i>
    <span>Accueil</span>
</a>
<a href="{{ route('agent.profile') }}" class="mobile-nav-item {{ request()->routeIs('agent.profile.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-user"></i>
    <span>Profil</span>
</a>
<a href="{{ route('agent.documents') }}" class="mobile-nav-item {{ request()->routeIs('agent.documents.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-file-alt"></i>
    <span>Documents</span>
</a>
<a href="{{ route('agent.attendance') }}" class="mobile-nav-item {{ request()->routeIs('agent.attendance.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-clock"></i>
    <span>Pointage</span>
</a>
<a href="{{ route('agent.salary') }}" class="mobile-nav-item {{ request()->routeIs('agent.salary.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-money-bill-wave"></i>
    <span>Salaire</span>
</a>
@endsection

@section('header-actions')
<div class="flex items-center space-x-4">
    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
        <i class="fas fa-calendar"></i>
        <span>{{ now()->format('d/m/Y') }}</span>
    </div>
    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
        <i class="fas fa-clock"></i>
        <span id="current-time"></span>
    </div>
</div>
@endsection

@section('content')
<!-- Welcome Card -->
<div class="responsive-card mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-csar-green-100 rounded-full flex items-center justify-center">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                @else
                    <i class="fas fa-user text-2xl text-csar-green-600"></i>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }} !</h2>
                <p class="text-gray-600">{{ auth()->user()->poste ?? 'Agent CSAR' }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->warehouse->name ?? 'Entrepôt CSAR' }}</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center space-x-4 text-sm text-gray-600">
            <div class="text-center">
                <div class="font-semibold text-csar-green-600">{{ $stats['days_worked'] ?? 0 }}</div>
                <div>Jours travaillés</div>
            </div>
            <div class="text-center">
                <div class="font-semibold text-blue-600">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                <div>Taux présence</div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="stats-grid">
    <!-- Pointage aujourd'hui -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['today_status'] ?? 'Non pointé' }}</div>
                <div class="stat-card-label">Pointage aujourd'hui</div>
                <div class="stat-details">
                    @if($stats['check_in_time'] ?? null)
                        <span class="badge badge-success">Arrivée: {{ $stats['check_in_time'] }}</span>
                    @else
                        <span class="badge badge-pending">En attente</span>
                    @endif
                </div>
            </div>
            <div class="stat-card-icon bg-blue-500">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <!-- Heures travaillées -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['hours_worked'] ?? 0 }}h</div>
                <div class="stat-card-label">Heures travaillées</div>
                <div class="stat-details">
                    <span class="badge badge-success">Cette semaine</span>
                </div>
            </div>
            <div class="stat-card-icon bg-green-500">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>
    
    <!-- Messages non lus -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['unread_messages'] ?? 0 }}</div>
                <div class="stat-card-label">Messages non lus</div>
                <div class="stat-details">
                    <span class="badge badge-pending">À consulter</span>
                </div>
            </div>
            <div class="stat-card-icon bg-orange-500">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
    </div>
    
    <!-- Documents disponibles -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['available_documents'] ?? 0 }}</div>
                <div class="stat-card-label">Documents disponibles</div>
                <div class="stat-details">
                    <span class="badge badge-stock">À télécharger</span>
                </div>
            </div>
            <div class="stat-card-icon bg-purple-500">
                <i class="fas fa-file-download"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="responsive-grid-2 gap-6 sm:gap-8 mb-8">
    <!-- Présence mensuelle -->
    <div class="chart-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Présence mensuelle</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Jours présents</span>
            </div>
        </div>
        <canvas id="attendanceChart" width="400" height="200"></canvas>
    </div>
    
    <!-- Répartition des heures -->
    <div class="chart-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Répartition des heures</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Heures travaillées</span>
            </div>
        </div>
        <canvas id="hoursChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- Content Grid -->
<div class="responsive-grid-2 gap-6 sm:gap-8">
    <!-- Messages récents -->
    <div class="responsive-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Messages récents</h3>
            <a href="{{ route('agent.messages') }}" class="text-sm text-csar-green-600 hover:text-csar-green-700">
                Voir tout
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recent_messages ?? [] as $message)
                <div class="flex items-start space-x-3 p-3 {{ $message['is_read'] ? 'bg-gray-50' : 'bg-blue-50' }} rounded-lg">
                    <div class="w-2 h-2 {{ $message['is_read'] ? 'bg-gray-400' : 'bg-blue-500' }} rounded-full mt-2"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $message['subject'] }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $message['excerpt'] }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $message['created_at']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Aucun message récent</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Documents récents -->
    <div class="responsive-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Documents récents</h3>
            <a href="{{ route('agent.documents') }}" class="text-sm text-csar-green-600 hover:text-csar-green-700">
                Voir tout
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recent_documents ?? [] as $document)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-csar-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-file-pdf text-csar-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $document['name'] }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $document['type'] }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $document['created_at']->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ $document['download_url'] }}" class="text-csar-green-600 hover:text-csar-green-700">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-2"></i>
                    <p>Aucun document récent</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="responsive-card mt-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
    <div class="responsive-grid-2 lg:responsive-grid-4 gap-4">
        @if(!($stats['check_in_time'] ?? null))
            <a href="{{ route('agent.attendance.check-in') }}" class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <div class="text-center">
                    <i class="fas fa-sign-in-alt text-2xl text-green-600 mb-2"></i>
                    <p class="text-sm font-medium text-green-900">Pointer arrivée</p>
                </div>
            </a>
        @elseif(!($stats['check_out_time'] ?? null))
            <a href="{{ route('agent.attendance.check-out') }}" class="flex items-center justify-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                <div class="text-center">
                    <i class="fas fa-sign-out-alt text-2xl text-red-600 mb-2"></i>
                    <p class="text-sm font-medium text-red-900">Pointer sortie</p>
                </div>
            </a>
        @endif
        
        <a href="{{ route('agent.documents') }}" class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-file-download text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm font-medium text-blue-900">Mes documents</p>
            </div>
        </a>
        
        <a href="{{ route('agent.messages') }}" class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-envelope text-2xl text-orange-600 mb-2"></i>
                <p class="text-sm font-medium text-orange-900">Messages</p>
            </div>
        </a>
        
        <a href="{{ route('agent.profile') }}" class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-user-edit text-2xl text-purple-600 mb-2"></i>
                <p class="text-sm font-medium text-purple-900">Mon profil</p>
            </div>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);
    
    // Initialize charts with responsive configuration
    const attendanceData = @json($attendance_chart_data ?? []);
    const hoursData = @json($hours_chart_data ?? []);
    
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx && attendanceData.length > 0) {
        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: attendanceData.map(item => item.week),
                datasets: [{
                    label: 'Jours présents',
                    data: attendanceData.map(item => item.days_present),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Hours Chart
    const hoursCtx = document.getElementById('hoursChart');
    if (hoursCtx && hoursData.length > 0) {
        new Chart(hoursCtx, {
            type: 'doughnut',
            data: {
                labels: hoursData.map(item => item.category),
                datasets: [{
                    data: hoursData.map(item => item.hours),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection

