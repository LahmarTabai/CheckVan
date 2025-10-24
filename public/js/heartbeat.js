/**
 * Heartbeat Service pour CheckVan
 * Envoie un ping toutes les 15 secondes pour maintenir le statut online
 */

class HeartbeatService {
    constructor() {
        this.intervalId = null;
        this.isRunning = false;
        this.heartbeatInterval = 15000; // 15 secondes
        this.retryCount = 0;
        this.maxRetries = 3;
    }

    /**
     * Démarrer le heartbeat
     */
    start() {
        if (this.isRunning) {
            console.log('⏭️ Heartbeat déjà en cours');
            return;
        }

        console.log('💓 Démarrage du heartbeat service...');
        this.isRunning = true;

        // Envoyer immédiatement
        this.sendHeartbeat();

        // Puis toutes les 15 secondes
        this.intervalId = setInterval(() => {
            this.sendHeartbeat();
        }, this.heartbeatInterval);
    }

    /**
     * Arrêter le heartbeat
     */
    stop() {
        if (!this.isRunning) {
            return;
        }

        console.log('🛑 Arrêt du heartbeat service');
        this.isRunning = false;

        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }

        // Marquer explicitement comme offline
        this.markOffline();
    }

    /**
     * Envoyer un heartbeat au serveur
     */
    async sendHeartbeat() {
        try {
            // Optionnel : inclure la position GPS si disponible
            const payload = {
                timestamp: new Date().toISOString()
            };

            // Si la géolocalisation est disponible, l'inclure
            if (navigator.geolocation && window.includeGPSInHeartbeat) {
                const position = await this.getCurrentPosition();
                if (position) {
                    payload.latitude = position.coords.latitude;
                    payload.longitude = position.coords.longitude;
                    payload.accuracy = position.coords.accuracy;
                    payload.speed = position.coords.speed;
                    payload.heading = position.coords.heading;
                }
            }

            const response = await fetch('/api/heartbeat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                console.log('💓 Heartbeat envoyé:', data.message);
                this.retryCount = 0; // Reset retry count on success
            } else {
                console.error('❌ Heartbeat échoué:', response.status, response.statusText);
                this.handleError();
            }
        } catch (error) {
            console.error('❌ Erreur heartbeat:', error);
            this.handleError();
        }
    }

    /**
     * Marquer comme offline
     */
    async markOffline() {
        try {
            const response = await fetch('/api/heartbeat/offline', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                console.log('👋 Marqué comme offline');
            }
        } catch (error) {
            console.error('❌ Erreur marquage offline:', error);
        }
    }

    /**
     * Obtenir la position GPS actuelle (promesse)
     */
    getCurrentPosition() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) {
                resolve(null);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                position => resolve(position),
                error => {
                    console.log('GPS non disponible:', error.message);
                    resolve(null);
                },
                {
                    enableHighAccuracy: false,
                    timeout: 5000,
                    maximumAge: 30000
                }
            );
        });
    }

    /**
     * Gérer les erreurs de heartbeat
     */
    handleError() {
        this.retryCount++;

        if (this.retryCount >= this.maxRetries) {
            console.warn('⚠️ Trop d\'échecs heartbeat, arrêt temporaire...');
            this.stop();

            // Réessayer après 1 minute
            setTimeout(() => {
                console.log('🔄 Tentative de redémarrage du heartbeat...');
                this.retryCount = 0;
                this.start();
            }, 60000);
        }
    }
}

// Instance globale
window.heartbeatService = new HeartbeatService();

// Démarrage automatique au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Démarrer le heartbeat après 1 seconde
    setTimeout(() => {
        window.heartbeatService.start();
    }, 1000);
});

// Arrêter le heartbeat quand l'utilisateur quitte la page
window.addEventListener('beforeunload', function() {
    if (window.heartbeatService) {
        window.heartbeatService.stop();
    }
});

// Gérer le changement de visibilité de la page
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        console.log('📱 Page cachée, heartbeat continue en arrière-plan');
        // Le heartbeat continue même quand la page est cachée
    } else {
        console.log('📱 Page visible, heartbeat actif');
        // Vérifier si le heartbeat est toujours actif
        if (!window.heartbeatService.isRunning) {
            window.heartbeatService.start();
        }
    }
});

