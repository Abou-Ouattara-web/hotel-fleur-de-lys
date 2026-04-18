-- Création de la base de données
CREATE DATABASE IF NOT EXISTS fleur_de_lys_hotel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fleur_de_lys_hotel;

-- Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    mot_de_passe VARCHAR(255) NULL DEFAULT NULL,
    date_naissance DATE NULL DEFAULT NULL,
    newsletter BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des chambres
CREATE TABLE IF NOT EXISTS chambres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    type ENUM('ventilee', 'climatisee') NOT NULL,
    prix_nuit DECIMAL(10, 2) NOT NULL,
    description TEXT,
    capacite INT DEFAULT 2
) ENGINE=InnoDB;

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_reservation VARCHAR(20) NOT NULL UNIQUE,
    client_id INT NOT NULL,
    chambre_id INT NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    adultes INT NOT NULL DEFAULT 1,
    enfants INT NOT NULL DEFAULT 0,
    options JSON,
    prix_total DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'annulee') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (chambre_id) REFERENCES chambres(id)
) ENGINE=InnoDB;

-- Table des paiements
CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    methode_paiement VARCHAR(50) NOT NULL,
    telephone VARCHAR(20),
    montant DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'reussi', 'echoue') DEFAULT 'en_attente',
    reference_transaction VARCHAR(100),
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
) ENGINE=InnoDB;

-- Insertion des chambres par défaut
INSERT IGNORE INTO chambres (nom, type, prix_nuit) VALUES 
('Chambre Ventilée', 'ventilee', 20000),
('Chambre Ventilée Prestige', 'ventilee', 22000),
('Chambre Ventilée Supérieure', 'ventilee', 25000),
('Chambre Ventilée Familiale', 'ventilee', 30000),
('Chambre Ventilée standard', 'ventilee', 35000),
('Chambre Climatisée', 'climatise', 40000),
('Chambre Climatisée Executive', 'climatise', 42000),
('Chambre Climatisée Prestige', 'climatise', 45000),
('Chambre Climatisée Standard', 'climatise', 50000),
('Chambre Climatisée Royale', 'climatise', 55000);
