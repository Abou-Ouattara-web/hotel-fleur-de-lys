# Architecture Technique du Projet

Le système qui propulse l'application Web de l'Hôtel Fleur de Lys repose sur une architecture **MVC (Modèle-Vue-Contrôleur)** construite sur mesure (aussi connue sous le nom de *Vanilla PHP MVC*).

Elle sépare proprement la logique du back-end (traitement des données API, logique métier) de celle du front-end (feuilles de style, vues html, logique de design client).

## 📁 Arborescence des Fichiers

```text
/
├── .htaccess                 # Fichier de redirection de serveur (mod_rewrite) pointe à l'initialisation MVC vers index.php
├── ARCHITECTURE.md           # Ce guide d'architecture technique
├── README.md                 # Le guide fonctionnel et d'installation du projet
├── database/
│   └── database.sql          # Le schéma MySQL initial complet
├── backend/                  # Le Cœur de l'application orientée MVC (Logique "Back")
│   ├── config/               # Configuration de base (connexion PDO DB)
│   ├── controllers/          # Logique d'interaction et de traitement (ex: AuthController.php, AdminController.php)
│   ├── models/               # Interface avec la Base de données (ex: User.php, Room.php, Offer.php)
│   └── routes/               # Logique de gestion de routage HTTP 
│       ├── Router.php        # Le moteur de dispatching de classe
│       └── web.php           # Définition manuelle des Routes de toute l'application
└── frontend/                 # Tout ce qui traite du design et de l'affichage (Logique "Front")
    ├── public/               # Données exposées au public...
    │   ├── css/              # Feuilles CSS de design global
    │   ├── images/           # Actifs graphiques de l'hôtel (Logos, photos chambres...)
    │   ├── js/               # Scripts, animations et appels AJAX aux controllers
    │   ├── vendor/           # Bibliothèques locales (FontAwesome, Chart.js, FullCalendar)
    │   └── index.php         # L'ENTRÉE de toute l'application (le Point de d'amorce unique)
    └── views/                # Les pages traitées avant affichage (html en php complet avec entêtes)
        ├── 404.php
        ├── home.php          
        ├── inscription.php   
        ├── login.php         
        ├── reservation.php   
        ├── restaurant.php    
        └── rooms.php         
```

## 🔀 Flux d'Exécution d'une Requête HTTP (Route)

Lorsqu'un utilisateur navigue vers une URL (comme `http://.../inscription`), voici comment notre pile technique l'aborde :

1. L'utilisateur lance sa requête HTTP (ex: `/inscription`).
2. Le fichier **`.htaccess`** situé à la racine du serveur capte cette requête, et la route dynamiquement vers **`frontend/public/index.php`** sans que l'URL n'affiche ces détails.
3. Le **`index.php`** s'exécute, il définit des variables globales (comme le `URL_ROOT` utilisé partout dans le CSS/JS) et amorce l'autoloader PSR-4.
4. L'objet **`Router`** est appelé. Le path `/inscription` est vérifié dans **`web.php`** où la règle `['GET', '/inscription', 'AuthController@registerView']` le captera.
5. Le Router bascule alors la commande à la méthode `registerView()` de la classe  **`AuthController`**.
6. Le **Contrôleur** (Ici `AuthController`) affiche techniquement la Vue voulue via un `require_once` pointant vers la page en html/php de **`frontend/views/inscription.php`**.

*(Ce flux exact se déroule ainsi pour des requêtes POST d'API JSON, mais le Contrôleur, au lieu de déclencher un `require_once` d'un composant visuel, appellera un *Modèle* (comme le model `User`) et imprimera purement un *json_encode($array)* attendu par la fameuse méthode javascript `fetch()` côté client.)*

## 💡 Concepts Importants

### L'Autoloader
Ce projet ne requiert pas `Composer` explicitement. Un autoloader manuel simplifié (similaire à PSR-4) gère les dépendances au sommet du fichier `index.php`. 
Ainsi, un composant localisé en `App\Models\User` sera converti organiquement par PHP en une importation localisée vers la classe `backend/models/User.php`.

### Résistant aux Attaques Externes
- **SQL Injection :** Les variables sont passées sous forme de tableau via les méthodes de préparation de PDO `$stmt->execute([$arg1, $arg2])`.
- **Failles XSS :** Il y a des mécanismes de nettoyages, toutes les routes imposent des Headers stricts (`X-XSS-Protection`, etc.), injectées dans l'`index.php`.
- **Session Fixation/Hijacking :** Un `session_regenerate_id(true)` est explicitement activé lors des processus critiques (connexion/inscription) par `AuthController.php`.
62: 
### Stratégie d'Assets & Performance
- **Localisation des Assets :** Pour éviter les blocages de "Tracking Prevention" et améliorer la confidentialité, les bibliothèques tierces (Font Awesome, Chart.js, FullCalendar) sont hébergées intégralement en local dans `/frontend/public/vendor`.
- **LCP & Performance :** Utilisation de `fetchpriority="high"` pour les logos et héros, et `decoding="async"` pour toutes les images afin de libérer le thread principal.
- **Gestion Dynamique (CMS) :** Le projet inclut désormais un système de gestion des offres spéciales via le modèle `Offer.php` et un module de suivi des réservations restaurant intégré à l'administration.
- **Résilience Graphique :** Pour garantir la cohérence visuelle, le système utilise des images "placeholders" élégantes (`offer-placeholder.png`, `room-placeholder.jpg`) si aucun visuel n'est fourni par l'administrateur.
- **Auto-Documentation :** Le code source utilise des commentaires pédagogiques en français expliquant les choix d'architecture (Singleton, Middleware, Routing) pour faciliter l'apprentissage et la maintenance.
- **Design Premium :** Utilisation intensive des variables CSS, du Glassmorphism (blur) et des transitions `fade-in` pour une expérience utilisateur moderne et fluide.

## 📌 Astuce de Test
Si vous mettez le site sur un serveur local, et que le navigateur retourne d'étranges erreurs ou n'arrive pas à résoudre les sous pages (Erreur 404 du Serveur HTTP sous XAMPP et non pas la page 404 du site visuellement designé), **soyez sûr que l'extension `mod_rewrite` est bien active** sur l'instance Apache, le framework `Router` du projet ne peut absolument pas fonctionner sans lui.
