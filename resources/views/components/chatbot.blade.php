<!-- Chatbot CSAR -->
<div id="csar-chatbot" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: Arial, sans-serif;">
    
    <!-- Bouton du chatbot -->
    <div id="chatbot-button" style="width: 60px; height: 60px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: transform 0.3s ease;">
        <span style="color: white; font-size: 24px;">🤖</span>
    </div>
    
    <!-- Fenêtre du chatbot -->
    <div id="chatbot-window" style="position: absolute; bottom: 80px; right: 0; width: 320px; height: 400px; background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); display: none; flex-direction: column; overflow: hidden;">
        
        <!-- Header du chatbot -->
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 12px; display: flex; align-items: center; gap: 8px;">
            <div style="width: 32px; height: 32px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 16px;">🤖</span>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 14px; font-weight: 600;">CSAR Chatbot</h3>
                <p style="margin: 0; font-size: 11px; opacity: 0.9;">En ligne • Prêt à vous aider</p>
            </div>
            <button id="close-chatbot" style="margin-left: auto; background: none; border: none; color: white; cursor: pointer; font-size: 16px; padding: 4px;">✕</button>
        </div>
        
        <!-- Zone de messages -->
        <div id="chat-messages" style="flex: 1; padding: 12px; overflow-y: auto; background: #f8fafc;">
            <!-- Message de bienvenue -->
            <div class="welcome-message" style="margin-bottom: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 6px;">
                    <div style="width: 28px; height: 28px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="color: white; font-size: 12px;">🤖</span>
                    </div>
                    <div style="background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); max-width: 240px;">
                        <p style="margin: 0; font-size: 13px; color: #374151;">Bonjour ! Je suis l'assistant virtuel de CSAR. Comment puis-je vous aider ?</p>
                    </div>
                </div>
            </div>
            
            <!-- Options rapides -->
            <div class="quick-options" style="margin-bottom: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 6px;">
                    <div style="width: 28px; height: 28px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="color: white; font-size: 12px;">🤖</span>
                    </div>
                    <div style="background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); max-width: 240px;">
                        <p style="margin: 0 0 6px 0; font-size: 12px; color: #374151;">Options rapides :</p>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <button class="quick-option" data-option="demande-aide" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">📋 Demande d'aide</button>
                            <button class="quick-option" data-option="suivre-demande" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">📱 Suivre demande</button>
                            <button class="quick-option" data-option="delais" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">⏱️ Délais</button>
                            <button class="quick-option" data-option="urgence" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">🚨 Urgence</button>
                            <button class="quick-option" data-option="contact" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">📞 Contact</button>
                            <button class="quick-option" data-option="adresse" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">📍 Adresse</button>
                            <button class="quick-option" data-option="services" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">🛠️ Services</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Zone de saisie -->
        <div style="padding: 12px; border-top: 1px solid #e5e7eb; background: white;">
            <div style="display: flex; gap: 6px;">
                <input type="text" id="chat-input" placeholder="Tapez votre message..." 
                       style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; outline: none;">
                <button id="send-message" style="background: #059669; color: white; border: none; border-radius: 6px; padding: 10px 12px; cursor: pointer; font-size: 14px; transition: background-color 0.2s;">📤</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour le chatbot */
#csar-chatbot {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#chatbot-button:hover {
    transform: scale(1.1);
}

.quick-option:hover {
    background-color: #e5e7eb !important;
}

#send-message:hover {
    background-color: #047857 !important;
}

#chat-input:focus {
    border-color: #059669 !important;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

/* Animation d'apparition */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation de frappe */
@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

#chatbot-window {
    animation: slideIn 0.3s ease;
}

/* Scrollbar personnalisée */
#chat-messages::-webkit-scrollbar {
    width: 6px;
}

