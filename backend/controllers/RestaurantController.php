<?php

namespace App\Controllers;

/**
 * Contrôleur du Restaurant
 * Permet l'affichage de la vue gastronomique, incluant les carrousels du menu.
 */
class RestaurantController {
    /**
     * Affiche l'interface du restaurant (Route /restaurant)
     */
    public function index() {
        require_once __DIR__ . '/../../frontend/views/restaurant.php';
    }

    /**
     * API : Enregistre une réservation de table
     * Reçoit les données JSON : nom, email, telephone, date, heure, couverts, occasion, notes
     */
    public function reserve() {
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['nom']) || empty($data['date_reservation']) || empty($data['heure_reservation'])) {
                throw new \Exception("Veuillez remplir tous les champs obligatoires.");
            }

            $clientId = $_SESSION['user_id'] ?? null;

            $db = \App\Config\Database::getInstance();
            $stmt = $db->prepare("INSERT INTO reservations_restaurant 
                                (client_id, nom, email, telephone, date_reservation, heure_reservation, couverts, occasion, notes, statut) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')");
            
            $success = $stmt->execute([
                $clientId,
                $data['nom'],
                $data['email'] ?? '',
                $data['telephone'] ?? '',
                $data['date_reservation'],
                $data['heure_reservation'],
                $data['couverts'] ?? 1,
                $data['occasion'] ?? '',
                $data['notes'] ?? ''
            ]);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Votre table a été réservée avec succès.' : 'Une erreur est survenue lors de la réservation.'
            ]);

        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
