<?php

namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Models\Setting;
use App\Utils\InvoiceGenerator;
use Exception;

/**
 * Contrôleur des Réservations
 * Responsable de l'affichage de la page de réservation et de l'enregistrement 
 * des nouvelles disponibilités (flux client et chambre).
 */
class ReservationController {
    private $reservationModel;
    private $roomModel;
    private $userModel;
    private $settingModel;

    public function __construct() {
        $this->reservationModel = new Reservation();
        $this->roomModel        = new Room();
        $this->userModel        = new User();
        $this->settingModel     = new Setting();
    }

    /**
     * Affiche l'interface utilisateur pour la recherche et sélection (Route /reservation)
     */
    public function index() {
        $rooms = $this->roomModel->getAll();
        $siteSettings = $this->settingModel->getAll();
        require_once __DIR__ . '/../../frontend/views/reservation.php';
    }

    /**
     * Traite les données envoyées par l'API pour créer une réservation.
     * Génère également un ID ou récupère le client existant.
     * 
     * @return void Sortie API JSON
     */
    public function store() {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data)) {
                throw new Exception('Données JSON invalides ou absentes.');
            }

            // --- Validation des champs obligatoires ---
            $required = ['prenom', 'nom', 'email', 'chambre', 'arrivee', 'depart', 'adultes', 'total'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Le champ « $field » est requis.");
                }
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("L'adresse e-mail est invalide.");
            }
            if ((int)$data['adultes'] < 1) {
                throw new Exception("Au moins 1 adulte est requis.");
            }

            // --- Gestion du client (trouve ou crée un compte invité) ---
            $client = $this->userModel->findByEmail($data['email']);
            if (!$client) {
                $clientId = $this->userModel->create([
                    'prenom'    => trim($data['prenom']),
                    'nom'       => trim($data['nom']),
                    'email'     => trim($data['email']),
                    'telephone' => $data['telephone'] ?? null,
                    // Pas de mot de passe → compte invité
                    'password'  => null,
                ]);
            } else {
                $clientId = $client['id'];
            }

            // --- Résolution de la chambre ---
            $roomInput = trim((string)($data['chambre'] ?? ''));

            // Si c'est un ID numérique (nouveau système dynamique)
            if (is_numeric($roomInput)) {
                $chambre = $this->roomModel->findById((int)$roomInput);
            } else {
                // Fallback (ancien système ou labels textuels)
                $roomMap = [
                    'ventilee-'          => 'Chambre Ventilée',
                    'ventilee-Prestige'  => 'Chambre Ventilée Prestige',
                    'ventilee-Supérieure'=> 'Chambre Ventilée Supérieure',
                    'ventilee-Familiale' => 'Chambre Ventilée Familiale',
                    'ventilee-Standard'  => 'Chambre Ventilée standard',
                    'climatise-'         => 'Chambre Climatisée',
                    'climatise-executive'=> 'Chambre Climatisée Executive',
                    'climatise-prestige' => 'Chambre Climatisée Prestige',
                    'climatise-Standard' => 'Chambre Climatisée Standard',
                    'climatise-royale'   => 'Chambre Climatisée Royale',
                ];
                $roomName  = $roomMap[$roomInput] ?? preg_replace('/\s*-\s*\d[\d\s]*FCFA\/nuit$/u', '', $roomInput);
                $chambre = $this->roomModel->findByName($roomName) ?? $this->roomModel->findByNameLike($roomName);
            }

            if (!$chambre) {
                throw new Exception('Chambre introuvable. Veuillez vérifier votre sélection.');
            }

            // --- Création de la réservation (la validation des dates est dans le modèle) ---
            $numeroReservation = $this->reservationModel->generateNumber();
            $reservationId = $this->reservationModel->create([
                'numero_reservation' => $numeroReservation,
                'client_id'          => $clientId,
                'chambre_id'         => $chambre['id'],
                'arrivee'            => $data['arrivee'],
                'depart'             => $data['depart'],
                'adultes'            => $data['adultes'],
                'enfants'            => $data['enfants'] ?? 0,
                'options'            => $data['options'] ?? [],
                'total'              => $data['total'],
            ]);

            echo json_encode([
                'success'            => true,
                'message'            => 'Réservation créée avec succès.',
                'reservation_id'     => $reservationId,
                'reservation_number' => $numeroReservation,
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Génère et télécharge la facture PDF d'une réservation.
     * Accessible via /api/reservation/invoice?id=...
     */
    public function invoice() {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception("ID de réservation manquant.");
            }

            $reservation = $this->reservationModel->findById((int)$id);
            if (!$reservation) {
                throw new Exception("Réservation introuvable.");
            }

            // Génération du PDF
            $pdf = new InvoiceGenerator($reservation);
            $pdf->generate();
            
            // Envoyer le document au navigateur
            $filename = 'Facture_FleurDeLys_' . $reservation['numero_reservation'] . '.pdf';
            $pdf->Output('D', $filename); // 'D' force le téléchargement

        } catch (Exception $e) {
            header('Content-Type: text/plain');
            echo "Erreur lors de la génération de la facture : " . $e->getMessage();
        }
    }
}
