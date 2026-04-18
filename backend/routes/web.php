<?php

// ==========================================
// DÉFINITION DES ROUTES DE L'APPLICATION
// ==========================================
// $router est déjà disponible ici (initialisé dans index.php)

// --- Pages Publiques ---
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/rooms', 'RoomController@index');
$router->addRoute('GET', '/reservation', 'ReservationController@index');
$router->addRoute('GET', '/restaurant', 'RestaurantController@index');

// --- API Publique (Chambres & Restaurant) ---
$router->addRoute('GET', '/api/rooms/availability', 'RoomController@checkAvailability');
$router->addRoute('POST', '/api/restaurant/reserve', 'RestaurantController@reserve');

// --- Gestion des Avis (Reviews) ---
$router->addRoute('POST', '/api/reviews', 'ReviewController@submit');
$router->addRoute('POST', '/api/admin/reviews/approve', 'ReviewController@approve'); // Modération Admin
$router->addRoute('POST', '/api/admin/reviews/delete', 'ReviewController@delete');   // Suppression Admin

// --- Processus de Réservation & Factures ---
$router->addRoute('POST', '/api/reservation', 'ReservationController@store');
$router->addRoute('GET', '/api/reservation/invoice', 'ReservationController@invoice'); // Génération PDF (FPDF)

// --- Module de Paiement ---
$router->addRoute('POST', '/api/payment', 'PaymentController@process');
$router->addRoute('GET', '/payment/checkout', 'PaymentController@checkoutSimulation');
$router->addRoute('GET', '/api/payment/status', 'PaymentController@status');
$router->addRoute('POST', '/api/payment/verify', 'PaymentController@verify');
$router->addRoute('POST', '/api/payment/confirmation', 'PaymentController@confirmation');

// --- Authentification (Connexion / Inscription) ---
$router->addRoute('GET', '/login', 'AuthController@loginView');
$router->addRoute('GET', '/inscription', 'AuthController@registerView');
$router->addRoute('POST', '/api/auth/login', 'AuthController@login');
$router->addRoute('POST', '/api/auth/register', 'AuthController@register');

// --- Espace Client ---
$router->addRoute('GET', '/dashboard', 'UserController@dashboard');
$router->addRoute('GET', '/logout', 'UserController@logout');
$router->addRoute('POST', '/api/user/update-profile', 'UserController@updateProfile');

// --- Administration (Sécurisé) ---
$router->addRoute('GET', '/admin', 'AdminController@dashboard');
$router->addRoute('POST', '/api/admin/rooms/update', 'AdminController@updateRoom');
$router->addRoute('POST', '/api/admin/rooms/add', 'AdminController@addRoom');
$router->addRoute('POST', '/api/admin/rooms/delete', 'AdminController@deleteRoom');
$router->addRoute('POST', '/api/admin/reservations/update', 'AdminController@updateReservationStatus');
$router->addRoute('POST', '/api/admin/reservations/delete', 'AdminController@deleteReservation');
$router->addRoute('GET', '/api/admin/reservations/calendar', 'AdminController@getCalendarEvents'); // Données Planning
$router->addRoute('POST', '/api/admin/settings/update', 'AdminController@updateSettings');       // Configuration du site
$router->addRoute('POST', '/api/admin/offers/add', 'AdminController@addOffer');
$router->addRoute('POST', '/api/admin/offers/update', 'AdminController@updateOffer');
$router->addRoute('POST', '/api/admin/offers/delete', 'AdminController@deleteOffer');
$router->addRoute('POST', '/api/admin/restaurant/update', 'AdminController@updateRestaurantReservationStatus');
$router->addRoute('POST', '/api/admin/restaurant/delete', 'AdminController@deleteRestaurantReservation');
