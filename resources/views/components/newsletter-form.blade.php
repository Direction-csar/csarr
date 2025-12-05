<div class="newsletter-form-container" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border-radius: 16px; padding: 2rem; margin: 2rem 0; color: white;">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.5rem; color: white;">
            <i class="fas fa-envelope" style="margin-right: 8px;"></i>
            Restez informé
        </h3>
        <p style="margin: 0; opacity: 0.9; font-size: 1rem;">
            Abonnez-vous à notre newsletter pour recevoir les dernières actualités du CSAR
        </p>
    </div>
    
    <form id="newsletterForm" style="max-width: 500px; margin: 0 auto;">
        @csrf
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <input type="email" 
                   id="newsletterEmail" 
                   name="email" 
                   placeholder="Votre adresse email" 
                   required
                   style="flex: 1; min-width: 250px; padding: 12px 16px; border: none; border-radius: 8px; font-size: 1rem; background: white; color: #1f2937;">
            <button type="submit" 
                    id="newsletterSubmit"
                    style="padding: 12px 24px; background: #22c55e; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; white-space: nowrap;">
                <i class="fas fa-paper-plane" style="margin-right: 5px;"></i>
                S'abonner
            </button>
        </div>
    </form>
    
    <!-- Messages -->
    <div id="newsletterMessage" style="margin-top: 1rem; text-align: center; display: none;">
        <div id="newsletterMessageContent" style="padding: 12px; border-radius: 8px; font-weight: 500;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletterForm');
    const emailInput = document.getElementById('newsletterEmail');
    const submitBtn = document.getElementById('newsletterSubmit');
    const messageDiv = document.getElementById('newsletterMessage');
    const messageContent = document.getElementById('newsletterMessageContent');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 5px;"></i>En cours...';
        
        try {
            const response = await fetch('{{ route("newsletter.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: emailInput.value
                })
            });
            
            const data = await response.json();
            
            // Afficher le message
            messageDiv.style.display = 'block';
            messageContent.textContent = data.message;
            
            if (data.success) {
                messageContent.style.background = '#22c55e';
                messageContent.style.color = 'white';
                emailInput.value = ''; // Vider le champ
            } else {
                messageContent.style.background = '#ef4444';
                messageContent.style.color = 'white';
            }
            
            // Masquer le message après 5 secondes
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
            
        } catch (error) {
            console.error('Erreur:', error);
            messageDiv.style.display = 'block';
            messageContent.textContent = 'Une erreur est survenue. Veuillez réessayer.';
            messageContent.style.background = '#ef4444';
            messageContent.style.color = 'white';
            
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        } finally {
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane" style="margin-right: 5px;"></i>S\'abonner';
        }
    });
});
</script>
