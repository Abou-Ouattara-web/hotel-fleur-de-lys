<?php

namespace App\Controllers;

use App\Models\Offer;
use App\Models\Review;
use App\Models\Room;
use App\Models\Setting;

/**
 * Contrôleur de la Page d'Accueil
 * Gère l'affichage général de la page principale de l'hôtel (Landing Page).
 */
class HomeController {
    /**
     * Point d'entrée principal (Route "/").
     * Appelle le fichier de la vue qui contient l'ensemble des carrousels et sections.
     * 
     * @return void Affiche la vue home.php
     */
    public function index() {
        $reviewModel = new Review();
        $reviews = $reviewModel->getApprovedReviews(6);
        
        $roomModel = new Room();
        $rooms = $roomModel->getAll();

        $settingModel = new Setting();
        $siteSettings = $settingModel->getAll();

        $offerModel = new Offer();
        $offers = $offerModel->getAll();
        
        // Charger la vue d'accueil
        require_once __DIR__ . '/../../frontend/views/home.php';
    }
}
