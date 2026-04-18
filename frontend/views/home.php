<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtel FLEUR DE LYS - Luxe & Confort à Tiassalé, Côte d'Ivoire</title>
    <meta name="description" content="L'Hôtel Fleur de Lys offre excellence, luxe et raffinement au cœur de la Côte d'Ivoire. Chambres climatisées et ventilées, restaurant gastronomique. Réservez votre séjour inoubliable à Tiassalé.">
    <meta name="keywords" content="hotel tiassalé, fleur de lys, hotel luxe côte d'ivoire, chambre n'douci-tiassalé, réservation hotel abidjan, nuit hotel tiassalé">
    <meta name="author" content="Hôtel Fleur de Lys">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo URL_ROOT; ?>/">

    <!-- Open Graph (Facebook / WhatsApp) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo URL_ROOT; ?>/">
    <meta property="og:title" content="Hôtel FLEUR DE LYS - Luxe & Confort à Tiassalé">
    <meta property="og:description" content="Découvrez l'excellence hôtelière au cœur de Tiassalé. Chambres d'exception, restaurant gastronomique, séjours inoubliables.">
    <meta property="og:image" content="<?php echo URL_ROOT; ?>/images/hero-bg.jpg">
    <meta property="og:locale" content="fr_CI">
    <meta property="og:site_name" content="Hôtel Fleur de Lys">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Hôtel FLEUR DE LYS - Tiassalé">
    <meta name="twitter:description" content="Luxe, confort et gastronomie au cœur de la Côte d'Ivoire.">
    <meta name="twitter:image" content="<?php echo URL_ROOT; ?>/images/hero-bg.jpg">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png">

    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
