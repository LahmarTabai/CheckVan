# 🟢 Système de Statut Online/Offline - CheckVan

## 📋 Vue d'ensemble

Ce système permet de suivre en temps réel le statut online/offline des chauffeurs avec une précision de **30 secondes**. Il remplace l'ancien système basé sur `recorded_at` (5 minutes) par un système de **heartbeat** moderne et fiable.

## 🏗️ Architecture

### Composants principaux

1. **Base de données** : 3 nouvelles colonnes dans `users`

    - `is_online` (boolean) : Statut actuel
    - `last_seen` (timestamp) : Dernière activité (toute requête)
    - `last_heartbeat` (timestamp) : Dernier ping heartbeat

2. **OnlineStatusService** : Service central pour gérer les statuts
3. **HeartbeatController** : API endpoints pour le mobile
4. **UpdateLastSeen** : Middleware qui met à jour automatiquement `last_seen`
5. **CleanupStaleOnlineStatuses** : Commande pour nettoyer les statuts périmés
6. **heartbeat.js** : Script JavaScript côté client

## 🔄 Fonctionnement

### 1. Heartbeat automatique (Mobile/Web)

**Côté Chauffeur (JavaScript):**

```javascript
// Le script heartbeat.js s'exécute automatiquement
// Envoie un ping toutes les 15 secondes à /api/heartbeat
window.heartbeatService.start();
```

**Côté Serveur (API):**

```php
POST /api/heartbeat
- Met à jour is_online = true
- Met à jour last_heartbeat = now()
- Met à jour last_seen = now()
- Optionnel : sauvegarde position GPS si fournie
```

### 2. Détection offline

Un chauffeur est considéré **offline** si :

-   `last_heartbeat` est NULL, OU
-   `last_heartbeat` date de **plus de 30 secondes**

**Seuil configuré dans :**

```php
OnlineStatusService::ONLINE_THRESHOLD = 30; // secondes
```

### 3. Nettoyage automatique

**Scheduler Laravel :** Exécute `online-status:cleanup` **toutes les minutes**

```php
// routes/console.php
Schedule::command('online-status:cleanup')->everyMinute();
```

**Action :** Marque automatiquement comme `is_online = false` tous les users avec `last_heartbeat` > 30 secondes

## 📡 Endpoints API

### POST /api/heartbeat

Envoyer un heartbeat (maintenir online)

**Request:**

```json
{
    "latitude": 48.8566, // Optionnel
    "longitude": 2.3522, // Optionnel
    "accuracy": 10.5, // Optionnel
    "speed": 15.2, // Optionnel
    "heading": 90 // Optionnel
}
```

**Response:**

```json
{
    "success": true,
    "message": "Heartbeat reçu",
    "data": {
        "is_online": true,
        "last_heartbeat": "2025-10-24T19:49:00.000000Z",
        "server_time": "2025-10-24T19:49:00.000000Z"
    }
}
```

### POST /api/heartbeat/offline

Marquer explicitement comme offline (logout)

**Response:**

```json
{
    "success": true,
    "message": "Marqué comme offline"
}
```

### GET /api/heartbeat/status

Vérifier son statut (debug)

**Response:**

```json
{
    "success": true,
    "data": {
        "user_id": 123,
        "is_online": true,
        "is_online_db": true,
        "last_heartbeat": "2025-10-24T19:49:00.000000Z",
        "last_seen": "2025-10-24T19:49:05.000000Z",
        "server_time": "2025-10-24T19:49:10.000000Z"
    }
}
```

## 🎯 Utilisation dans le code

### Vérifier si un user est online

```php
use App\Services\OnlineStatusService;

$service = app(OnlineStatusService::class);

// Vérifier un seul user
$isOnline = $service->isUserOnline($user);

// Obtenir tous les chauffeurs online d'un admin
$onlineChauffeurs = $service->getOnlineUsers($adminId, 'chauffeur');

// Obtenir les statistiques
$stats = $service->getOnlineStats($adminId);
// ['total' => 10, 'online' => 7, 'offline' => 3, 'online_percentage' => 70.0]
```

### Marquer manuellement comme online/offline

```php
// Marquer comme online (fait automatiquement par le heartbeat)
$service->markAsOnline($user);

// Marquer comme offline (fait automatiquement au logout)
$service->markAsOffline($user);
```

## 🗺️ Intégration avec la carte

**Dans `Map.php` (Livewire) :**

```php
// ✅ NOUVEAU : Système heartbeat (30s)
$isOnline = $this->onlineStatusService->isUserOnline($chauffeur);

if (!$isOnline) {
    $status = 'hors_ligne';
} elseif ($tache) {
    $status = 'en_cours';
} else {
    $status = 'disponible';
}
```

**Résultat :**

-   🟢 En ligne + en tâche = **En cours** (vert)
-   🔵 En ligne + pas de tâche = **Disponible** (bleu)
-   ⚫ Hors ligne = **Hors ligne** (gris, marqueur opaque)

## ⚙️ Configuration

### Modifier le seuil de détection

**Fichier :** `app/Services/OnlineStatusService.php`

```php
// Par défaut : 30 secondes
const ONLINE_THRESHOLD = 30;

// Pour 1 minute :
const ONLINE_THRESHOLD = 60;

// Pour 10 secondes (très réactif, mais plus de requêtes) :
const ONLINE_THRESHOLD = 10;
```

### Modifier la fréquence du heartbeat

**Fichier :** `public/js/heartbeat.js`

