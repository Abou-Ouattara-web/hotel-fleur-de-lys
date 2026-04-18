<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Setting;
use Exception;

/**
 * Contrôleur d'Authentification (AuthController)
 * 
 * Ce contrôleur gère toute la logique liée à l'accès utilisateur :
 * Affichage des vues de connexion/inscription, traitement des formulaires (login/register).
 */
class AuthController {
    /** @var User L'instance du Modèle User communiquant avec la base de données */
    private $userModel;
    private $settingModel;

    public function __construct() {
        $this->userModel = new User();
        $this->settingModel = new Setting();
    }

    /**
     * Affiche la page HTML (Vue) pour la connexion.
     */
    public function loginView() {
        $siteSettings = $this->settingModel->getAll();
        require_once __DIR__ . '/../../frontend/views/login.php';
    }

    /**
     * Affiche la page HTML (Vue) pour l'inscription.
     */
    public function registerView() {
        $siteSettings = $this->settingModel->getAll();
        require_once __DIR__ . '/../../frontend/views/inscription.php';
    }

    // =============================================
    //  CONNEXION (Endpoint API)
    // =============================================
    /**
     * Traite la requête AJAX POST de la connexion.
     * Vérifie les identifiants, initialise la session PHP et renvoie une réponse JSON.
     * 
     * @return void Affiche un payload JSON
     */
    public function login() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $email    = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception("Email et mot de passe requis.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Adresse e-mail invalide.");
            }

            // Vérification sécurisée email + mot de passe via password_verify()
            $user = $this->userModel->verifyCredentials($email, $password);

            if (!$user) {
                // Message générique pour ne pas révéler si l'email existe
                throw new Exception("Identifiants incorrects. Veuillez réessayer.");
            }

            // Régénérer l'ID de session pour prévenir le session fixation
            session_regenerate_id(true);

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_nom']   = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'] ?? 'client';

            echo json_encode([
                'success' => true,
                'message' => 'Connexion réussie. Bienvenue ' . htmlspecialchars($user['prenom']) . ' !',
                'role'    => $user['role'] ?? 'client'
            ]);

        } catch (Exception $e) {
            http_response_code(200);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // =============================================
    //  INSCRIPTION (Endpoint API)
    // =============================================
    /**
     * Traite la requête AJAX POST pour l'inscription d'un nouvel utilisateur.
     * Effectue la validation des champs, vérifie si l'e-mail est unique,
     * déclenche la création en BDD et connecte le patient automatiquement.
     * 
     * @return void Affiche un payload JSON
     */
    public function register() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $prenom    = trim($data['prenom']   ?? '');
            $nom       = trim($data['nom']      ?? '');
            $email     = trim($data['email']    ?? '');
            $password  = $data['password']      ?? '';
            $confirm   = $data['confirm_password'] ?? '';
            $telephone = trim($data['telephone'] ?? '');
            
            $dateNaissance = trim($data['date_naissance'] ?? '');
            if ($dateNaissance === '') {
                $dateNaissance = null;
            }

            // --- Validation serveur ---
            if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($telephone)) {
                throw new Exception("Tous les champs obligatoires (incluant le téléphone) doivent être remplis.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Adresse e-mail invalide.");
            }
            if (strlen($password) < 8) {
                throw new Exception("Le mot de passe doit contenir au moins 8 caractères.");
            }
            if (empty($confirm) || $password !== $confirm) {
                throw new Exception("Les mots de passe ne correspondent pas ou la confirmation est vide.");
            }

            // Vérifier si l'email est déjà utilisé
            if ($this->userModel->findByEmail($email)) {
                throw new Exception("Cette adresse e-mail est déjà associée à un compte.");
            }

            // Le hashage BCRYPT est effectué dans User::create()
            $userId = $this->userModel->create([
                'prenom'         => $prenom,
                'nom'            => $nom,
                'email'          => $email,
                'telephone'      => $telephone,
                'date_naissance' => $dateNaissance,
                'newsletter'     => $data['newsletter']   ?? false,
                'password'       => $password,
            ]);

            // Connexion automatique après inscription (rôle par défaut 'client')
            session_regenerate_id(true);
            $_SESSION['user_id']    = $userId;
            $_SESSION['user_nom']   = $prenom . ' ' . $nom;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role']  = 'client';

            echo json_encode([
                'success' => true,
                'message' => 'Inscription réussie ! Bienvenue ' . htmlspecialchars($prenom) . ' !',
                'user_id' => $userId,
            ]);

        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../../debug_auth_error.log', date('Y-m-d H:i:s') . ' - REGISTER ERROR: ' . $e->getMessage() . "\n", FILE_APPEND);
            http_response_code(200);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

