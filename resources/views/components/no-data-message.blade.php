<!-- Composant pour afficher "Aucune donnée disponible" -->
<div class="no-data-container">
    <div class="no-data-content">
        <div class="no-data-icon">
            <i class="fas fa-inbox text-gray-400"></i>
        </div>
        <h3 class="no-data-title">Aucune donnée disponible pour le moment</h3>
        <p class="no-data-message">
            @if(isset($message))
                {{ $message }}
            @else
                Les données seront affichées ici dès qu'elles seront disponibles.
            @endif
        </p>
        @if(isset($action) && isset($actionUrl))
            <a href="{{ $actionUrl }}" class="no-data-action">
                <i class="fas fa-plus mr-2"></i>
                {{ $action }}
            </a>
        @endif
    </div>
</div>

<style>
.no-data-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    padding: 2rem;
}

.no-data-content {
    text-align: center;
    max-width: 400px;
}

.no-data-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-data-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.no-data-message {
    color: #6b7280;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.no-data-action {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: background-color 0.2s ease;
}

.no-data-action:hover {
    background-color: #2563eb;
    color: white;
}
</style>
