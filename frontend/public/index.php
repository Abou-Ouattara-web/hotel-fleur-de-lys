<?php

// ==========================================
// POINT D'ENTRÉE GLOBAL DE L'APPLICATION
// ==========================================

// En-têtes de sécurité (Prévention contre l'injection, le clickjacking et failles XSS)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");

/**
 * Autoloader PSR-4 simplifié.
 * Charge automatiquement les classes PHP nécessaires (sans require explicite) 
 * dès l'utilisation de leur namespace 'App\...'.
 */
spl_autoload_register(function ($class) {
    // Le préfixe de projet 'App\' correspond au dossier backend/
    $prefix = 'App\\';
    
    // Le dossier de base pour ce préfixe : pointe vers le dossier backend
    $base_dir = __DIR__ . '/../../backend/';

    // On vérifie si la classe utilise le préfixe App\
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Récupérer le nom relatif de la classe sans le préfixe
    $relative_class = substr($class, $len);

    // Transformer le nom de la classe en chemin de fichier
    $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    // Si le fichier existe, on le charge
    if (file_exists($file)) {
        require $file;
    }
});

// Initialisation globale
session_start();

// Système d'internationalisation (Traductions)
require_once __DIR__ . '/../../backend/config/translations.php';

// Chargement des routes
$router = new App\Routes\Router();
require_once __DIR__ . '/../../backend/routes/web.php';

// ---------------------------------------------------------
// DÉTERMINATION DYNAMIQUE DE L'URL RACINE (URL_ROOT)
// ---------------------------------------------------------
// Le scriptName contient le chemin complet vers ce fichier (ex: /HOTEL FLEUR DE LYS - Copie/frontend/public/index.php)
$scriptName = $_SERVER['SCRIPT_NAME']; 

/**
 * CALCUL DU RÉPERTOIRE RACINE :
 * Pour que les liens (CSS, JS, Images) fonctionnent même si le projet est dans un sous-dossier,
 * on extrait le chemin de base en retirant la partie pointant vers l'index.
 */
$baseDir = str_replace('/frontend/public/index.php', '', $scriptName);

// Constante globale utilisée dans toutes les vues PHP pour les liens absolus
define('URL_ROOT', $baseDir);

// Déterminer si c'est une requête API pour adapter la réponse (JSON vs Vue HTML)
$isApiRequest = strpos($_SERVER['REQUEST_URI'], '/api/') !== false;

// Injection du URL_ROOT dans l'espace global Javascript
if (!$isApiRequest) {
    echo "<script>window.URL_ROOT = '" . URL_ROOT . "';</script>";
}

/**
 * NETTOYAGE DE L'URI POUR LE ROUTAGE :
 * On récupère l'URL demandée (ex: /HOTEL FLEUR DE LYS - Copie/admin)
 */
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = urldecode(parse_url($uri, PHP_URL_PATH));

// On retire le sous-dossier de l'URI pour que le Router puisse matcher des routes simples comme '/admin'
if (!empty($baseDir) && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}

// Nettoyage supplémentaire pour éviter les doubles slashes /frontend/public/ dans l'URI si l'utilisateur y accède directement
if (strpos($uri, '/frontend/public') === 0) {
    $uri = substr($uri, strlen('/frontend/public'));
}

if (empty($uri)) $uri = '/';
if ($uri !== '/' && substr($uri, 0, 1) !== '/') $uri = '/' . $uri;

$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($method, $uri);
