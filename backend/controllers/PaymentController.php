<?php

namespace App\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Utils\Mailer;
use Exception;

/**
 * Contrôleur des Paiements
 * Gère le module transactionnel (Mobile Money, Carte, etc.) ainsi que son statut.
 */
class PaymentController {
    /** @var Payment Instance Modèle du Système de paiement */
    private $paymentModel;
    private $reservationModel;

    public function __construct() {
        $this->paymentModel = new Payment();
        $this->reservationModel = new Reservation();
    }

    /**
     * Déclenche une nouvelle tentative de paiement factice ou communicant avec API externe.
     * Sécurise la validation par un ticket transactionnel.
     * 
     * @return void Sortie API JSON
     */
    public function process() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) $data = $_POST;

            // Valider les données
            if (empty($data['reservation_id']) || empty($data['method']) || empty($data['amount'])) {
                throw new Exception("Données de paiement incomplètes");
            }

            // Générer une référence
            $reference = strtoupper(substr($data['method'], 0, 3)) . date('Ymd') . strtoupper(substr(uniqid(), -6));
            
            // --- GESTION DE LA REDIRECTION GÉNÉRALE (TOUS MODES) ---
            // On exclut éventuellement 'especes' et 'virement' du flux de redirection immédiate si on veut les traiter à part, 
            // mais ici on généralise pour répondre à la demande "fait pareil pour les autres"
            
            $paymentId = $this->paymentModel->create([
                'reservation_id' => $data['reservation_id'],
                'methode_paiement' => $data['method'],
                'telephone' => $data['phone'] ?? 'N/A',
                'montant' => $data['amount'],
                'reference_transaction' => $reference,
                'statut' => 'en_attente'
            ]);

            // On renvoie une URL de redirection (Simulation Dynamique)
            echo json_encode([
                'success' => true,
                'message' => 'Redirection vers la plateforme de paiement...',
                'redirect_url' => URL_ROOT . '/payment/checkout?method=' . $data['method'] . '&ref=' . $reference . '&id=' . $data['reservation_id'],
                'data' => [
                    'payment_id' => $paymentId,
                    'reference' => $reference,
                    'status' => 'en_attente'
                ]
            ]);
            return;

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * EndPoint API pour l'interrogation du statut du paiement d'une réservation précise.
     * Route attendue: /api/payment/status?reservation_id=...
     */
    public function status() {
        header('Content-Type: application/json');
        if (isset($_GET['reservation_id'])) {
            $payment = $this->paymentModel->findByReservationId($_GET['reservation_id']);
            echo json_encode(['success' => true, 'data' => $payment]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de réservation manquant']);
        }
    }

    /**
     * Endpoint API pour vérifier une transaction via sa référence unique de ticket.
     * Attendu en post body type JSON via route '/api/payment/verify'.
     */
    public function verify() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['reference'])) {
            $payment = $this->paymentModel->findByReference($data['reference']);
            echo json_encode(['success' => true, 'data' => $payment]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Référence manquante']);
        }
    }

    /**
     * Reçoit la confirmation de paiement de la plateforme externe (ou simulation).
     */
    public function confirmation() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['reference']) || empty($data['reservation_id'])) {
                throw new Exception("Données de confirmation manquantes");
            }

            // Chercher le paiement par référence
            $payment = $this->paymentModel->findByReference($data['reference']);
            if (!$payment) {
                // Si non trouvé par référence, on essaie par reservation_id
                $payment = $this->paymentModel->findByReservationId($data['reservation_id']);
            }

            if ($payment) {
                // Mettre à jour le paiement
                $this->paymentModel->update($payment['id'], ['statut' => 'reussi']);
                
                // Mettre à jour la réservation
                $this->reservationModel->updateStatus($data['reservation_id'], 'confirmee');

                // Envoyer l'email
                $reservation = $this->reservationModel->findById((int)$data['reservation_id']);
                if ($reservation) {
                    Mailer::sendConfirmation($reservation);
                }

                echo json_encode(['success' => true, 'message' => 'Paiement confirmé avec succès']);
            } else {
                throw new Exception("Transaction introuvable");
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Affiche la page de simulation de paiement dynamique (Checkout).
     */
    public function checkoutSimulation() {
        $method = $_GET['method'] ?? 'wave';
        $reference = $_GET['ref'] ?? '';
        $reservationId = $_GET['id'] ?? '';
        
        if (empty($reference) || empty($reservationId)) {
            header('Location: ' . URL_ROOT . '/reservation');
            exit;
        }

        $reservation = $this->reservationModel->findById((int)$reservationId);
        
        // Inclure la vue de simulation dynamique
        require_once __DIR__ . '/../../frontend/views/payment_simulation.php';
    }
}
