# 🏨 Hôtel FLEUR DE LYS - Plateforme de Gestion Hôtelière

Plateforme web professionnelle pour l'Hôtel Fleur de Lys (Tiassalé, Côte d'Ivoire).
Architecture **MVC PHP** sans framework — Développé avec performance, sécurité et SEO en tête.

## ✅ Fonctionnalités Implémentées (10 Phases)

| Phase | Module | Statut |
|-------|--------|--------|
| 1 | Dashboard Administration sécurisé | ✅ |
| 2 | Moteur de Disponibilité (filtrage SQL temps réel) | ✅ |
| 3 | Facturation PDF automatique (FPDF) | ✅ |
| 4 | Système d'Avis Clients avec modération | ✅ |
| 5 | Internationalisation (FR/EN) | ✅ |
| 6 | Analytics & Graphiques (Chart.js) | ✅ |
| 7 | Emails de Confirmation (Mailer + Logs) | ✅ |
| 8 | Filtres de Recherche Avancés (Chambres) | ✅ |
| 9 | CMS Lite (gestion des chambres depuis l'admin) | ✅ |
| 10 | Optimisation SEO & Performance | ✅ |
| 11 | Modernisation UI/UX (Hamburger & Glassmorphism) | ✅ |
| 12 | Assets Locaux (Charts, Calendar, FontAwesome) | ✅ |
| 13 | Gestion Dynamique des Offres Spéciales | ✅ |
| 14 | Dashboard Admin Restaurant (Suivi Tables) | ✅ |

Bienvenue dans le dépôt du système de gestion Web de l'Hôtel Fleur de Lys, établissement de luxe situé à Tiassalé !

Ce projet est une application web complète (sur mesure sans framework externe massif), comportant un système de site vitrine dynamique combiné à un backend fournissant un système de réservation de chambres, d'inscription, d'authentification client et de gestion du restaurant.

## 🚀 Fonctionnalités Clés

* **Landing Page & Vitrine :** Présentation élégante de l'hôtel, de ses services (Wifi, Pisine, Fitness, etc.) et de ses offres avec carrousels immersifs.
* **Réservation de Chambres :** Catalogue affichant divers types de chambres (Ventilées, Climatisées, Executive, Royales...). Possibilité de sélectionner des dates, de spécifier le nombre d'adultes/enfants et d'avoir une indication du prix dynamique.
* **Système d'Authentification :** Inscription, connexion, validation de mots de passe, et gestion de flux de session en toute sécurité (les mots de passe sont hachés nativement avec PHP-`bcrypt`).
* **Paiement (Module en attente / de test) :** Interface simulant le paiement Mobile Money (Orange, MTN, Moov, Wave) ou des méthodes classiques (Visa, Espèces, Virement).
* **Navigation Premium & Responsive :** Menu hamburger moderne sur mobile, chargement fluide des images avec effets de fondu, et localisation des icônes/scripts pour éviter les blocages publicitaires.
* **Dashboard Admin Glassmorphism :** Interface de gestion haut de gamme avec flou d'arrière-plan, statistiques dynamiques (Chart.js local), planning interactif (FullCalendar local) et gestion des offres promotionnelles.
* **Restaurant & Menu :** Présentation de la carte (Entrées, Plats, Desserts, Vins) et gestion complète des réservations de tables avec interface d'administration dédiée.

## 🛠 Prérequis

* Un serveur web avec **Apache** et **PHP** (ex: *XAMPP*, *WAMP* ou *MAMP*).
* Une version de **PHP 7.4 minimum** (PHP 8.x recommandé).
* Une base de données **MySQL** ouverte et configurée en local (ou distante si sur serveur).

## 📥 Installation

1. **Cloner / Placer le projet**
Placez tout le dossier source de ce projet (`HOTEL FLEUR DE LYS - Copie`) dans le dossier web racine de votre serveur (exemple: `c:\xampp\htdocs\` pour Windows XAMPP).

2. **Base de données**
   * Ouvrez *phpMyAdmin* ou votre client MySQL habituel.
   * Importez le fichier **`database/database.sql`** qui s'assurera de :
     - Créer la base de données `fleur_de_lys_hotel`.
     - Créer toutes les tables (clients, chambres, reservations, paiements, menus, reservations_restaurant, etc.).
     - Insérer les données par défaut indispensables pour le site (liste complète et prix des chambres, liste du menu restaurant...).

3. **Configuration du Backend**
Ouvrez le fichier de configuration de l'application situé sous `backend/config/database.php`.
Si vous êtes en local via XAMPP, les paramètres par défaut (`root` et mot de passe vide `''`) devraient déjà correspondre :
```php
$host = 'localhost';
$db   = 'fleur_de_lys_hotel';
$user = 'root';
$pass = ''; // Modifiez ici si votre XAMPP a un mot de passe DB défini.
```

## 💻 Démarrage Local

Démarrez votre serveur Apache et MySQL et accédez au projet depuis votre navigateur.  
Si le projet a été glissé dans votre dossier `htdocs` en en gardant son nom de dossier, l'URL racine sera très probablement :
👉 `http://localhost/HOTEL FLEUR DE LYS - Copie/`

*(L'application utilise un routeur PHP via `index.php` au format Model-View-Controller et le fichier `.htaccess` intercepte automatiquement toutes les URL pour une navigation propre et moderne)*

## 📂 Architecture

Le code du projet ne se base pas sur un framework "usine" usuel (comme Laravel ou Symfony), mais repose plutôt sur une mini-architecture Modèle-Vue-Contrôleur PHP totalement faite à la main et optimisée du côté "backend".

Pour le confort de lecture des développeurs amenés à modifier ce code, une partie technique détaillant son fonctionnement (Routes, Controllers, .htaccess, Modèles PHP) se trouve dans le document **[ARCHITECTURE.md](./ARCHITECTURE.md)** dédié.

---

💼 *Site Web propulsé par YESHOU COMMUNICATION*
