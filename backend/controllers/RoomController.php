<?php

namespace App\Controllers;

use App\Models\Room;

/**
 * Contrôleur des Chambres (Rooms)
 * Interface lisant la base de données pour afficher le catalogue dynamique des lits de l'hôtel.
 */
class RoomController {
    /** @var Room Modèle gérant l'accès au catalogue des chambres */
    private $roomModel;

    public function __construct() {
        $this->roomModel = new Room();
    }

    /**
     * Route /rooms. 
     * Charge les chambres selon les filtres (GET) et les injecte dans la Vue.
     */
    public function index() {
        $filters = [
            'min_price'  => $_GET['min_price'] ?? null,
            'max_price'  => $_GET['max_price'] ?? null,
            'type'       => $_GET['type'] ?? null,
            'disponible' => $_GET['disponible'] ?? null
        ];

        $rooms = $this->roomModel->findWithFilters($filters);
        require_once __DIR__ . '/../../frontend/views/rooms.php';
    }

    /**
     * Endpoint API /api/rooms/check-availability
     * Vérifie quelles chambres sont libres sur une période donnée.
     */
    public function checkAvailability() {
        header('Content-Type: application/json');
        
        $arrivee = $_GET['arrivee'] ?? null;
        $depart  = $_GET['depart'] ?? null;

        if (!$arrivee || !$depart) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dates d\'arrivée et de départ requises.']);
            return;
        }

        try {
            $rooms = $this->roomModel->getAvailableRooms($arrivee, $depart);
            echo json_encode(['success' => true, 'data' => $rooms]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