</head>
<body>
       <!-- Header avec navigation -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img" width="60" height="60" fetchpriority="high">
                    <span class="logo-text"><?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'FLEUR DE LYS'); ?></span>
                </div>
                
                <div class="nav-menu" id="nav-menu">
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/" class="active">Accueil</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#chambres">Chambres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#services">Services</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#offres">Offres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#contact">Contact</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/reservation">Réservation</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/restaurant">Restauration</a></li>
                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <li><a href="<?php echo URL_ROOT; ?>/inscription">Inscription</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo URL_ROOT; ?>/dashboard">Mon Compte</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
        
        <!-- Hero Section : Image de fond et message d'accueil -->
        <div class="hero">
            <div class="hero-content">
                <h1 class="animate-text"><?php echo __('hero_title'); ?></h1>
                <p class="animate-text-delay"><?php echo __('hero_subtitle'); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-primary"><?php echo __('hero_btn_res'); ?></a>
                    <a href="<?php echo URL_ROOT; ?>/#chambres" class="btn-secondary"><?php echo __('hero_btn_rooms'); ?></a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Barre de réservation rapide / Disponibilité -->
    <div class="booking-bar-container">
        <div class="container">
            <form id="check-availability-form" class="booking-bar">
                <div class="input-group">
                    <label for="check-in"><i class="fas fa-calendar-alt"></i> <?php echo __('booking_check_in'); ?></label>
                    <input type="date" id="check-in" name="arrivee" required>
                </div>
                <div class="input-group">
                    <label for="check-out"><i class="fas fa-calendar-check"></i> <?php echo __('booking_check_out'); ?></label>
                    <input type="date" id="check-out" name="depart" required>
                </div>
                <div class="input-group">
                    <label for="guests"><i class="fas fa-users"></i> <?php echo __('booking_guests'); ?></label>
                    <select id="guests" name="adultes">
                        <option value="1">1 <?php echo $_SESSION['lang'] === 'fr' ? 'Adulte' : 'Adult'; ?></option>
                        <option value="2" selected>2 <?php echo $_SESSION['lang'] === 'fr' ? 'Adultes' : 'Adults'; ?></option>
                        <option value="3">3 <?php echo $_SESSION['lang'] === 'fr' ? 'Adultes' : 'Adults'; ?></option>
                    </select>
                </div>
                <button type="submit" class="btn-check"><?php echo __('booking_btn'); ?></button>
            </form>
        </div>
    </div>
    
    <main>

    <!-- Présentation de l'hôtel -->
    <section class="presentation reveal" id="presentation">
        <div class="container">
            <h2 class="section-title">Présentation de l'Hôtel</h2>
            <p class="section-subtitle">Un établissement raffiné, pensé pour les séjours de détente, d'affaires et en famille.</p>
            <div class="presentation-grid">
                <div class="presentation-card">
                    <h3>Notre concept</h3>
                    <p><?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'Fleur de Lys'); ?> associe élégance, hospitalité ivoirienne et confort moderne pour une expérience haut de gamme.</p>
                </div>
                <div class="presentation-card">
                    <h3>Localisation</h3>
                    <p>Situé sur l'<?php echo htmlspecialchars($siteSettings['hotel_address'] ?? 'axe N\'douci-Tiassalé'); ?>, l'hôtel offre un accès rapide au centre-ville.</p>
                </div>
                <div class="presentation-card">
                    <h3>Points forts</h3>
                    <p>Piscines, restauration soignée, espaces évènementiels et service personnalisé 24h/24.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Chambres avec carousel horizontal -->
    <section id="chambres" class="chambres reveal">
        <div class="container">
            <h2 class="section-title">Nos Chambres d'Exception</h2>
            <p class="section-subtitle"><?php echo count($rooms); ?> chambres uniques pour un séjour inoubliable et exceptionnel</p>
            
            <div class="carousel-container">
                <button class="carousel-btn prev" id="prevBtn" aria-label="Voir les précédentes"><i class="fas fa-chevron-left"></i></button>
                
                <div class="carousel-track-container">
                    <ul class="carousel-track" id="carouselTrack">
                        <?php 
                        // Performance : On ne charge en "lazy" qu'après les 2 premières images du carousel pour un affichage immédiat
                        $renderCount = 0; 
                        foreach ($rooms as $room): 
                            $renderCount++; 
                        ?>
                        <li class="carousel-card">
                            <div class="room-card">
                                <div class="room-badge <?= ($room['type'] === 'climatise' || strpos(strtolower($room['nom']), 'clim') !== false) ? 'climatise' : '' ?>">
                                    <?= htmlspecialchars($room['type'] === 'climatise' ? 'Climatisée' : 'Ventilée') ?>
                                </div>
                                <img src="<?php echo URL_ROOT; ?>/images/<?php echo $room['image'] ?: 'room-placeholder.jpg'; ?>" 
                                     alt="<?= htmlspecialchars($room['nom']) ?>" 
                                     class="room-img" 
                                     <?= ($renderCount <= 2) ? 'fetchpriority="high"' : 'loading="lazy"' ?> 
                                     decoding="async"
                                     width="400" height="300">
                                <div class="room-info">
                                    <h3><?= htmlspecialchars($room['nom']) ?></h3>
                                    <p class="room-desc"><?= htmlspecialchars($room['description']) ?></p>
                                    <div class="room-features">
                                        <span><i class="fas fa-wifi"></i> WiFi</span>
                                        <span><i class="fas fa-shower"></i> Bain/Douche</span>
                                        <span><i class="fas fa-users"></i> <?= $room['capacite'] ?> pers.</span>
                                    </div>
                                    <p class="room-price">À partir de <?= number_format($room['prix'], 0, ',', ' ') ?> FCFA <span>/nuit</span></p>
                                    <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-room">Réserver</a>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <button class="carousel-btn next" id="nextBtn" aria-label="Voir les suivantes"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    <!-- Section Offres Spéciales -->
    <section class="offers reveal" id="offres">
        <div class="container">
            <h2 class="section-title">Nos Offres Spéciales</h2>
            <p class="section-subtitle">Profitez d'expériences exclusives conçues pour votre confort et votre plaisir.</p>
            
            <div class="offers-grid">
                <?php if (!empty($offers)): ?>
                    <?php foreach ($offers as $off): ?>
                        <div class="offer-card">
                            <img src="<?php echo URL_ROOT; ?>/images/<?php echo $off['image'] ?: 'offer-placeholder.png'; ?>" 
                                 alt="<?= htmlspecialchars($off['titre']) ?>" 
                                 class="offer-img-bg"
                                 loading="lazy">
                            <div class="offer-overlay">
                                <span class="offer-tag"><?= htmlspecialchars($off['tag'] ?: 'Hôtel') ?></span>
                                <h3><?= htmlspecialchars($off['titre']) ?></h3>
                                <p><?= htmlspecialchars($off['description']) ?></p>
                                <span class="offer-price"><?= htmlspecialchars($off['prix_texte']) ?></span>
                                <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-offer">En savoir plus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: span 3; text-align: center; padding: 40px; color: var(--gray);">
                        <p>Aucune offre spéciale pour le moment. Revenez bientôt !</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

   <!-- Section Équipements -->