#chat-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotButton = document.getElementById('chatbot-button');
    const chatbotWindow = document.getElementById('chatbot-window');
    const closeChatbot = document.getElementById('close-chatbot');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendMessage = document.getElementById('send-message');
    const quickOptions = document.querySelectorAll('.quick-option');

    // Variables pour gérer l'état du chatbot
    let conversationHistory = [];
    let isTyping = false;

    // Fonction pour effacer tous les messages sauf le message de bienvenue
    function clearConversation() {
        const welcomeMessage = document.querySelector('.welcome-message');
        const quickOptionsDiv = document.querySelector('.quick-options');
        
        // Supprimer tous les messages sauf le message de bienvenue et les options rapides
        const allMessages = chatMessages.querySelectorAll('div');
        allMessages.forEach(message => {
            if (!message.classList.contains('welcome-message') && !message.classList.contains('quick-options')) {
                message.remove();
            }
        });
        
        conversationHistory = [];
    }

    // Fonction pour afficher l'effet de frappe
    function showTypingIndicator() {
        if (isTyping) return;
        
        isTyping = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.style.marginBottom = '12px';
        typingDiv.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 6px;">
                <div style="width: 28px; height: 28px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="color: white; font-size: 12px;">🤖</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); max-width: 240px;">
                    <div style="display: flex; gap: 3px;">
                        <div style="width: 6px; height: 6px; background: #059669; border-radius: 50%; animation: typing 1.4s infinite ease-in-out;"></div>
                        <div style="width: 6px; height: 6px; background: #059669; border-radius: 50%; animation: typing 1.4s infinite ease-in-out 0.2s;"></div>
                        <div style="width: 6px; height: 6px; background: #059669; border-radius: 50%; animation: typing 1.4s infinite ease-in-out 0.4s;"></div>
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Fonction pour masquer l'effet de frappe
    function hideTypingIndicator() {
        const typingIndicator = document.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
        isTyping = false;
    }

    // Ouvrir/fermer le chatbot
    chatbotButton.addEventListener('click', function() {
        if (chatbotWindow.style.display === 'none' || chatbotWindow.style.display === '') {
            chatbotWindow.style.display = 'flex';
            chatInput.focus();
        } else {
            chatbotWindow.style.display = 'none';
            // Effacer la conversation quand on ferme le chatbot
            setTimeout(clearConversation, 300);
        }
    });

    closeChatbot.addEventListener('click', function() {
        chatbotWindow.style.display = 'none';
        // Effacer la conversation quand on ferme le chatbot
        setTimeout(clearConversation, 300);
    });

    // Envoyer un message
    function sendUserMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.style.marginBottom = '12px';
        messageDiv.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 6px; justify-content: flex-end;">
                <div style="background: #059669; padding: 10px; border-radius: 10px; max-width: 240px;">
                    <p style="margin: 0; font-size: 13px; color: white;">${message}</p>
                </div>
                <div style="width: 28px; height: 28px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="color: white; font-size: 12px;">👤</span>
                </div>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Ajouter à l'historique
        conversationHistory.push({ type: 'user', message: message });
    }

    function sendBotMessage(message, options = null) {
        const messageDiv = document.createElement('div');
        messageDiv.style.marginBottom = '12px';
        
        let optionsHtml = '';
        if (options) {
            optionsHtml = `
                <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 6px;">
                    ${options.map(option => `
                        <button class="quick-option" data-option="${option.value}" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; text-align: left; cursor: pointer; font-size: 12px; color: #374151; transition: background-color 0.2s;">${option.text}</button>
                    `).join('')}
                </div>
            `;
        }

        messageDiv.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 6px;">
                <div style="width: 28px; height: 28px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span style="color: white; font-size: 12px;">🤖</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); max-width: 240px;">
                    <p style="margin: 0; font-size: 13px; color: #374151;">${message}</p>
                    ${optionsHtml}
                </div>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Ajouter à l'historique
        conversationHistory.push({ type: 'bot', message: message, options: options });

        // Réattacher les événements aux nouveaux boutons
        if (options) {
            const newOptions = messageDiv.querySelectorAll('.quick-option');
            newOptions.forEach(option => {
                option.addEventListener('click', function() {
                    handleQuickOption(this.dataset.option);
                });
            });
        }
    }

        // Gérer les options rapides
    function handleQuickOption(option) {
        switch(option) {
            case 'demande-aide':
                sendUserMessage('Comment faire une demande d\'aide ?');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('Pour faire une demande d\'aide, vous avez plusieurs options :\n\n1️⃣ **En ligne** : Rendez-vous sur notre page "Action" et remplissez le formulaire de demande\n\n2️⃣ **Par téléphone** : Appelez notre ligne d\'urgence au +221 77 645 92 42\n\n3️⃣ **En personne** : Visitez notre siège au 22 Rue Amadou Assane NDOYE X Béranger Féraud, Dakar\n\nQuelle option préférez-vous ?', [
                        {value: 'action-page', text: '📋 Aller à la page Action'},
                        {value: 'urgence-phone', text: '📞 Numéro d\'urgence'},
                        {value: 'adresse', text: '📍 Notre adresse'}
                    ]);
                }, 1500);
                break;

            case 'suivre-demande':
                sendUserMessage('Comment suivre ma demande ?');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('Pour suivre votre demande :\n\n📱 **Par SMS** : Vous recevrez un code de suivi par SMS après soumission de votre demande\n\n🌐 **En ligne** : Utilisez ce code sur notre page de suivi pour voir l\'état de votre demande\n\n📞 **Par téléphone** : Appelez-nous avec votre code de suivi\n\nAvez-vous déjà reçu votre code de suivi ?', [
                        {value: 'track-page', text: '🔍 Page de suivi'},
                        {value: 'contact-support', text: '📞 Contacter le support'}
                    ]);
                }, 1500);
                break;

            case 'delais':
                sendUserMessage('Quels sont les délais de traitement ?');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('Les délais de traitement varient selon le type de demande :\n\n🚨 **Urgences alimentaires** : 24-48 heures\n\n📋 **Demandes standard** : 3-5 jours ouvrables\n\n📄 **Demandes complexes** : 7-10 jours ouvrables\n\n⏱️ **Suivi en temps réel** : Vous recevrez des notifications par SMS à chaque étape\n\nVoulez-vous plus de détails sur un type spécifique ?', [
                        {value: 'urgence-details', text: '🚨 Urgences alimentaires'},
                        {value: 'standard-details', text: '📋 Demandes standard'},
                        {value: 'complex-details', text: '📄 Demandes complexes'}
                    ]);
                }, 1500);
                break;

            case 'urgence':
                sendUserMessage('Urgence alimentaire');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('🚨 **URGENCE ALIMENTAIRE**\n\nPour les situations d\'urgence alimentaire :\n\n📞 **Ligne d\'urgence** : +221 77 645 92 42\n\n⏰ **Disponible** : 24h/24 et 7j/7\n\n📍 **Intervention rapide** : Notre équipe peut intervenir dans les 24h\n\nVoulez-vous que je vous connecte directement ?', [
                        {value: 'call-urgence', text: '📞 Appeler maintenant'},
                        {value: 'urgence-form', text: '📋 Formulaire d\'urgence'}
                    ]);
                }, 1500);
                break;

            case 'contact':
                sendUserMessage('Contacter CSAR');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('📞 **CONTACTER CSAR**\n\nVoici nos coordonnées :\n\n📧 **Email** : contact@csar.sn\n\n📞 **Téléphone** : +221 77 645 92 42\n\n🕒 **Horaires** : Lun-Ven 8h00-17h00\n\n📍 **Adresse** : 22 Rue Amadou Assane NDOYE X Béranger Féraud, Dakar\n\nQue souhaitez-vous faire ?', [
                        {value: 'contact-form', text: '📝 Formulaire de contact'},
                        {value: 'directions', text: '🗺️ Comment venir ?'}
                    ]);
                }, 1500);
                break;

            case 'localisation':
                sendUserMessage('Notre localisation');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('🗺️ **NOTRE LOCALISATION**\n\n📍 **Adresse** : 22 Rue Amadou Assane NDOYE X Béranger Féraud\n📮 **BP** : 170 Dakar, Sénégal\n\n🚗 **En voiture** : Parking disponible sur place\n🚌 **En transport** : Lignes de bus 10, 15, 23\n🚶 **À pied** : 5 min du centre-ville\n\nVoulez-vous des directions détaillées ?', [
                        {value: 'google-maps', text: '🗺️ Ouvrir Google Maps'},
                        {value: 'directions-detail', text: '🚗 Itinéraire détaillé'}
                    ]);
                }, 1500);
                break;

            case 'services':
                sendUserMessage('Nos services');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('🛠️ **NOS SERVICES**\n\n🔸 **Aide alimentaire d\'urgence**\n🔸 **Suivi des demandes en ligne**\n🔸 **Assistance téléphonique 24h/24**\n🔸 **Accompagnement personnalisé**\n🔸 **Formation et sensibilisation**\n🔸 **Coordination avec les partenaires**\n\nQuel service vous intéresse le plus ?', [
                        {value: 'aide-urgence', text: '🚨 Aide d\'urgence'},
                        {value: 'formation', text: '📚 Formation'},
                        {value: 'partenariat', text: '🤝 Partenariat'}
                    ]);
                }, 1500);
                break;

            case 'action-page':
                sendBotMessage('📋 **Page Action**\n\nRendez-vous sur notre page Action pour faire votre demande d\'aide en ligne. Le formulaire est simple et rapide à remplir.\n\nVoulez-vous que je vous guide dans le processus ?');
                break;

            case 'track-page':
                sendBotMessage('🔍 **Page de Suivi**\n\nUtilisez votre code de suivi reçu par SMS pour suivre l\'état de votre demande en temps réel.\n\nSi vous n\'avez pas reçu de code, contactez-nous au +221 77 645 92 42');
                break;

            case 'call-urgence':
                sendBotMessage('📞 **Appel d\'urgence**\n\nComposez immédiatement le +221 77 645 92 42\n\nNotre équipe d\'urgence est disponible 24h/24 pour vous aider.');
                break;

            case 'adresse':
                sendUserMessage('Notre adresse');
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    sendBotMessage('📍 **NOTRE ADRESSE**\n\n22 Rue Amadou Assane NDOYE X Béranger Féraud\nBP 170 Dakar, Sénégal\n\nCliquez sur le lien ci-dessous pour ouvrir directement dans Google Maps :', [
                        {value: 'open-maps-direct', text: '🗺️ Ouvrir dans Google Maps'}
                    ]);
                }, 1500);
                break;

            case 'open-maps-direct':
                sendBotMessage('🗺️ **Ouverture de Google Maps**\n\nCliquez sur le lien ci-dessous pour ouvrir Google Maps avec notre localisation exacte :\n\n<a href="https://maps.google.com/?q=22+Rue+Amadou+Assane+NDOYE+X+Béranger+Féraud+BP+170+Dakar+Sénégal" target="_blank" style="color: #059669; text-decoration: underline; font-weight: 600;">📍 Ouvrir dans Google Maps</a>\n\nL\'adresse sera automatiquement localisée et vous pourrez obtenir des directions.');
                break;

            case 'google-maps':
                sendBotMessage('🗺️ **Google Maps**\n\nCliquez sur le lien ci-dessous pour ouvrir Google Maps avec notre localisation :\n\n<a href="https://maps.google.com/?q=22+Rue+Amadou+Assane+NDOYE+X+Béranger+Féraud+BP+170+Dakar+Sénégal" target="_blank" style="color: #059669; text-decoration: underline;">📍 Ouvrir dans Google Maps</a>');
                break;

            case 'directions-detail':
                sendBotMessage('🚗 **Itinéraire détaillé**\n\n**Depuis le centre-ville :**\n• Prenez l\'avenue Georges Bush\n• Tournez à droite sur la rue Amadou Assane NDOYE\n• Nous sommes à 200m sur votre gauche\n\n**Depuis l\'aéroport :**\n• Prenez la route de l\'aéroport\n• Continuez sur la VDN\n• Sortez à la sortie 5\n• Suivez les panneaux CSAR');
                break;

            case 'aide-urgence':
                sendBotMessage('🚨 **Aide alimentaire d\'urgence**\n\nNotre service d\'urgence intervient dans les 24h pour :\n\n• Distribution de kits alimentaires\n• Soutien nutritionnel\n• Coordination avec les autorités\n• Suivi post-intervention\n\n📞 **Contact urgent** : +221 77 645 92 42');
                break;

            case 'formation':
                sendBotMessage('📚 **Formation et sensibilisation**\n\nNous proposons des formations sur :\n\n• Sécurité alimentaire\n• Nutrition équilibrée\n• Gestion des stocks alimentaires\n• Prévention des crises\n\n📧 **Contact formation** : formation@csar.sn');
                break;

            case 'partenariat':
                sendBotMessage('🤝 **Partenariat**\n\nNous collaborons avec :\n\n• Organisations internationales\n• ONG locales\n• Institutions gouvernementales\n• Secteur privé\n\n📧 **Contact partenariat** : partenariat@csar.sn');
                break;

            default:
                sendBotMessage('Je ne comprends pas votre demande. Pouvez-vous reformuler ou choisir une option dans la liste ?');
        }
    }

    // Événements pour les options rapides
    quickOptions.forEach(option => {
        option.addEventListener('click', function() {
            handleQuickOption(this.dataset.option);
        });
    });

    // Envoyer un message avec Entrée
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            const userMessage = this.value.trim();
            sendUserMessage(userMessage);
            this.value = '';
            
            // Effet de frappe
            showTypingIndicator();
            
            // Réponse intelligente basée sur le message
            setTimeout(() => {
                hideTypingIndicator();
                let response = '';
                
                if (userMessage.toLowerCase().includes('aide') || userMessage.toLowerCase().includes('demande')) {
                    response = 'Pour faire une demande d\'aide, vous pouvez :\n\n1️⃣ Utiliser notre formulaire en ligne\n2️⃣ Nous appeler au +221 77 645 92 42\n3️⃣ Visiter notre siège\n\nQuelle option préférez-vous ?';
                } else if (userMessage.toLowerCase().includes('suivre') || userMessage.toLowerCase().includes('suivi')) {
                    response = 'Pour suivre votre demande, utilisez le code de suivi reçu par SMS sur notre page de suivi en ligne.';
                } else if (userMessage.toLowerCase().includes('urgence') || userMessage.toLowerCase().includes('urgent')) {
                    response = '🚨 **URGENCE**\n\nPour les urgences alimentaires, appelez immédiatement :\n📞 +221 77 645 92 42\n\nNotre équipe intervient dans les 24h.';
                } else if (userMessage.toLowerCase().includes('adresse') || userMessage.toLowerCase().includes('localisation')) {
                    response = '📍 **Notre adresse** :\n22 Rue Amadou Assane NDOYE X Béranger Féraud\nBP 170 Dakar, Sénégal\n\nVoulez-vous ouvrir directement dans Google Maps ?';
                } else {
                    response = 'Merci pour votre message. Un membre de notre équipe vous répondra dans les plus brefs délais. En attendant, pouvez-vous choisir une option rapide ci-dessus ?';
                }
                
                sendBotMessage(response);
            }, 1500);
        }
    });

    // Envoyer un message avec le bouton
    sendMessage.addEventListener('click', function() {
        if (chatInput.value.trim()) {
            const userMessage = chatInput.value.trim();
            sendUserMessage(userMessage);
            chatInput.value = '';
            
            // Effet de frappe
            showTypingIndicator();
            
            // Réponse intelligente basée sur le message
            setTimeout(() => {
                hideTypingIndicator();
                let response = '';
                
                if (userMessage.toLowerCase().includes('aide') || userMessage.toLowerCase().includes('demande')) {
                    response = 'Pour faire une demande d\'aide, vous pouvez :\n\n1️⃣ Utiliser notre formulaire en ligne\n2️⃣ Nous appeler au +221 77 645 92 42\n3️⃣ Visiter notre siège\n\nQuelle option préférez-vous ?';
                } else if (userMessage.toLowerCase().includes('suivre') || userMessage.toLowerCase().includes('suivi')) {
                    response = 'Pour suivre votre demande, utilisez le code de suivi reçu par SMS sur notre page de suivi en ligne.';
                } else if (userMessage.toLowerCase().includes('urgence') || userMessage.toLowerCase().includes('urgent')) {
                    response = '🚨 **URGENCE**\n\nPour les urgences alimentaires, appelez immédiatement :\n📞 +221 77 645 92 42\n\nNotre équipe intervient dans les 24h.';
                } else if (userMessage.toLowerCase().includes('adresse') || userMessage.toLowerCase().includes('localisation')) {
                    response = '📍 **Notre adresse** :\n22 Rue Amadou Assane NDOYE X Béranger Féraud\nBP 170 Dakar, Sénégal\n\nVoulez-vous ouvrir directement dans Google Maps ?';
                } else {
                    response = 'Merci pour votre message. Un membre de notre équipe vous répondra dans les plus brefs délais. En attendant, pouvez-vous choisir une option rapide ci-dessus ?';
                }
                
                sendBotMessage(response);
            }, 1500);
        }
    });
});
</script> 