```javascript
// Par défaut : 15 secondes
this.heartbeatInterval = 15000;

// Pour 10 secondes :
this.heartbeatInterval = 10000;

// Pour 30 secondes :
this.heartbeatInterval = 30000;
```

### Modifier la fréquence du nettoyage

**Fichier :** `routes/console.php`

```php
// Par défaut : chaque minute
Schedule::command('online-status:cleanup')->everyMinute();

// Toutes les 30 secondes :
Schedule::command('online-status:cleanup')->everyThirtySeconds();

// Toutes les 5 minutes :
Schedule::command('online-status:cleanup')->everyFiveMinutes();
```

## 🚀 Démarrage du Scheduler

**IMPORTANT :** Pour que le nettoyage automatique fonctionne, le scheduler Laravel doit tourner en arrière-plan.

### Windows (Development)

```bash
# Terminal 1 : Serveur web
php artisan serve

# Terminal 2 : Scheduler (garde ouvert)
php artisan schedule:work
```

### Production (Linux/crontab)

```bash
# Ajouter dans crontab -e
* * * * * cd /path/to/checkvan && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Monitoring & Debug

### Commandes utiles

```bash
# Nettoyer manuellement les statuts périmés
php artisan online-status:cleanup

# Voir les logs
tail -f storage/logs/laravel.log | grep -i "online\|heartbeat"

# Tester l'API heartbeat (requiert authentification)
curl -X POST http://localhost:8000/api/heartbeat \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=..." \
  -d '{"latitude": 48.8566, "longitude": 2.3522}'
```

### Logs automatiques

Le service log automatiquement :

-   ✅ Chaque heartbeat reçu
-   ✅ Changements de statut online/offline
-   ✅ Nettoyages effectués

**Exemple :**

```
[2025-10-24 19:49:00] local.INFO: User 123 marked as online {"user_id":123,"role":"chauffeur","last_heartbeat":"2025-10-24 19:49:00"}
[2025-10-24 19:50:00] local.INFO: Cleaned up 2 stale online statuses
```

## 🔒 Sécurité

### Middleware automatique

Le middleware `UpdateLastSeen` s'exécute sur **toutes les requêtes** :

-   Met à jour `last_seen` (throttled à 1 minute)
-   Pas d'impact sur les performances (check en cache)

### Authentification requise

Tous les endpoints `/api/heartbeat/*` nécessitent une authentification via le middleware `web`.

### Rate limiting

Considérer l'ajout d'un rate limit sur `/api/heartbeat` :

```php
Route::post('/heartbeat', [HeartbeatController::class, 'store'])
    ->middleware('throttle:120,1'); // 120 requêtes/minute max
```

## 🎨 Interface utilisateur

### Carte admin

-   **Badge "Hors ligne (>30s)"** : Affiché dans le popup du marqueur
-   **Marqueur gris** : Couleur visuelle pour les chauffeurs offline
-   **Opacité réduite** : Animation pulse désactivée
-   **Filtre "Hors ligne"** : Checkbox pour masquer/afficher

### Liste latérale

-   **Avatar gris** : Pour les chauffeurs offline
-   **Badge "Hors ligne"** : Statut clair

## 🐛 Troubleshooting

### Problème : Chauffeurs restent online alors qu'ils sont déconnectés

**Cause :** Scheduler pas lancé

**Solution :**

```bash
php artisan schedule:work
```

### Problème : Heartbeat ne s'envoie pas

**Vérifier :**

1. Le fichier `public/js/heartbeat.js` est accessible
2. La console navigateur pour les erreurs JavaScript
3. Le CSRF token est présent dans le `<head>` :

```html
<meta name="csrf-token" content="{{ csrf_token() }}" />
```

### Problème : Tous les chauffeurs sont offline

**Vérifier :**

1. Le middleware `UpdateLastSeen` est enregistré
2. Les routes API fonctionnent : `php artisan route:list | grep heartbeat`
3. Les chauffeurs envoient bien le heartbeat (logs)

## 📈 Performance

### Cache

Le service utilise le cache Laravel pour réduire les requêtes DB :

-   Clé : `user_online_{user_id}`
-   TTL : 2 minutes
-   Invalidation automatique lors des changements

### Indexes DB

La migration crée automatiquement un index composite :

```sql
INDEX (is_online, last_seen)
```

**Impact :** Requêtes ultra-rapides même avec des milliers d'utilisateurs

## 🔮 Évolutions futures

### WebSockets (Recommandé)

Remplacer le polling heartbeat par des WebSockets :

-   Connexion persistante
-   Détection instantanée de déconnexion
-   Moins de requêtes HTTP
-   Implémentation : Laravel Reverb / Pusher / Soketi

### Service Worker (PWA)

Continuer le heartbeat même quand l'app est en arrière-plan.

### Historique de connexion

Stocker un historique des sessions :

```php
connection_logs: user_id, connected_at, disconnected_at, duration
```

---

## ✅ Résumé

| Composant               | Rôle                    | Fréquence      |
| ----------------------- | ----------------------- | -------------- |
| `heartbeat.js`          | Envoie ping au serveur  | 15s            |
| `/api/heartbeat`        | Marque user online      | À chaque ping  |
| `UpdateLastSeen`        | MAJ last_seen           | Chaque requête |
| `online-status:cleanup` | Nettoie statuts périmés | 1 minute       |
| `ONLINE_THRESHOLD`      | Seuil détection offline | 30s            |

**Résultat :** Détection offline en **30-60 secondes max** (30s seuil + 60s nettoyage) ⚡
