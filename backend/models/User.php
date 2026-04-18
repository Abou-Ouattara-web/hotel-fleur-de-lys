<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Modèle User
 * Gère toutes les intéractions avec la table 'clients' de la base de données.
 */
class User {
    /** @var PDO $db Représente la connexion active à la base de données */
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Cherche un utilisateur via son adresse e-mail.
     * Cette méthode utilise une requête préparée pour empêcher les injections SQL.
     * 
     * @param string $email L'adresse e-mail à rechercher.
     * @return array|false Retourne le tableau des données client si trouvé, sinon false.
     */
    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT id, prenom, nom, email, mot_de_passe, role FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données.
     * Le mot de passe est encrypté (haché) automatiquement avant insertion via l'algorithme BCRYPT.
     * 
     * @param array $data Les informations saisies lors de l'inscription (prenom, nom, email, etc.)
     * @return int L'ID unique (Primary Key) généré en base pour ce nouveau client.
     */
    public function create(array $data): int {
        // Hachage sécurisé du mot de passe avant stockage
        $hashedPassword = !empty($data['password']) 
            ? password_hash($data['password'], PASSWORD_BCRYPT) 
            : null;

        $stmt = $this->db->prepare("
            INSERT INTO clients (prenom, nom, email, telephone, mot_de_passe, date_naissance, newsletter) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['prenom'],
            $data['nom'],
            $data['email'],
            $data['telephone'] ?? null,
            $hashedPassword,
            $data['date_naissance'] ?? null,
            !empty($data['newsletter']) ? 1 : 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Vérifie l'authenticité d'une paire "Email / Mot de passe" lors d'une connexion.
     * Récupère l'utilisateur en base et compare de façon sécurisée le hash stocké.
     * 
     * @param string $email L'e-mail du client.
     * @param string $password Le mot de passe rentré dans le formulaire (non haché).
     * @return array|false Les données du client si les identifiants sont bons, false sinon.
     */
    public function verifyCredentials(string $email, string $password): array|false {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }
        // Vérification sécurisée du mot de passe avec password_verify
        if (empty($user['mot_de_passe']) || !password_verify($password, $user['mot_de_passe'])) {
            return false;
        }
        return $user;
    }
}
