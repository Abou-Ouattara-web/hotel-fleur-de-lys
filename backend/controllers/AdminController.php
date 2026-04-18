<?php

namespace App\Controllers;

use App\Models\Offer;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Room;
use App\Models\Setting;

/**
 * Contrôleur de l'Espace d'Administration
 * Sécurisé pour n'afficher son contenu qu'aux utilisateurs ayant le rôle 'admin'.
 */
class AdminController {
    /** @var Reservation */
    private $reservationModel;
    /** @var Review */
    private $reviewModel;
    /** @var Room */
    private $roomModel;
    /** @var Setting */
    private $settingModel;
    /** @var Offer */
    private $offerModel;

    public function __construct() {
        /**
         * PROTECTION DE L'ESPACE ADMIN (Middleware) :
         * Le constructeur vérifie systématiquement si une session est active 
         * et si l'utilisateur possède le rôle 'admin'.
         * En cas d'échec, l'exécution s'arrête immédiatement avec un code 403 (Forbidden), 
         * empêchant tout accès aux données sensibles.
         */
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die("Accès refusé. Cette zone est réservé au personnel administratif autorisé.");
        }

        // Initialisation des Modèles nécessaires pour les opérations CRUD (Create, Read, Update, Delete)
        $this->reservationModel = new Reservation();
        $this->reviewModel = new Review();
        $this->roomModel = new Room();
        $this->settingModel = new Setting();
        $this->offerModel = new Offer();
    }

    /**
     * Affiche le tableau de bord principal
     */
    public function dashboard() {
        $reservations = $this->reservationModel->getAllForAdmin();
        $reviews = $this->reviewModel->getAllForAdmin();
        $stats = $this->reservationModel->getStatsByMonth();
        $rooms = $this->roomModel->getAll();
        $siteSettings = $this->settingModel->getAll();
        $offers = $this->offerModel->getAll();
        
        // Récupérer les réservations restaurant
        $db = \App\Config\Database::getInstance();
        $stmt = $db->query("SELECT * FROM reservations_restaurant ORDER BY date_reservation DESC, heure_reservation DESC");
        $restaurantReservations = $stmt->fetchAll();
        
        require_once __DIR__ . '/../../frontend/views/admin.php';
    }

    /**
     * API : Mise à jour d'une chambre
     */
    public function updateRoom() {
        header('Content-Type: application/json');
        try {
            // Utiliser $_POST pour gérer multipart/form-data
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception("ID de la chambre manquant.");
            }

            $currentRoom = $this->roomModel->findById((int)$id);
            if (!$currentRoom) {
                throw new \Exception("Chambre introuvable.");
            }

            $data = [
                'nom'         => $_POST['nom'] ?? $currentRoom['nom'],
                'prix'        => $_POST['prix'] ?? $currentRoom['prix'],
                'disponible'  => $_POST['disponible'] ?? $currentRoom['disponible'],
                'description' => $_POST['description'] ?? $currentRoom['description'],
                'image'       => $currentRoom['image'] // Valeur actuelle par défaut
            ];

            // Gérer l'upload d'image si un fichier est fourni
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->handleFileUpload($_FILES['image']);
            }

            $success = $this->roomModel->update((int)$id, $data);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Chambre mise à jour' : 'Erreur lors de la mise à jour']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Ajout d'une chambre
     */
    public function addRoom() {
        header('Content-Type: application/json');
        try {
            if (empty($_POST['nom']) || empty($_POST['prix'])) {
                throw new \Exception("Le nom et le prix sont obligatoires.");
            }

            $data = [
                'nom'         => $_POST['nom'],
                'description' => $_POST['description'] ?? '',
                'prix'        => $_POST['prix'],
                'disponible'  => $_POST['disponible'] ?? 1,
                'image'       => 'room-placeholder.jpg'
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->handleFileUpload($_FILES['image']);
            }

            $id = $this->roomModel->create($data);
            
            echo json_encode(['success' => true, 'message' => 'Chambre ajoutée', 'id' => $id]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Helper pour l'upload d'images
     */
    private function handleFileUpload($file) {
        $targetDir = __DIR__ . '/../../frontend/public/images/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . '_' . basename($file['name']);
        $targetFile = $targetDir . $filename;
        
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($file['tmp_name']);
        
        if ($check === false) {
            throw new \Exception("Le fichier n'est pas une image.");
        }
        
        if ($file['size'] > 5000000) { // Limit to 5MB
            throw new \Exception("Le fichier est trop volumineux (max 5Mo).");
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            throw new \Exception("Seuls les formats JPG, JPEG, PNG, WEBP & GIF sont acceptés.");
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $filename;
        } else {
            throw new \Exception("Erreur lors de l'enregistrement du fichier sur le serveur.");
        }
    }

    /**
     * API : Suppression d'une chambre
     */
    public function deleteRoom() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id'])) {
                throw new \Exception("ID de la chambre manquant.");
            }

            $success = $this->roomModel->delete((int)$data['id']);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Chambre supprimée' : 'Erreur lors de la suppression']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Mise à jour du statut d'une réservation
     */
    public function updateReservationStatus() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id']) || empty($data['statut'])) {
                throw new \Exception("Données invalides.");
            }

            $success = $this->reservationModel->updateStatus((int)$data['id'], $data['statut']);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Statut mis à jour' : 'Erreur lors de la mise à jour']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Suppression d'une réservation
     */
    public function deleteReservation() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id'])) {
                throw new \Exception("ID de réservation manquant.");
            }

            $success = $this->reservationModel->delete((int)$data['id']);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Réservation supprimée' : 'Erreur lors de la suppression']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Récupération des réservations pour le calendrier visuel
     */
    public function getCalendarEvents() {
        header('Content-Type: application/json');
        try {
            $reservations = $this->reservationModel->getAllForAdmin();
            $events = [];

            foreach ($reservations as $res) {
                // On exclut les réservations annulées du planning
                if ($res['statut'] === 'annulee') {
                    continue;
                }

                // Code couleur selon le statut
                $color = '#d4af37'; // Or par défaut (en attente)
                if ($res['statut'] === 'confirmee') {
                    $color = '#28a745'; // Vert
                } elseif ($res['statut'] === 'terminee') {
                    $color = '#6c757d'; // Gris
                }

                $events[] = [
                    'id' => $res['id'],
                    'title' => $res['chambre_nom'] . ' - ' . $res['nom'],
                    'start' => $res['date_arrivee'],
                    // FullCalendar exclut le jour de fin s'il n'y a pas d'heure, on ajoute donc +1 jour pour l'affichage correct
                    'end' => date('Y-m-d', strtotime($res['date_depart'] . ' +1 day')),
                    'color' => $color,
                    'extendedProps' => [
                        'statut' => $res['statut'],
                        'numero' => $res['numero_reservation']
                    ]
                ];
            }

            echo json_encode($events);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * API : Mise à jour des paramètres globaux du site
     * Reçoit un objet JSON contenant les clés de configuration vers le modèle Setting.
     * 
     * @return void
     */
    public function updateSettings() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new \Exception("Données de configuration invalides.");
            }

            $success = $this->settingModel->updateMultiple($data);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Paramètres mis à jour' : 'Erreur lors de la mise à jour']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Ajout d'une offre
     */
    public function addOffer() {
        header('Content-Type: application/json');
        try {
            if (empty($_POST['titre']) || empty($_POST['prix_texte'])) {
                throw new \Exception("Le titre et le prix sont obligatoires.");
            }

            $data = [
                'titre'       => $_POST['titre'],
                'description' => $_POST['description'] ?? '',
                'prix_texte'  => $_POST['prix_texte'],
                'tag'         => $_POST['tag'] ?? '',
                'image'       => 'offer-placeholder.png'
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->handleFileUpload($_FILES['image']);
            }

            $id = $this->offerModel->create($data);
            
            echo json_encode(['success' => true, 'message' => 'Offre ajoutée', 'id' => $id]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Mise à jour d'une offre
     */
    public function updateOffer() {
        header('Content-Type: application/json');
        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception("ID de l'offre manquant.");
            }

            $currentOffer = $this->offerModel->findById((int)$id);
            if (!$currentOffer) {
                throw new \Exception("Offre introuvable.");
            }

            $data = [
                'titre'       => $_POST['titre'] ?? $currentOffer['titre'],
                'description' => $_POST['description'] ?? $currentOffer['description'],
                'prix_texte'  => $_POST['prix_texte'] ?? $currentOffer['prix_texte'],
                'tag'         => $_POST['tag'] ?? $currentOffer['tag'],
                'image'       => $currentOffer['image']
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->handleFileUpload($_FILES['image']);
            }

            $success = $this->offerModel->update((int)$id, $data);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Offre mise à jour' : 'Erreur lors de la mise à jour']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Suppression d'une offre
     */
    public function deleteOffer() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id'])) {
                throw new \Exception("ID de l'offre manquant.");
            }

            $success = $this->offerModel->delete((int)$data['id']);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Offre supprimée' : 'Erreur lors de la suppression']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Mise à jour du statut d'une réservation restaurant
     */
    public function updateRestaurantReservationStatus() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id']) || empty($data['statut'])) {
                throw new \Exception("Données invalides.");
            }

            $db = \App\Config\Database::getInstance();
            $stmt = $db->prepare("UPDATE reservations_restaurant SET statut = ? WHERE id = ?");
            $success = $stmt->execute([$data['statut'], (int)$data['id']]);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Statut mis à jour' : 'Erreur lors de la mise à jour']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API : Suppression d'une réservation restaurant
     */
    public function deleteRestaurantReservation() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || empty($data['id'])) {
                throw new \Exception("ID de réservation manquant.");
            }

            $db = \App\Config\Database::getInstance();
            $stmt = $db->prepare("DELETE FROM reservations_restaurant WHERE id = ?");
            $success = $stmt->execute([(int)$data['id']]);
            
            echo json_encode(['success' => $success, 'message' => $success ? 'Réservation supprimée' : 'Erreur lors de la suppression']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
