<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Chambres - Hôtel FLEUR DE LYS Tiassalé</title>
    <meta name="description" content="Découvrez nos chambres d'exception à l'Hôtel Fleur de Lys de Tiassalé. Chambres ventilées (20 000 - 35 000 FCFA) et climatisées (40 000 - 55 000 FCFA). Réservez dès aujourd'hui.">
    <meta name="keywords" content="chambre hotel tiassalé, chambre climatisée n'douci, chambre ventilée côte d'ivoire, tarifs hotel fleur de lys">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo URL_ROOT; ?>/rooms">
    <link rel="icon" type="image/png" href="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo URL_ROOT; ?>/rooms">
    <meta property="og:title" content="Nos Chambres d'Exception - Hôtel FLEUR DE LYS">
    <meta property="og:description" content="Chambres ventilées et climatisées avec tout le confort moderne. Tarifs à partir de 20 000 FCFA/nuit.">
    <meta property="og:image" content="<?php echo URL_ROOT; ?>/images/rooms-hero.jpg">

    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/rooms.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
</head>
<body>
    <header class="header mini-header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img">
                    <span class="logo-text">FLEUR DE LYS</span>
                </div>
                <div class="nav-menu" id="nav-menu">
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/">Accueil</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#chambres" class="active">Chambres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#services">Services</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#offres">Offres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#contact">Contact</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/reservation">Réservation</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/restaurant">Restauration</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/inscription">Inscription</a></li>
                    </ul>
                </div>

                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    <main class="container">
        <!-- Barre de filtrage -->
        <section class="filters-bar">
            <form action="<?php echo URL_ROOT; ?>/rooms" method="GET" class="filter-form">
                <div class="filter-group">
                    <label>Filtrer par type</label>
                    <select name="type">
                        <option value="">Tous les types</option>
                        <option value="Climatisée" <?php echo ($_GET['type'] ?? '') === 'Climatisée' ? 'selected' : ''; ?>>Climatisée</option>
                        <option value="Ventilée" <?php echo ($_GET['type'] ?? '') === 'Ventilée' ? 'selected' : ''; ?>>Ventilée</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Prix Min (FCFA)</label>
                    <input type="number" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>" placeholder="0">
                </div>
                <div class="filter-group">
                    <label>Prix Max (FCFA)</label>
                    <input type="number" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>" placeholder="100 000">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-filter">Appliquer les filtres</button>
                </div>
                <?php if (!empty($_GET)): ?>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="<?php echo URL_ROOT; ?>/rooms" class="btn-clear">Réinitialiser</a>
                </div>
                <?php endif; ?>
            </form>
        </section>

        <section class="rooms-list">
            <div class="rooms-grid">
                <?php 
                // Performance : Les 4 premières chambres (première ligne) chargent sans lazy-loading pour un LCP rapide (Meilleur score PageSpeed)
                $roomCount = 0; 
                foreach ($rooms as $room): 
                    $roomCount++; 
                ?>
                <div class="room-card">
                    <div class="room-badge"><?php echo $room['type'] ?? 'Standard'; ?></div>
                    <img src="<?php echo URL_ROOT; ?>/images/<?php echo $room['image'] ?? 'room-placeholder.jpg'; ?>" 
                         alt="<?php echo $room['nom']; ?>" 
                         class="room-img" 
                         <?php echo ($roomCount <= 4) ? 'fetchpriority="high"' : 'loading="lazy"'; ?> 
                         decoding="async"
                         width="400" height="300">
                    <div class="room-info">
                        <h3><?php echo $room['nom']; ?></h3>
                        <p class="room-desc"><?php echo $room['description'] ?? 'Une chambre magnifique et confortable.'; ?></p>
                        <p class="room-price">À partir de <?php echo number_format($room['prix'], 0, ',', ' '); ?> FCFA <span>/nuit</span></p>
                        <a href="<?php echo URL_ROOT; ?>/reservation?room=<?php echo $room['id']; ?>" class="btn-room">Réserver</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="footer-logo">
                    <p>L'excellence du luxe et du raffinement au cœur de la ville de Tiassalé.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Contact</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Axe N’douci-Tiassalé, Tiassalé</p>
                    <p><i class="fas fa-phone"></i> +225 07 03 24 44 64</p>
                    <p><i class="fas fa-envelope"></i> fleurdelys1821@gmail.com</p>
                </div>
                
                <div class="footer-col">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">CGV</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 Hôtel FLEUR DE LYS - Tous droits réservés</p>
                <p>Site réalisé par <span class="credit">YESHOU COMMUNICATION</span></p>
            </div>
        </div>
    </footer>
    <script src="<?php echo URL_ROOT; ?>/js/script.js" defer></script>
</body>
</html>
