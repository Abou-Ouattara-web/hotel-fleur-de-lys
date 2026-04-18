<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use InvalidArgumentException;

class Reservation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Génère un numéro de réservation unique (garanti en base)
     */
    public function generateNumber(): string {
        do {
            $number = 'FL' . date('Ymd') . strtoupper(substr(uniqid(), -5));
            $stmt = $this->db->prepare("SELECT id FROM reservations WHERE numero_reservation = ?");
            $stmt->execute([$number]);
        } while ($stmt->fetch()); // Réessaye si collision (très rare)
        return $number;
    }

    /**
     * Crée une réservation après validation métier des dates
     */
    public function create(array $data): int {
        // Validation des dates
        $arrivee = new \DateTime($data['arrivee']);
        $depart  = new \DateTime($data['depart']);
        $today   = new \DateTime('today');

        if ($arrivee < $today) {
            throw new InvalidArgumentException("La date d'arrivée ne peut pas être dans le passé.");
        }
        if ($depart <= $arrivee) {
            throw new InvalidArgumentException("La date de départ doit être postérieure à la date d'arrivée.");
        }

        // Vérifier la disponibilité de la chambre sur ces dates
        $stmt = $this->db->prepare("
            SELECT id FROM reservations
            WHERE chambre_id = ?
              AND statut != 'annulee'
              AND date_arrivee < ?
              AND date_depart > ?
        ");
        $stmt->execute([$data['chambre_id'], $data['depart'], $data['arrivee']]);
        if ($stmt->fetch()) {
            throw new InvalidArgumentException("Cette chambre n'est pas disponible pour les dates sélectionnées.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO reservations (
                numero_reservation, client_id, chambre_id,
                date_arrivee, date_depart, adultes, enfants,
                options, prix_total, statut
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')
        ");

        $stmt->execute([
            $data['numero_reservation'],
            $data['client_id'],
            $data['chambre_id'],
            $data['arrivee'],
            $data['depart'],
            (int)($data['adultes'] ?? 1),
            (int)($data['enfants'] ?? 0),
            json_encode($data['options'] ?? []),
            $data['total'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): bool {
        $allowed = ['en_attente', 'confirmee', 'annulee', 'terminee'];
        if (!in_array($status, $allowed)) {
            throw new InvalidArgumentException("Statut de réservation invalide : $status");
        }
        $stmt = $this->db->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT r.*, c.prenom, c.nom, c.email,
                   ch.nom AS chambre_nom, ch.prix_nuit
            FROM reservations r
            JOIN clients c  ON r.client_id  = c.id
            JOIN chambres ch ON r.chambre_id = ch.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Récupère la liste complète des réservations pour le tableau de bord admin.
     */
    public function getAllForAdmin(): array {
        $stmt = $this->db->query("
            SELECT r.*, c.prenom, c.nom, c.email, ch.nom as chambre_nom
            FROM reservations r
            JOIN clients c ON r.client_id = c.id
            JOIN chambres ch ON r.chambre_id = ch.id
            ORDER BY r.date_arrivee DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupère les statistiques agrégées par mois (Revenus et Volume).
     */
    public function getStatsByMonth(): array {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(date_arrivee, '%Y-%m') as mois,
                COUNT(*) as nb_reservations,
                SUM(prix_total) as CA
            FROM reservations 
            WHERE statut != 'annulee'
            GROUP BY mois
            ORDER BY mois ASC
            LIMIT 12
        ");
        return $stmt->fetchAll();
    }

    /**
     * Supprime définitivement une réservation et ses paiements associés.
     */
    public function delete(int $id): bool {
        // Optionnel : s'il y a des paiements associés, on peut les supprimer s'il y a une contrainte de clé étrangère
        $stmt = $this->db->prepare("DELETE FROM paiements WHERE reservation_id = ?");
        $stmt->execute([$id]);

        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = ?");
        return $stmt->execute([$id]);
    }
    /**
     * Récupère toutes les réservations d'un utilisateur spécifique.
     */
    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT r.*, ch.nom as chambre_nom, ch.image as chambre_image
            FROM reservations r
            JOIN chambres ch ON r.chambre_id = ch.id
            WHERE r.client_id = ?
            ORDER BY r.date_arrivee DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
