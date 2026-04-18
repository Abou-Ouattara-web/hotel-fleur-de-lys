<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Modèle de Gestion des Offres Spéciales
 */
class Offer {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère toutes les offres
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM offres ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    /**
     * Récupère une offre par son ID
     */
    public function findById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM offres WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Ajoute une nouvelle offre
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO offres (titre, description, prix_texte, tag, image)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['titre'],
            $data['description'] ?? '',
            $data['prix_texte'] ?? '',
            $data['tag'] ?? '',
            $data['image'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Met à jour une offre existante
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE offres 
            SET titre = ?, description = ?, prix_texte = ?, tag = ?, image = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['prix_texte'],
            $data['tag'],
            $data['image'] ?? null,
            $id
        ]);
    }

    /**
     * Supprime une offre
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM offres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
