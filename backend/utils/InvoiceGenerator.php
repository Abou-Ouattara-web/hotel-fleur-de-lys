<?php

namespace App\Utils;

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

use FPDF;

/**
 * Générateur de Factures PDF
 * Utilise la bibliothèque FPDF pour créer un document A4 professionnel.
 */
class InvoiceGenerator extends FPDF {
    private $reservation;

    public function __construct($reservation) {
        parent::__construct();
        $this->reservation = $reservation;
        $this->AddPage();
        $this->SetFont('Arial', '', 12);
    }

    /**
     * Decode UTF-8 string to ISO-8859-1 for FPDF
     */
    private function decode($string) {
        return mb_convert_encoding((string)$string, 'ISO-8859-1', 'UTF-8');
    }

    /**
     * En-tête de la facture avec Logo et Coordonnées
     */
    public function Header() {
        // Logo (si le fichier existe)
        $logoPath = __DIR__ . '/../../frontend/public/images/logo-fleur-de-lys.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 6, 30);
        }

        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, $this->decode('HOTEL FLEUR DE LYS'), 0, 0, 'C');
        $this->Ln(20);

        // Informations Hôtel
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, $this->decode('Axe N\'douci-Tiassalé, Tiassalé, Côte d\'Ivoire'), 0, 1, 'R');
        $this->Cell(0, 5, $this->decode('Tél : +225 07 03 24 44 64'), 0, 1, 'R');
        $this->Cell(0, 5, $this->decode('Email : fleurdelys1821@gmail.com'), 0, 1, 'R');
        $this->Ln(10);
    }

    /**
     * Contenu principal de la facture
     */
    public function generate() {
        $res = $this->reservation;

        // Titre
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $this->decode('FACTURE N° ' . $res['numero_reservation']), 0, 1, 'L');
        $this->SetDrawColor(212, 175, 55); // Couleur Gold
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(10);

        // Informations Client
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 7, $this->decode('DESTINATAIRE :'), 0, 1);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, $this->decode($res['prenom'] . ' ' . $res['nom']), 0, 1);
        $this->Cell(0, 6, $this->decode($res['email']), 0, 1);
        $this->Ln(10);

        // Tableau des détails
        $this->SetFillColor(26, 26, 26); // Black
        $this->SetTextColor(255, 255, 255); // White
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(90, 10, $this->decode('Description'), 1, 0, 'C', true);
        $this->Cell(30, 10, $this->decode('Arrivée'), 1, 0, 'C', true);
        $this->Cell(30, 10, $this->decode('Départ'), 1, 0, 'C', true);
        $this->Cell(40, 10, $this->decode('Montant'), 1, 1, 'C', true);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // Calcul du séjour
        $this->Cell(90, 15, $this->decode('Séjour - ' . $res['chambre_nom']), 1, 0, 'L');
        $this->Cell(30, 15, $this->decode($res['date_arrivee']), 1, 0, 'C');
        $this->Cell(30, 15, $this->decode($res['date_depart']), 1, 0, 'C');
        $this->Cell(40, 15, number_format($res['prix_total'], 0, ',', ' ') . ' FCFA', 1, 1, 'R');

        // Total
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(150, 10, $this->decode('TOTAL À PAYER'), 0, 0, 'R');
        $this->SetTextColor(212, 175, 55); // Gold
        $this->Cell(40, 10, number_format($res['prix_total'], 0, ',', ' ') . ' FCFA', 0, 1, 'R');

        // Pied de page
        $this->Ln(20);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'I', 9);
        $this->MultiCell(0, 5, $this->decode("Merci d'avoir choisi l'Hôtel Fleur de Lys. \nPour toute question concernant cette facture, contactez-nous au +225 07 03 24 44 64."), 0, 'C');
    }

    /**
     * Pied de page standard FPDF
     */
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
