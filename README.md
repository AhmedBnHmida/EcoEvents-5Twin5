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

### 6. Exécuter les migrations
```bash
php artisan migrate
```

### 7. Démarrer le serveur
```bash
php artisan serve
```

Ouvrez http://localhost:8000 dans votre navigateur.