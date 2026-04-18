<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Setting;
use Exception;

/**
 * Contrôleur Utilisateur (Espace Client)
 */
class UserController {
    private $reservationModel;
    private $userModel;
    private $settingModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->reservationModel = new Reservation();
        $this->userModel        = new User();
        $this->settingModel     = new Setting();
    }

    /**
     * Affiche le tableau de bord du client
     */
    public function dashboard() {
        // Sécurité : Rediriger vers la connexion si non authentifié
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Récupérer les infos complètes de l'utilisateur
        $db = \App\Config\Database::getInstance();
        $stmtUser = $db->prepare("SELECT * FROM clients WHERE id = ?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();

        $reservations = $this->reservationModel->getByUserId($userId);
        
        // Récupérer les réservations restaurant
        $db = \App\Config\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM reservations_restaurant WHERE client_id = ? ORDER BY date_reservation DESC");
        $stmt->execute([$userId]);
        $restaurantReservations = $stmt->fetchAll();

        $siteSettings = $this->settingModel->getAll();

        require_once __DIR__ . '/../../frontend/views/dashboard.php';
    }

    /**
     * API : Mise à jour du profil utilisateur
     */
    public function updateProfile() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['nom'])) {
                throw new Exception("Données invalides.");
            }

            $userId = $_SESSION['user_id'];
            $db = \App\Config\Database::getInstance();
            $stmt = $db->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?");
            $success = $stmt->execute([
                $data['nom'],
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['telephone'] ?? '',
                $userId
            ]);

            if ($success) {
                // Mettre à jour la session
                $_SESSION['user_nom'] = $data['prenom'] . ' ' . $data['nom'];
                $_SESSION['user_email'] = $data['email'];
            }

            echo json_encode(['success' => $success, 'message' => $success ? 'Profil mis à jour' : 'Erreur lors de la mise à jour']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout() {
        // Détruire toutes les variables de session
        $_SESSION = [];

        // Supprimer le cookie de session si présent
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Détruire la session
        session_destroy();

        // Rediriger vers la page d'accueil
        header('Location: ' . URL_ROOT . '/');
        exit();
    }
}
