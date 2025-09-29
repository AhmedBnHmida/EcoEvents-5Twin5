# 🎫 Guide de Gestion des Inscriptions - EcoEvents

## ✅ Fonctionnalités Implémentées

### 🔹 Pour les Participants (Frontend)

1. **Bouton "Participer" sur les cartes d'événements**

    - Visible sur la page `/events` et les détails des événements
    - Vérifie automatiquement si l'utilisateur est déjà inscrit
    - Affiche "Inscrit" si déjà enregistré

2. **Processus d'inscription**

    - Si non connecté → Redirection vers la page de connexion
    - Si pas participant → Message d'erreur
    - Formulaire de confirmation d'inscription avec détails de l'événement
    - Génération automatique de code de ticket unique
    - Génération de code QR

3. **Gestion des inscriptions**
    - Page "Mes inscriptions" : `/my-registrations`
    - Vue détaillée de chaque inscription avec code QR
    - Possibilité d'annuler une inscription
    - Affichage du statut (En attente, Confirmé, Annulé, Présent)

### 🔹 Pour les Administrateurs (Backend)

1. **Dashboard des inscriptions** : `/manage/registrations`

    - Liste complète de toutes les inscriptions
    - Statistiques en temps réel (En attente, Confirmés, Présents, Annulés)
    - Filtrage et tri des inscriptions

2. **Gestion des statuts**

    - Changement rapide de statut via dropdown
    - 4 statuts disponibles :
        - ⏳ **Pending** (En attente) - Par défaut
        - ✅ **Confirmed** (Confirmé) - Validé par l'admin
        - ❌ **Canceled** (Annulé) - Annulé
        - 👤 **Attended** (Présent) - Participant confirmé présent

3. **Actions administrateur**
    - Voir les détails de chaque inscription
    - Supprimer une inscription
    - Mise à jour du statut en un clic

## 📋 Statuts des Inscriptions

| Statut        | Description                            | Couleur  |
| ------------- | -------------------------------------- | -------- |
| **pending**   | Inscription en attente de confirmation | 🟡 Jaune |
| **confirmed** | Inscription confirmée par l'admin      | 🟢 Vert  |
| **canceled**  | Inscription annulée                    | 🔴 Rouge |
| **attended**  | Participant présent à l'événement      | 🔵 Bleu  |

## 🛣️ Routes Créées

### Routes Participants

```php
GET  /registrations/create?event_id={id}  // Formulaire d'inscription
POST /registrations                        // Enregistrer l'inscription
GET  /registrations/{id}                   // Voir détails inscription
DELETE /registrations/{id}                 // Annuler inscription
GET  /my-registrations                     // Mes inscriptions
```

### Routes Admin

```php
GET   /manage/registrations                    // Liste des inscriptions
PATCH /manage/registrations/{id}/status        // Mettre à jour le statut
```

## 📁 Fichiers Créés/Modifiés

### Nouveaux Fichiers

-   `app/RegistrationStatus.php` - Enum pour les statuts
-   `app/Http/Controllers/RegistrationController.php` - Contrôleur complet
-   `resources/views/registrations/create.blade.php` - Formulaire d'inscription
-   `resources/views/registrations/show.blade.php` - Détails inscription
-   `resources/views/registrations/my-registrations.blade.php` - Liste inscriptions participant
-   `resources/views/registrations/index.blade.php` - Dashboard admin

### Fichiers Modifiés

-   `app/Models/Registration.php` - Utilise RegistrationStatus enum
-   `routes/web.php` - Ajout des routes
-   `resources/views/events/public-show.blade.php` - Bouton participer
-   `resources/views/events/public-index.blade.php` - Bouton participer sur cartes

## 🚀 Utilisation

### Pour un Participant

1. **S'inscrire à un événement**

    - Aller sur `/events`
    - Cliquer sur "Participer" sur un événement
    - Se connecter si nécessaire (avec un compte participant)
    - Confirmer l'inscription

2. **Voir ses inscriptions**

    - Aller sur `/my-registrations`
    - Voir toutes ses inscriptions avec statuts
    - Cliquer sur "Détails" pour voir le code QR

3. **Annuler une inscription**
    - Dans "Mes inscriptions" ou "Détails"
    - Cliquer sur "Annuler mon inscription"

### Pour un Administrateur

1. **Gérer les inscriptions**

    - Aller sur `/manage/registrations`
    - Voir toutes les inscriptions avec statistiques

2. **Changer le statut**

    - Dans la liste, sélectionner un statut dans le dropdown
    - Le statut est mis à jour automatiquement

3. **Confirmer une inscription**

    - Changer le statut de "En attente" à "Confirmé"

4. **Marquer la présence**
    - Changer le statut à "Présent" lors de l'événement

## 🔐 Sécurité

-   ✅ Authentification requise pour toutes les actions
-   ✅ Vérification du rôle (participant pour s'inscrire)
-   ✅ Vérification de la capacité de l'événement
-   ✅ Protection contre les inscriptions multiples
-   ✅ Seuls les admins peuvent changer les statuts
-   ✅ Les participants peuvent uniquement gérer leurs propres inscriptions

## 📧 Améliorations Possibles (Futures)

-   [ ] Envoi d'email de confirmation d'inscription
-   [ ] Notification quand le statut change
-   [ ] Génération de PDF pour le ticket
-   [ ] Scan de QR code pour valider la présence
-   [ ] Paiement en ligne si événement payant
-   [ ] Rappel automatique avant l'événement

## 🎨 Code QR

Actuellement, un code QR simple est généré. Pour améliorer :

### Installer le package QR Code (optionnel)

```bash
composer require simplesoftwareio/simple-qrcode
```

Le code dans le contrôleur est prêt pour utiliser ce package si installé.

## 📞 Support

Si vous avez des questions sur l'utilisation de ce système :

1. Vérifiez que MySQL est démarré
2. Vérifiez que les migrations sont exécutées
3. Vérifiez que vous avez le bon rôle utilisateur

---

**Développé pour EcoEvents** 🌱
