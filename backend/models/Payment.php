<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO paiements (
                reservation_id, methode_paiement, telephone, montant, statut, reference_transaction, date_paiement
            ) VALUES (?, ?, ?, ?, 'en_attente', ?, NOW())
        ");
        
        $stmt->execute([
            $data['reservation_id'],
            $data['methode_paiement'],
            $data['telephone'],
            $data['montant'],
            $data['reference_transaction'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        
        $stmt = $this->db->prepare("UPDATE paiements SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function findByReservationId($reservationId) {
        $stmt = $this->db->prepare("
            SELECT p.*, r.numero_reservation 
            FROM paiements p
            JOIN reservations r ON p.reservation_id = r.id
            WHERE p.reservation_id = ?
            ORDER BY p.date_paiement DESC
            LIMIT 1
        ");
        $stmt->execute([$reservationId]);
        return $stmt->fetch();
    }

    public function findByReference($reference) {
        $stmt = $this->db->prepare("
            SELECT p.*, r.numero_reservation 
            FROM paiements p
            JOIN reservations r ON p.reservation_id = r.id
            WHERE p.reference_transaction = ?
        ");
        $stmt->execute([$reference]);
        return $stmt->fetch();
    }
}
