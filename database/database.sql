-- Création de la base de données
CREATE DATABASE IF NOT EXISTS fleur_de_lys_hotel;
USE fleur_de_lys_hotel;

-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    date_naissance DATE,
    mot_de_passe VARCHAR(255) NULL DEFAULT NULL,
    role VARCHAR(20) DEFAULT 'client',
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    newsletter BOOLEAN DEFAULT TRUE
);

-- Table des chambres
CREATE TABLE chambres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'ventilee' ou 'climatise'
    prix_nuit DECIMAL(10,2) NOT NULL,
    capacite INT NOT NULL,
    description TEXT,
    image VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE
);

-- Insertion des chambres
INSERT INTO chambres (nom, type, prix_nuit, capacite, description) VALUES
('Chambre Ventilée ', 'ventilee', 20000.00, 2, 'Chambre  avec ventilation naturelle et vue jardin'),
('Chambre Ventilée Prestige', 'ventilee', 22000.00, 2, 'Chambre spacieuse avec ventilation et terrasse'),
('Chambre Ventilée Supérieure', 'ventilee', 25000.00, 2, 'Chambre confortable avec ventilation'),
('Chambre Ventilée Familiale', 'ventilee', 30000.00, 4, 'Grande chambre ventilée pour famille avec espace'),
('Chambre Ventilée standard', 'ventilee', 35000.00, 2, 'Standard ventilée avec espace de travail et vue sur le paysage'),
('Chambre Climatisée ', 'climatise', 40000.00, 2, 'Chambre climatisée avec literie haut de gamme'),
('Chambre Climatisée Executive', 'climatise', 42000.00, 2, 'Chambre climatisée avec espace travail'),
('Chambre Climatisée Prestige', 'climatise', 45000.00, 2, 'Chambre climatisée avec balcon'),
('Chambre Climatisée Standard', 'climatise', 50000.00, 3, 'chambre standard climatisée avec espace travaille'),
('Chambre Climatisée Royale', 'climatise', 55000.00, 2, ' Chambre royale climatisée');

-- Table des réservations
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_reservation VARCHAR(20) UNIQUE NOT NULL,
    client_id INT,
    chambre_id INT NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    adultes INT NOT NULL,
    enfants INT DEFAULT 0,
    options TEXT, -- Stocké en JSON
    prix_total DECIMAL(10,2) NOT NULL,
    statut VARCHAR(20) DEFAULT 'en_attente', -- 'en_attente', 'confirmee', 'annulee', 'terminee'
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (chambre_id) REFERENCES chambres(id)
);

-- Table des paiements
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    methode_paiement VARCHAR(50) NOT NULL, -- 'moov', 'mtn', 'orange', 'wave'
    telephone VARCHAR(20) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    statut VARCHAR(20) DEFAULT 'en_attente', -- 'en_attente', 'reussi', 'echoue'
    reference_transaction VARCHAR(100),
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);

-- Table des menus du restaurant
CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie VARCHAR(50) NOT NULL, -- 'entree', 'plat', 'dessert', 'vin'
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE
);

-- Insertion des menus
INSERT INTO menus (categorie, nom, description, prix) VALUES
('entree', 'Foie Gras de Canard', 'Foie gras poêlé, chutney de figues et pain brioché', 28),
('entree', 'Homard Bleu', 'Homard rôti, émulsion au champagne et légumes de saison', 35),
('entree', 'Saint-Jacques', 'Noix de Saint-Jacques snackées, purée de céleri et truffe', 32),
('plat', 'Filet de Boeuf', 'Filet de boeuf Wellington, sauce périgueux et légumes glacés', 48),
('plat', 'Bar de Ligne', 'Bar de ligne rôti, écrasé de pommes de terre et bisque', 42),
('plat', 'Risotto aux Truffes', 'Risotto crémeux aux truffes noires et parmesan', 38),
('dessert', 'Soufflé au Chocolat', 'Soufflé chaud au chocolat noir, glace à la vanille', 16),
('dessert', 'Île Flottante', 'Île flottante aux amandes caramélisées, crème anglaise', 14),
('dessert', 'Assortiment de Macarons', 'Sélection de macarons faits maison (6 pièces)', 12),
('vin', 'Château Margaux', 'Grand Cru Classé, Bordeaux 2015', 150),
('vin', 'Dom Pérignon', 'Champagne Vintage 2010', 180),
('vin', 'Meursault', 'Bourgogne blanc, 2018', 85);

-- Table des réservations restaurant
CREATE TABLE reservations_restaurant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    date_reservation DATE NOT NULL,
    heure_reservation TIME NOT NULL,
    couverts INT NOT NULL,
    occasion VARCHAR(100),
    notes TEXT,
    statut VARCHAR(20) DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Table des avis clients
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    nom VARCHAR(100) NOT NULL,
    note INT NOT NULL CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approuve BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Table des newsletters
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE
);

-- Création des index pour améliorer les performances
CREATE INDEX idx_reservations_dates ON reservations(date_arrivee, date_depart);
CREATE INDEX idx_reservations_client ON reservations(client_id);
CREATE INDEX idx_reservations_statut ON reservations(statut);
CREATE INDEX idx_paiements_reservation ON paiements(reservation_id);
CREATE INDEX idx_clients_email ON clients(email);