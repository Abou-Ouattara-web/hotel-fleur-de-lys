<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Modèle Review (Avis)
 * Gère les retours d'expérience des clients sur l'hôtel et le restaurant.
 */
class Review {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les avis approuvés pour l'affichage public (Home page).
     */
    public function getApprovedReviews(int $limit = 6): array {
        $stmt = $this->db->prepare("
            SELECT * FROM avis 
            WHERE approuve = 1 
            ORDER BY date_avis DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère TOUS les avis pour le panneau d'administration.
     */
    public function getAllForAdmin(): array {
        $stmt = $this->db->query("
            SELECT a.*, c.email as client_email 
            FROM avis a 
            LEFT JOIN clients c ON a.client_id = c.id 
            ORDER BY a.approuve ASC, a.date_avis DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Enregistre un nouvel avis (soumis à validation).
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO avis (client_id, nom, note, commentaire, approuve) 
            VALUES (?, ?, ?, ?, 0)
        ");
        return $stmt->execute([
            $data['client_id'] ?? null,
            trim($data['nom']),
            (int)$data['note'],
            trim($data['commentaire'])
        ]);
    }

    /**
     * Approuve ou rejette un avis.
     */
    public function updateStatus(int $id, bool $approuve): bool {
        $stmt = $this->db->prepare("UPDATE avis SET approuve = ? WHERE id = ?");
        return $stmt->execute([$approuve ? 1 : 0, $id]);
    }

    /**
     * Supprime définitivement un avis.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM avis WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
