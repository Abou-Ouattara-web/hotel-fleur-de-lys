<?php

namespace App\Config;

use PDO;
use PDOException;

/**
 * CLASSE DATABASE (Design Pattern : SINGLETON)
 * Cette classe assure qu'une seule et unique connexion à la base de données 
 * est créée durant toute l'exécution du script, économisant ainsi les ressources du serveur.
 */
class Database {
    private static $instance = null;
    private $pdo;

    /**
     * Constructeur Privé : Empêche l'instanciation directe via 'new'
     */
    private function __construct() {
        // Configuration de la base de données
        $host = 'localhost';
        $db   = 'fleur_de_lys_hotel';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        /**
         * CONFIGURATION SÉCURISÉE DE PDO :
         * 1. ATTR_ERRMODE => Sort des exceptions en cas d'erreur SQL.
         * 2. ATTR_EMULATE_PREPARES => Désactivé pour utiliser les vraies requêtes préparées de MySQL (Protection SQL Injection).
         * 3. ATTR_PERSISTENT => Maintient la connexion ouverte pour accélérer les chargements.
         */
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => true, 
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Sécurité : On ne logue jamais le message brute ($e->getMessage) car il peut contenir des identifiants.
            throw new PDOException("Impossible de se connecter à la base de données.");
        }
    }

    /**
     * Méthode Statique d'accès à l'instance (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
