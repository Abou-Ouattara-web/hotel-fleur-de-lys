<?php

/**
 * Système d'Internationalisation (i18n)
 * Charge les dictionnaires de traduction selon la langue choisie par l'utilisateur.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Langue par défaut : Français
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr';
}

// Changement de langue via URL (ex: ?lang=en)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'];
$translations = [];

// Chargement du fichier de langue
$langFile = __DIR__ . "/../lang/{$lang}.php";
if (file_exists($langFile)) {
    $translations = require $langFile;
}

/**
 * Fonction globale de traduction.
 * Retourne la valeur correspondant à la clé, ou la clé elle-même si non trouvée.
 * 
 * @param string $key La clé de traduction
 * @return string Le texte traduit
 */
function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
