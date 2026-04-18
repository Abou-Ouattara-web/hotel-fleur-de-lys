<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Gastronomique - Hôtel FLEUR DE LYS Tiassalé</title>
    <meta name="description" content="Savourez une cuisine raffinée au restaurant de l'Hôtel Fleur de Lys. Entrées ivoiriennes, plats traditionnels et internationaux, desserts maison, cave à vins. Tiassalé, Côte d'Ivoire.">
    <meta name="keywords" content="restaurant tiassalé, gastronomie côte d'ivoire, attiéké poulet, foutou sauce graine, restaurant hotel luxe">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo URL_ROOT; ?>/restaurant">
    <link rel="icon" type="image/png" href="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png">

    <!-- Open Graph -->
    <meta property="og:type" content="restaurant">
    <meta property="og:url" content="<?php echo URL_ROOT; ?>/restaurant">
    <meta property="og:title" content="Restaurant Gastronomique - Hôtel FLEUR DE LYS">
    <meta property="og:description" content="Découvrez notre carte : spécialités ivoiriennes, plats internationaux, desserts maison et cave à vins.">
    <meta property="og:image" content="<?php echo URL_ROOT; ?>/images/restaurant-1.jpg">

    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/restaurant.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
    <style>
        /* Modernisation UI Restaurant (Conservation des couleurs or/blanc/noir) */
        .restaurant-hero {
            background: none !important;
            padding: 50px 0 20px !important;
            text-align: center;
            color: #1A1A1A !important;
        }
        .restaurant-hero h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            margin-bottom: 20px;
        }
        .restaurant-section .container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }
        .category-title {
            width: 100%;
            text-align: center;
            margin: 50px 0 30px !important;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #D4AF37 !important;
            border-bottom: 1px solid rgba(212,175,55,0.3);
            padding-bottom: 15px;
        }
        .section-title {
            width: 100%;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1A1A1A;
            margin-bottom: 20px;
        }
        .menu-item {
            background: #fff !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            width: calc(33.333% - 20px) !important;
            min-width: 300px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
            transition: all 0.4s ease !important;
            border: 1px solid #f9f9f9 !important;
            display: flex !important;
            flex-direction: column !important;
            margin-bottom: 0 !important;
        }
        .menu-item:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
            border-color: rgba(212,175,55,0.3) !important;
        }
        .menu-item .menu-image {
            width: 100% !important;
            height: 250px !important;
            object-fit: cover !important;
            transition: transform 0.6s ease !important;
        }
        .menu-item:hover .menu-image {
            transform: scale(1.08) !important;
        }
        .menu-content {
            padding: 25px !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            background: #fff;
            position: relative;
            z-index: 2;
        }
        .item-name {
            font-family: 'Cormorant Garamond', serif !important;
            font-size: 1.6rem !important;
            color: #1A1A1A !important;
            margin-bottom: 10px !important;
            font-weight: 600 !important;
        }
        .item-desc {
            font-size: 0.95rem !important;
            color: #666 !important;
            flex: 1 !important;
            line-height: 1.6 !important;
        }
        .item-price {
            font-weight: 700 !important;
            color: #D4AF37 !important;
            font-size: 1.2rem !important;
            margin-top: 15px !important;
            padding-top: 15px !important;
            border-top: 1px dashed #eee !important;
        }
        /* Hero Section Améliorée (page-title) */
        .page-title {
            background: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.55)), url('<?php echo URL_ROOT; ?>/images/restaurant-1.jpg') center/cover no-repeat fixed !important;
            min-height: 60vh !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 80px 0 0 0 !important; /* Décalage navbar */
            position: relative;
        }
        .page-title .container {
            width: 100% !important;
            position: relative;
            z-index: 2;
            animation: fadeInHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        @keyframes fadeInHero {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-title h1 {
            font-size: 4rem !important;
            text-shadow: 2px 4px 8px rgba(0,0,0,0.9) !important;
            color: #fff !important;
            margin-bottom: 20px !important;
            font-family: 'Cormorant Garamond', serif !important;
            font-weight: 700 !important;
            letter-spacing: 2px;
        }
        .page-title .breadcrumb {
            display: inline-block !important;
            font-size: 1.1rem !important;
            background: rgba(0,0,0,0.2) !important;
            padding: 12px 30px !important;
            border-radius: 50px !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
            transition: all 0.3s ease;
        }
        .page-title .breadcrumb:hover {
            background: rgba(0,0,0,0.4) !important;
            transform: translateY(-2px);
            border-color: #D4AF37 !important;
        }
        .page-title .breadcrumb a, .page-title .breadcrumb span {
            color: #fff !important;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.9) !important;
            font-weight: 500 !important;
            text-decoration: none !important;
        }
        .page-title .breadcrumb a:hover {
            color: #D4AF37 !important;
        }
        @media (max-width: 768px) {
            .page-title { min-height: 40vh !important; padding-top: 60px !important; }
            .page-title h1 { font-size: 2.5rem !important; }
            .page-title .breadcrumb { font-size: 0.9rem !important; padding: 8px 15px !important; }
        }
    </style>
</head>
<body>
    <!-- Header avec navigation -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img" width="60" height="60">
                    <span class="logo-text">FLEUR DE LYS</span>
                </div>
                
                <div class="nav-menu" id="nav-menu">
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/">Accueil</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#chambres">Chambres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#services">Services</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#offres">Offres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#contact">Contact</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/reservation">Réservation</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/restaurant" class="active">Restauration</a></li>
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
    </header>
    <main>

    <!-- Page Title -->
    <div class="page-title">
        <div class="container">
            <h1>Notre Restaurant</h1>
            <div class="breadcrumb">
                <a href="<?php echo URL_ROOT; ?>/">Accueil</a> / <span>Restaurant</span>
            </div>
        </div>
    </div>

    <!-- Section Restaurant Hero -->
    <section class="restaurant-hero">
        <div class="container">
            <div class="restaurant-intro">
                <h2>Une expérience gastronomique unique</h2>
                <p>Découvrez la cuisine raffinée de notre chef étoilé, une symphonie de saveurs qui éveille les sens.</p>
            </div>
        </div>
    </section>

   <!-- Section Restaurant -->
<section class="restaurant-section">
    <div class="container">
        <h2 class="section-title">Notre Carte</h2>
        
        <!-- ENTRÉES -->
        <h3 class="category-title">Nos Entrées</h3>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/entree-1.jpg" alt="Foie Gras" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Avocat à l'oeuf</h4>
                <p class="item-desc">Demi-avocat au beurre crémeux, accompagné d'oeuf tranché et servi avec du pain.</p>
                <p class="item-price">2 500 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/entree-2.jpg" alt="Homard" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Homard Bleu</h4>
                <p class="item-desc">Homard rôti, émulsion au champagne et légumes de saison</p>
                <p class="item-price">3 500 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/entree-3.jpg" alt="Saint-Jacques" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Salade au poulet</h4>
                <p class="item-desc">Salade accompagnée de poulet frit découpé en tranches, servie avec du pain.</p>
                <p class="item-price">3 000 FCFA</p>
            </div>
        </div>
        
        <!-- PLATS -->
        <h3 class="category-title">Nos Plats</h3>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/plat-1.jpg" alt="Filet de Boeuf" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Sauce graine avec foutou</h4>
                <p class="item-desc">Sauce graine avec viande de brousse et poisson, accompagnée de foutou bien pilé.</p>
                <p class="item-price">5 000 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/plat-2.webp" alt="Bar de Ligne" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Attiéké poulet</h4>
                <p class="item-desc">Une assiette d'attiéké avec du poulet rôti, préparée avec soin.</p>
                <p class="item-price">4 000 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/plat-3.jpg" alt="Risotto" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Tchep au poulet</h4>
                <p class="item-desc">Tchep diendé accompagné de poulet croustillant.</p>
                <p class="item-price">3 500 FCFA</p>
            </div>
        </div>
        
        <!-- DESSERTS -->
        <h3 class="category-title">Nos Desserts</h3>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/dessert-1.webp" alt="Soufflé" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Jus de bissap</h4>
                <p class="item-desc">Jus de bissap préparé avec élégance, bien frais et servi avec glaçons.</p>
                <p class="item-price">1 500 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/dessert-2.jpg" alt="Île Flottante" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Gâteau au chocolat</h4>
                <p class="item-desc">Gâteau au chocolat et à la vanille, crème maison.</p>
                <p class="item-price">1 500 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/dessert-3.webp" alt="Île Flottante" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Lait à la fraise</h4>
                <p class="item-desc">Un verre de lait à la fraise.</p>
                <p class="item-price">1 500 FCFA</p>
            </div>
        </div>

        <!-- VINS -->
        <h3 class="category-title">Notre Cave à Vins</h3>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/vin-1.jpg" alt="Château Margaux" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Cuvée prestige</h4>
                <p class="item-desc">Une sélection de vins haut de gamme, de grande qualité.</p>
                <p class="item-price">1 500 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/vin-2.webp" alt="Dom Pérignon" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Pierre Don</h4>
                <p class="item-desc">Vin Pierdon 2015</p>
                <p class="item-price">15 000 FCFA</p>
            </div>
        </div>
        
        <div class="menu-item">
            <img src="<?php echo URL_ROOT; ?>/images/menu/vin-3.jpg" alt="Meursault" class="menu-image" loading="lazy" width="400" height="250">
            <div class="menu-content">
                <h4 class="item-name">Champagne</h4>
                <p class="item-desc">Champagne blanc millésimé, 2018.</p>
                <p class="item-price">20 000 FCFA</p>
            </div>
        </div>
        
    </div>
</section>

<!-- Réservation Restaurant -->
<section class="restaurant-reservation" id="reserver-table">
    <div class="container">
        <div class="reservation-card-glass">
            <div class="reservation-header">
                <h3>Réserver votre table</h3>
                <p>Une expérience gastronomique d'exception vous attend.</p>
            </div>
            
            <form id="restaurant-booking-form" class="glass-form">
                <div class="form-grid">
                    <div class="input-group">
                        <label for="res-nom"><i class="fas fa-user"></i> Nom complet</label>
                        <input type="text" id="res-nom" name="nom" placeholder="Ex: Jean Dupont" required>
                    </div>
                    <div class="input-group">
                        <label for="res-tel"><i class="fas fa-phone"></i> Téléphone</label>
                        <input type="tel" id="res-tel" name="telephone" placeholder="Ex: +225 07 00 00 00 00" required>
                    </div>
                    <div class="input-group">
                        <label for="res-date"><i class="fas fa-calendar-day"></i> Date</label>
                        <input type="date" id="res-date" name="date_reservation" required>
                    </div>
                    <div class="input-group">
                        <label for="res-heure"><i class="fas fa-clock"></i> Heure</label>
                        <select id="res-heure" name="heure_reservation" required>
                            <option value="">Sélectionner l'heure</option>
                            <optgroup label="Déjeuner">
                                <option value="12:00">12:00</option>
                                <option value="12:30">12:30</option>
                                <option value="13:00">13:00</option>
                                <option value="13:30">13:30</option>
                            </optgroup>
                            <optgroup label="Dîner">
                                <option value="19:00">19:00</option>
                                <option value="19:30">19:30</option>
                                <option value="20:00">20:00</option>
                                <option value="20:30">20:30</option>
                                <option value="21:00" selected>21:00</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="res-couverts"><i class="fas fa-users"></i> Personnes</label>
                        <select id="res-couverts" name="couverts">
                            <?php for($i=1; $i<=10; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == 2 ? 'selected' : '' ?>><?= $i ?> <?= $i > 1 ? 'personnes' : 'personne' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="res-occasion"><i class="fas fa-glass-cheers"></i> Occasion</label>
                        <select id="res-occasion" name="occasion">
                            <option value="">Occasion standard</option>
                            <option value="Anniversaire">Anniversaire</option>
                            <option value="Dîner d'affaires">Dîner d'affaires</option>
                            <option value="Dîner romantique">Dîner romantique</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                </div>
                
                <div class="input-group full-width">
                    <label for="res-notes"><i class="fas fa-comment-dots"></i> Demandes spéciales</label>
                    <textarea id="res-notes" name="notes" placeholder="Régime alimentaire, préférence de table..."></textarea>
                </div>
                
                <button type="submit" class="btn-reserve-gold">Confirmer ma réservation</button>
            </form>
        </div>
    </div>
</section>

<style>
/* Style spécifique à la réservation restaurant (Premium Glass) */
.restaurant-reservation {
    padding: 80px 0;
    background: linear-gradient(rgba(26,26,26,0.95), rgba(26,26,26,0.95)), url('<?= URL_ROOT ?>/images/restaurant-2.jpg') center/cover no-repeat fixed;
}
.reservation-card-glass {
    background: rgba(255, 255, 255, 0.05);
    -webkit-backdrop-filter: blur(15px);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 50px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
}
.reservation-header {
    text-align: center;
    margin-bottom: 40px;
}
.reservation-header h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3rem;
    color: #D4AF37;
    margin-bottom: 10px;
}
.reservation-header p {
    color: #ccc;
    font-size: 1.1rem;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}
.input-group label {
    display: block;
    color: #D4AF37;
    margin-bottom: 10px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.input-group input, .input-group select, .input-group textarea {
    width: 100%;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #fff;
    font-family: inherit;
    transition: all 0.3s ease;
}
.input-group input:focus, .input-group select:focus, .input-group textarea:focus {
    outline: none;
    border-color: #D4AF37;
    background: rgba(255, 255, 255, 0.12);
}
.full-width {
    grid-column: span 2;
}
.btn-reserve-gold {
    width: 100%;
    margin-top: 30px;
    padding: 18px;
    background: #D4AF37;
    border: none;
    border-radius: 8px;
    color: #1a1a1a;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 2px;
}
.btn-reserve-gold:hover {
    background: #f1c40f;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(212,175,55,0.3);
}
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .full-width { grid-column: auto; }
    .reservation-card-glass { padding: 30px 20px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('restaurant-booking-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const originalText = btn.textContent;
            
            btn.disabled = true;
            btn.textContent = 'Traitement en cours...';
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch('<?= URL_ROOT ?>/api/restaurant/reserve', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Merci ! Votre réservation a été enregistrée. Nous vous contacterons prochainement.');
                    form.reset();
                } else {
                    alert('Erreur : ' + result.message);
                }
            } catch (error) {
                alert('Une erreur est survenue lors de la connexion au serveur.');
                console.error(error);
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    }
});
</script>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-col">
                <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="footer-logo">
            <p>L'excellence du luxe et du raffinement au coeur de la ville de Tiassalé.</p>
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