<section class="equipements" id="services">
    <div class="container">
        <h2 class="section-title">Nos Équipements Prestige</h2>
        
        <div class="equipements-grid">
            <div class="equipement-card reveal">
                <img src="<?php echo URL_ROOT; ?>/images/piscine-1.jpg" alt="Piscine" class="equipement-img" loading="lazy" width="400" height="250">
                <div class="equipement-info">
                    <span class="equipement-icon"><i class="fas fa-swimmer"></i></span>
                    <h3>Piscine Prestige</h3>
                    <p>Piscine propre et moderne pour vos moments de détente.</p>
                </div>
            </div>
            <div class="equipement-card reveal">
                <img src="<?php echo URL_ROOT; ?>/images/salle-event-1.jpg" alt="Salle" class="equipement-img" loading="lazy" width="400" height="250">
                <div class="equipement-info">
                    <span class="equipement-icon"><i class="fas fa-glass-cheers"></i></span>
                    <h3>Salles d'Évènements</h3>
                    <p>Espaces modulables pour vos séminaires et mariages.</p>
                </div>
            </div>
            <div class="equipement-card reveal">
                <img src="<?php echo URL_ROOT; ?>/images/restaurant-2.jpg" alt="Restaurant" class="equipement-img" loading="lazy" width="400" height="250">
                <div class="equipement-info">
                    <span class="equipement-icon"><i class="fas fa-utensils"></i></span>
                    <h3>Restaurant Gastronomique</h3>
                    <p>Une expérience culinaire unique au cœur de Tiassalé.</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Avis clients -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Avis Clients</h2>
            <div class="testimonials-grid">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <blockquote class="testimonial-card">
                            <div class="stars"><?php for($i=1; $i<=5; $i++) echo '<i class="fa'.($i <= $review['note'] ? 's' : 'r').' fa-star"></i>'; ?></div>
                            <p>“<?php echo htmlspecialchars($review['commentaire']); ?>”</p>
                            <span>- <?php echo htmlspecialchars($review['nom']); ?></span>
                        </blockquote>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contactez-nous</h2>
            <div class="contact-grid">
                <div class="contact-box">
                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($siteSettings['hotel_phone'] ?? '+225 07 03 24 44 64'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($siteSettings['hotel_email'] ?? 'fleurdelys1821@gmail.com'); ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($siteSettings['hotel_address'] ?? 'Tiassalé, Côte d\'Ivoire'); ?></p>
                </div>
                <form class="contact-form">
                    <input type="text" placeholder="Nom complet" required>
                    <input type="email" placeholder="Email" required>
                    <textarea rows="4" placeholder="Votre message" required></textarea>
                    <button type="submit" class="btn-submit">Envoyer le message</button>
                </form>
            </div>
        </div>
    </section>
    
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="footer-logo">
                    <p>L'excellence du luxe et du raffinement au cœur de la Côte d'Ivoire.</p>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($siteSettings['hotel_phone'] ?? '+225 07 03 24 44 64'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($siteSettings['hotel_email'] ?? 'fleurdelys1821@gmail.com'); ?></p>
                </div>
                <div class="footer-col">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Mentions Légales</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'Hôtel FLEUR DE LYS'); ?> - Tous droits réservés</p>
                <p>Site réalisé par <span class="credit">YESHOU COMMUNICATION</span></p>
            </div>
        </div>
    </footer>

    <script src="<?php echo URL_ROOT; ?>/js/script.js" defer></script>
</body>
</html>
