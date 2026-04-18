<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Setting {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les paramètres
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM site_settings");
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $results ?: [];
    }

    /**
     * Met à jour ou crée plusieurs paramètres simultanément.
     * Utilise une transaction pour garantir que tous les paramètres sont mis à jour 
     * ou aucun en cas d'erreur.
     * 
     * @param array $settings Tableau associatif [clé => valeur]
     * @return bool True si succès, False sinon
     */
    public function updateMultiple(array $settings): bool {
        try {
            $this->db->beginTransaction();
            
            // On utilise ON DUPLICATE KEY UPDATE pour mettre à jour la valeur si la clé existe déjà
            $stmt = $this->db->prepare("INSERT INTO site_settings (setting_key, setting_value) 
                                      VALUES (?, ?) 
                                      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            
            foreach ($settings as $key => $value) {
                $stmt->execute([$key, $value]);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Récupère un paramètre spécifique
     */
    public function get(string $key, $default = null) {
        $stmt = $this->db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : $default;
    }
}
