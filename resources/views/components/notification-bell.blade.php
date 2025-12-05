<button class="btn btn-link position-relative notification-bell-btn" 
        id="notificationBellBtn" 
        type="button" 
        data-bs-toggle="dropdown" 
        aria-expanded="false">
    <i class="fas fa-bell fs-5"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" 
          id="notificationBadge" 
          style="display: none;">
        0
    </span>
</button>

<style>
.notification-bell-btn {
    color: #6c757d;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.notification-bell-btn:hover {
    color: #495057;
    transform: scale(1.1);
}

.notification-bell-btn:hover .fa-bell {
    animation: bellRing 0.5s ease-in-out;
}

@keyframes bellRing {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}

.notification-badge {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>
