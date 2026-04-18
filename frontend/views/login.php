<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FLEUR DE LYS</title>
    <meta name="description" content="Connectez-vous à votre espace client Hôtel Fleur de Lys pour gérer vos réservations et profiter de nos offres exclusives.">
    <meta name="keywords" content="connexion hotel, espace client fleur de lys, gerer reservation hotel">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
    <style>
        /* CSS Moderne UI/UX (Sans toucher aux couleurs de base) */
        .inscription-wrapper {
            display: flex;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.1);
            overflow: hidden;
            min-height: 600px;
            margin: 40px 0;
            border: 1px solid #f0f0f0;
        }
        .inscription-form-container {
            flex: 1;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .inscription-info {
            flex: 1.2;
            background: url('<?php echo URL_ROOT; ?>/images/hero-bg.jpg') center/cover no-repeat;
            position: relative;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            max-width: 100%;
        }
        .inscription-info::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26,26,26,0.9) 0%, rgba(212,175,55,0.4) 100%);
        }
        .benefits-card {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }
        .benefits-card h3 {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .benefits-card li {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .benefits-card li i {
            color: #D4AF37;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        .form-group input {
            background: #fafafa;
            border: 1px solid #eaeaea;
            color: #1A1A1A !important;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .form-group label {
            color: #1A1A1A !important;
            font-weight: 600;
        }
        .form-group input:focus {
            background: #fff;
            border-color: #D4AF37;
            box-shadow: 0 0 0 4px rgba(212,175,55,0.1);
            transform: translateY(-2px);
        }
        .btn-submit {
            background: #D4AF37;
            color: #1A1A1A;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(212,175,55,0.3);
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212,175,55,0.4);
            background: linear-gradient(135deg, #D4AF37 0%, #c3a033 100%);
        }
        @media (max-width: 900px) {
            .inscription-wrapper { flex-direction: column; margin: 20px 0; }
            .inscription-info { display: none; }
            .inscription-form-container { padding: 40px 20px; }
        }
        /* Hero Section Améliorée (page-title) */
        .page-title {
            background: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.55)), url('<?php echo URL_ROOT; ?>/images/hero-bg.jpg') center/cover no-repeat fixed !important;
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
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img" width="60" height="60">
                    <span class="logo-text"><?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'FLEUR DE LYS'); ?></span>
                </div>

                <div class="nav-menu" id="nav-menu">
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/">Accueil</a></li>
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
                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <main>
        <div class="page-title">
            <div class="container">
                <h1>Connexion</h1>
                <div class="breadcrumb">
                    <a href="<?php echo URL_ROOT; ?>/">Accueil</a> / <span>Connexion</span>
                </div>
            </div>
        </div>

        <section class="inscription-section">
            <div class="container">
                <div class="inscription-wrapper">
                    <div class="inscription-form-container">
                        <h2>Accédez à votre espace</h2>
                        <p class="inscription-subtitle">Retrouvez vos réservations et vos avantages membres</p>

                        <form id="connexionForm" class="inscription-form" action="<?php echo URL_ROOT; ?>/api/auth/login" method="POST" autocomplete="on">
                            <div class="form-group">
                                <label for="login_email">Email</label>
                                <input type="email" id="login_email" name="email" placeholder="votre@email.com" autocomplete="email" required>
                            </div>

                            <div class="form-group">
                                <label for="login_password">Mot de passe</label>
                                <div class="input-wrapper">
                                    <input type="password" id="login_password" name="password" placeholder="********" autocomplete="current-password" required>
                                    <button type="button" class="btn-eye" onclick="togglePassword('login_password', this.querySelector('i'))" aria-label="Afficher/Masquer le mot de passe">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="remember_me">
                                    <span>Se souvenir de moi</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-submit">Se connecter</button>

                            <div class="login-link">
                                Pas encore de compte ? <a href="<?php echo URL_ROOT; ?>/inscription">Créez-en un</a>
                            </div>
                        </form>
                    </div>

                    <div class="inscription-info">
                        <div class="benefits-card">
                            <h3>Pourquoi vous connecter ?</h3>
                            <ul class="benefits-list">
                                <li><i class="fas fa-calendar-check"></i> Suivi de vos réservations en temps réel</li>
                                <li><i class="fas fa-tag"></i> Accès aux offres privées membres</li>
                                <li><i class="fas fa-bell"></i> Notifications personnalisées</li>
                                <li><i class="fas fa-user-shield"></i> Espace sécurisé et confidentiel</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="footer-logo" width="80" height="auto">
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
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($siteSettings['hotel_address'] ?? "Axe N'douci-Tiassalé, Tiassalé"); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($siteSettings['hotel_phone'] ?? '+225 07 03 24 44 64'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($siteSettings['hotel_email'] ?? 'fleurdelys1821@gmail.com'); ?></p>
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
                <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'Hôtel FLEUR DE LYS'); ?> - Tous droits réservés</p>
                <p>Site réalisé par <span class="credit">YESHOU COMMUNICATION</span></p>
            </div>
        </div>
    </footer>

    <script src="<?php echo URL_ROOT; ?>/js/script.js" defer></script>
</body>
</html>
