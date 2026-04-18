<?php

namespace App\Controllers;

use App\Models\Review;
use Exception;

/**
 * Contrôleur des Avis (ReviewController)
 * Permet aux clients de s'exprimer et à l'admin de gérer la réputation.
 */
class ReviewController {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new Review();
    }

    /**
     * Endpoint API : Soumet un nouvel avis.
     * Route: POST /api/reviews
     */
    public function submit() {
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['nom']) || empty($data['note']) || empty($data['commentaire'])) {
                throw new Exception("Tous les champs sont requis (Nom, Note, Commentaire).");
            }

            if ($data['note'] < 1 || $data['note'] > 5) {
                throw new Exception("La note doit être comprise entre 1 et 5.");
            }

            // Si l'utilisateur est connecté, on attache son ID
            session_start();
            $clientId = $_SESSION['user_id'] ?? null;

            $success = $this->reviewModel->create([
                'client_id'   => $clientId,
                'nom'         => $data['nom'],
                'note'        => $data['note'],
                'commentaire' => $data['commentaire']
            ]);

            if (!$success) throw new Exception("Erreur lors de l'enregistrement en base.");

            echo json_encode([
                'success' => true, 
                'message' => 'Merci ! Votre avis a été soumis et sera visible après modération.'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Administration : Approuve un avis.
     * Route: POST /api/admin/reviews/approve
     */
    public function approve() {
        header('Content-Type: application/json');
        $this->checkAdmin();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if ($id && $this->reviewModel->updateStatus((int)$id, true)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false]);
        }
    }

    /**
     * Administration : Supprime un avis.
     * Route: POST /api/admin/reviews/delete
     */
    public function delete() {
        header('Content-Type: application/json');
        $this->checkAdmin();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if ($id && $this->reviewModel->delete((int)$id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false]);
        }
    }

    /**
     * Vérification de sécurité Admin
     */
    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die(json_encode(['success' => false, 'message' => 'Action interdite.']));
        }
    }
}
