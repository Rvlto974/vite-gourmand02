# Documentation Technique - Vite et Gourmand

## 🏗️ Architecture
Frontend (Vite + Vue 3)
    ↓
API PHP (RESTful)
    ↓
Database (SQLite/MySQL)

## 🗄️ Base de Données

### Schéma Principal

```sql
utilisateurs (id, email, mot_de_passe, role, nom, adresse, created_at)
menus (id, titre, description, prix, regime, image, active, created_at)
plats (id, menu_id, nom, description, type, allergenes)
commandes (id, utilisateur_id, menu_id, date_livraison, adresse, statut)
avis (id, utilisateur_id, commande_id, note, commentaire, valide, created_at)
Relations

1 utilisateur → N commandes
1 menu → N plats
1 menu → N commandes
1 commande → 1 avis

🔌 API Endpoints
Authentification

POST /auth/register → Inscription
POST /auth/login → Connexion
POST /auth/logout → Déconnexion
GET /auth/me → Utilisateur courant

Menus

GET /menus → Liste tous
GET /menus?regime=vegetarien → Filtrer
GET /menus/{id} → Détail + plats
POST /menus → Admin only
PUT /menus/{id} → Admin only
DELETE /menus/{id} → Admin only

Commandes

POST /commandes → Créer
GET /commandes → Mes commandes
GET /commandes/{id} → Détail
PUT /commandes/{id}/statut → Employé
DELETE /commandes/{id} → Client (avant acceptation)

Avis

POST /avis → Laisser avis
GET /avis?valide=1 → Avis publics
GET /avis/{id} → Détail
PUT /avis/{id}/valide → Admin valide
DELETE /avis/{id} → Admin

Utilisateurs (Admin)

GET /utilisateurs → Tous
POST /utilisateurs → Créer
PUT /utilisateurs/{id}/role → Changer rôle
DELETE /utilisateurs/{id} → Supprimer

🔐 Authentification

Session PHP standard
Token JWT optionnel pour API
Mot de passe : password_hash(algo: 'bcrypt')
Email confirmation via link
CORS : localhost:5173 en dev

📦 Stack Technique
Frontend

Vite 5.0
Vue 3 (Composition API)
TailwindCSS
Pinia (state management)
Axios (HTTP client)

Backend

PHP 8.2+ (Docker)
MySQL 8.0 / SQLite 3
PDO avec prepared statements

Infrastructure

Docker Compose (local dev)
Fly.io (production)
GitHub (version control)

🧪 Tests

Unit tests : PHPUnit (backend)
E2E tests : Playwright (frontend)
Coverage cible : 80%+

📊 Monitoring

Sentry pour erreurs JS/PHP
Google Analytics pour usage
Logs centralisés (STDOUT en Docker)

🚀 Déploiement
# Local
docker compose up -d

# Production
git push origin main
flyctl deploy
🔄 CI/CD

GitHub Actions sur push develop
Tests automatiques
Linting PHP/JS
Build et déploiement Fly

📝 Conventions

Commits : Type: description
Branches : feature/, bugfix/, hotfix/*
Code style : PSR-12 (PHP), ESLint (JS)
Commentaires : JSDoc + PHPDoc

🐛 Debugging

XDebug en local (vscode)
Vue DevTools en dev
Network tab pour API
SQLite CLI pour BD

⚡ Performance

Cache DB : query résultats fréquents
Minification assets
Gzip compression
CDN images (imgix optionnel)

🔒 Sécurité Déployée

HTTPS forcé production
Headers de sécurité (CORS, CSP)
Rate limiting : 100 req/min/IP
SQL injection : 0 risque (prepared)
XSS : htmlspecialchars() systématique
CSRF : token auto sur formulaires

📞 Support Technique

Issues : GitHub
Documentation : /docs
Email : dev@vite-gourmand.fr
