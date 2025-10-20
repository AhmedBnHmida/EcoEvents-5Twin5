# EcoEvents - Plateforme d'Événements Écologiques

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

Un point de rencontre en ligne pour organiser et promouvoir des événements autour de l'écologie et du développement durable.

## 🌱 Fonctionnalités

- **Gestion des Événements** - Création et organisation d'événements écologiques
- **Inscriptions Participants** - Système d'inscription avec QR codes
- **Partenariats** - Gestion des sponsors et partenaires écoresponsables
- **Ressources Durables** - Optimisation des ressources écologiques
- **Feedbacks Intelligents** - Analyse des retours participants par IA

## 🚀 Installation Rapide

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Node.js et npm
- MySQL 8.0 ou supérieur

### 1. Cloner le projet
```bash
git clone https://github.com/AhmedBnHmida/EcoEvents-5Twin5.git
cd EcoEvents-5Twin5
```
### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurer la base de données
Éditez le fichier .env :

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecoevents
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 5. Installer les dépendances frontend
```bash
npm install
npm run dev
```

### 6. Exécuter les migrations et seeders
```bash
# Exécuter les migrations et peupler la base de données
php artisan migrate --seed

# Ou pour réinitialiser complètement
php artisan migrate:fresh --seed
```

### 7. Démarrer le serveur
```bash
php artisan serve
```

Ouvrez http://localhost:8000 dans votre navigateur.

## 🧪 Tests

### Configuration des tests
Les tests utilisent une base de données MySQL séparée pour éviter d'affecter les données de développement.

1. **Créer la base de données de test** :
```bash
# Connectez-vous à MySQL et créez la base de données
mysql -u root -p
CREATE DATABASE ecoevents_test;
```

2. **Configurer la base de données de test** :
Le fichier `phpunit.xml` est déjà configuré pour utiliser :
- Base de données : `ecoevents_test`
- Connexion : `mysql`

### Exécuter les tests

**Tous les tests** :
```bash
php artisan test
```

**Tests spécifiques** :
```bash
# Tests du module Partenaires & Sponsoring
php artisan test --filter=PartenaireTest
php artisan test --filter=SponsoringTest
php artisan test --filter=SponsoringBuilderTest

# Tests d'une classe spécifique
php artisan test tests/Feature/PartenaireTest.php
php artisan test tests/Feature/SponsoringTest.php
php artisan test tests/Feature/SponsoringBuilderTest.php

# Tests unitaires
php artisan test tests/Unit/
```

**Tests avec couverture** :
```bash
# Si Xdebug est installé
php artisan test --coverage
```

### Types de tests disponibles

- **Tests de fonctionnalité** : CRUD, validation, permissions, uploads
- **Tests unitaires** : Modèles, services, règles de validation
- **Tests d'intégration** : API, base de données, relations
- **Tests du Sponsoring Builder** : IA, optimisation de budget, génération de propositions