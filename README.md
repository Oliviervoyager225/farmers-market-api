# FarmBridge API

API REST backend de la plateforme **FarmBridge POS** — gestion des achats de produits agricoles, du crédit agriculteur, des dettes et des remboursements en Côte d'Ivoire.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Framework | Laravel 13 (PHP 8.3) |
| Base de données | PostgreSQL 16 |
| Authentification | Laravel Sanctum (tokens) |
| Administration | Filament 5 |
| Architecture | DDD (Domain-Driven Design) |
| Conteneurisation | Docker / Docker Compose |

---

## Domaines métier

```
app/Domain/
├── Auth/           # Connexion, gestion des tokens Sanctum
├── Farmer/         # Agriculteurs, profils, spécialités, limite de crédit
├── Product/        # Catalogue de produits agricoles
├── Category/       # Catégories de produits
├── Transaction/    # Achats POS (espèces ou crédit)
├── Debt/           # Dettes issues des achats à crédit
├── Repayment/      # Remboursements en nature (récoltes)
├── Notification/   # Notifications in-app
└── Setting/        # Configuration (taux d'intérêt, limites, etc.)
```

---

## Fonctionnalités principales

- **Gestion des agriculteurs** — création, profil complet, spécialités, catégories de production, limite de crédit configurable par agriculteur
- **Catalogue produits** — produits agricoles avec catégories, prix unitaires en FCFA
- **Caisse POS** — enregistrement d'achats multi-produits, paiement espèces ou crédit avec taux d'intérêt
- **Limite de crédit** — blocage automatique si la nouvelle dette dépasse le plafond autorisé
- **Suivi des dettes** — encours par agriculteur, statuts `open / partial / paid`
- **Remboursements en nature** — produit + kg + taux/kg → montant crédité calculé et imputé automatiquement sur la dette
- **Audit trail** — chaque transaction et remboursement est lié à l'opérateur (nom + rôle)
- **RBAC** — 3 rôles : `admin`, `supervisor`, `operator`
- **Administration Filament** — interface d'administration web intégrée

---

## Rôles et permissions

| Action | admin | supervisor | operator |
|---|:---:|:---:|:---:|
| Créer un agriculteur | ✅ | ❌ | ❌ |
| Voir tous les agriculteurs | ✅ | ✅ | ✅ |
| Passer une commande POS | ✅ | ✅ | ✅ |
| Enregistrer un remboursement | ✅ | ✅ | ✅ |
| Accès administration Filament | ✅ | ✅ | ❌ |

---

## Installation

### Prérequis

- PHP 8.3+
- Composer
- PostgreSQL 16
- Docker (optionnel)

### Avec Docker

```bash
git clone <repo-url>
cd farmers-market-api

cp .env.example .env
# Configurer DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env

docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

### Sans Docker

```bash
git clone <repo-url>
cd farmers-market-api

composer install
cp .env.example .env
php artisan key:generate

# Configurer .env avec vos identifiants PostgreSQL
php artisan migrate --seed
php artisan serve
```

---

## Variables d'environnement clés

```env
APP_NAME=FarmBridge
APP_ENV=production
APP_URL=https://votre-domaine.com

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=farmbridge
DB_USERNAME=postgres
DB_PASSWORD=secret

SANCTUM_STATEFUL_DOMAINS=localhost,votre-domaine.com
FRONTEND_URL=http://localhost
```

---

## Endpoints API principaux

Tous les endpoints (sauf `/api/auth/login`) nécessitent :
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Authentification
```
POST   /api/auth/login              Connexion (retourne token)
POST   /api/auth/logout             Déconnexion
GET    /api/auth/me                 Profil utilisateur connecté
```

### Agriculteurs
```
GET    /api/farmers                 Liste paginée (search, per_page)
POST   /api/farmers                 Créer un agriculteur
GET    /api/farmers/{id}            Détail agriculteur
PUT    /api/farmers/{id}            Modifier agriculteur
DELETE /api/farmers/{id}            Supprimer agriculteur
GET    /api/farmers/{id}/debts      Dettes d'un agriculteur
GET    /api/farmers/{id}/repayments Remboursements d'un agriculteur
```

### Produits & Catégories
```
GET    /api/products                Catalogue produits
GET    /api/categories              Liste des catégories
```

### Transactions (achats POS)
```
GET    /api/transactions            Toutes les transactions (per_page, farmer_id)
POST   /api/transactions            Enregistrer un achat
GET    /api/transactions/{id}       Détail d'une transaction
```

### Remboursements
```
GET    /api/repayments              Tous les remboursements (per_page, farmer_id)
POST   /api/repayments              Enregistrer un remboursement
GET    /api/repayments/{id}         Détail d'un remboursement
```

### Configuration
```
GET    /api/settings                Paramètres (taux d'intérêt, limites)
PUT    /api/settings                Mettre à jour les paramètres
```

---

## Exemple de payload — Créer un achat

```json
POST /api/transactions
{
  "farmer_id": 3,
  "payment_method": "credit",
  "interest_rate": 0.05,
  "items": [
    { "product_id": 1, "quantity": 2 },
    { "product_id": 4, "quantity": 5 }
  ]
}
```

## Exemple de payload — Enregistrer un remboursement

```json
POST /api/repayments
{
  "farmer_id": 3,
  "commodity": "Cacao",
  "kg_received": 25.5,
  "rate_per_kg": 1000
}
```

---

## Structure du projet

```
app/
├── Domain/            # Logique métier par domaine (DDD)
│   └── {Domain}/
│       ├── Controllers/
│       ├── Services/
│       ├── Repositories/
│       ├── DTOs/
│       └── Requests/
├── Filament/          # Interface d'administration Filament
├── Http/Resources/    # Transformateurs de réponse API (JSON)
├── Models/            # Modèles Eloquent
database/
├── migrations/        # Migrations PostgreSQL
└── seeders/           # Données initiales (users, produits)
routes/
└── api.php            # Définition de toutes les routes API
```

---

## Administration

Interface Filament accessible à `/admin` pour les rôles `admin` et `supervisor`.
Permet la gestion complète des données sans passer par l'application mobile/web.

---

## Déploiement

Le projet inclut un `Procfile` pour déploiement sur Railway, Render ou Heroku :

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## Licence

Projet privé — FarmBridge © 2026
