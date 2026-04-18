<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Room {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT *, prix_nuit AS prix FROM chambres");
        return $stmt->fetchAll();
    }

    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT id FROM chambres WHERE nom = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function findByNameLike($name) {
        $stmt = $this->db->prepare("SELECT id FROM chambres WHERE nom LIKE ? LIMIT 1");
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetch();
    }

    /**
     * Retourne la liste des chambres qui ne sont pas occupées entre $arrivee et $depart.
     * Utilise une sous-requête pour exclure les IDs de chambre ayant une réservation conflictuelle.
     * 
     * @param string $arrivee Date ISO (YYYY-MM-DD)
     * @param string $depart Date ISO (YYYY-MM-DD)
     * @return array Liste des chambres disponibles
     */
    public function getAvailableRooms(string $arrivee, string $depart): array {
        $stmt = $this->db->prepare("
            SELECT *, prix_nuit AS prix FROM chambres 
            WHERE id NOT IN (
                SELECT chambre_id FROM reservations 
                WHERE (date_arrivee < ? AND date_depart > ?) 
                  AND statut != 'annulee'
            )
            AND disponible = 1
        ");
        $stmt->execute([$depart, $arrivee]);
        return $stmt->fetchAll();
    }

    /**
     * Recherche des chambres avec des filtres optionnels (prix, type, disponibilité).
     */
    public function findWithFilters(array $filters): array {
        $sql = "SELECT *, prix_nuit AS prix FROM chambres WHERE 1=1";
        $params = [];

        if (!empty($filters['min_price'])) {
            $sql .= " AND prix_nuit >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND prix_nuit <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND (nom LIKE ? OR type LIKE ?)";
            $params[] = '%' . $filters['type'] . '%';
            $params[] = '%' . $filters['type'] . '%';
        }
        if (isset($filters['disponible']) && $filters['disponible'] !== '') {
            $sql .= " AND disponible = ?";
            $params[] = (int)$filters['disponible'];
        }

        $sql .= " ORDER BY prix_nuit ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une chambre par son ID.
     */
    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT *, prix_nuit AS prix FROM chambres WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Met à jour les informations d'une chambre.
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE chambres 
            SET nom = ?, prix_nuit = ?, disponible = ?, description = ?, image = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['nom'],
            $data['prix'] ?? $data['prix_nuit'],
            (int)$data['disponible'],
            $data['description'],
            $data['image'] ?? null,
            $id
        ]);
    }

    /**
     * Ajoute une nouvelle chambre en base de données.
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO chambres (nom, description, prix_nuit, type, capacite, disponible, image)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nom'],
            $data['description'] ?? '',
            $data['prix'],
            $data['type'] ?? 'Standard',
            (int)($data['capacite'] ?? 2),
            (int)($data['disponible'] ?? 1),
            $data['image'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Supprime une chambre de la base de données.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM chambres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
