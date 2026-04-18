<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation en Ligne - Hôtel FLEUR DE LYS Tiassalé</title>
    <meta name="description" content="Réservez votre séjour à l'Hôtel Fleur de Lys à Tiassalé. Chambres ventilées et climatisées disponibles, paiement sécurisé (MTN, Orange Money, Wave, Visa) et confirmation immédiate.">
    <meta name="keywords" content="réservation hotel tiassalé, chambre hotel côte d'ivoire, booking fleur de lys, paiement mobile money">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo URL_ROOT; ?>/reservation">
    <link rel="icon" type="image/png" href="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo URL_ROOT; ?>/reservation">
    <meta property="og:title" content="Réservation - Hôtel FLEUR DE LYS Tiassalé">
    <meta property="og:description" content="Réservez votre chambre maintenant. Paiement sécurisé, confirmation rapide, meilleur prix garanti.">
    <meta property="og:image" content="<?php echo URL_ROOT; ?>/images/hero-bg.jpg">

    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/reservation.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
    <style>
        /* Modernisation Réservation (Conservant l'identité visuelle) */
        .reservation-wrapper {
            display: flex;
            gap: 40px;
            margin: 40px 0;
        }
        .reservation-form-container {
            flex: 2;
            background: #fff;
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.06);
            border: 1px solid #f2f2f2;
        }
        .reservation-info {
            flex: 1;
            position: relative;
        }
        .sticky-summary {
            position: sticky;
            top: 120px;
            background: #1A1A1A;
            color: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 25px 50px rgba(212,175,55,0.15);
            border: 2px solid #D4AF37;
        }
        .sticky-summary h3 {
            color: #D4AF37;
            font-size: 1.5rem;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
        }
        .sticky-summary p { margin-bottom: 15px; color: #f0f0f0; display: flex; justify-content: space-between;}
        .summary-options { margin-top: 15px; border-left: 2px solid #D4AF37; padding-left: 15px; }
        .summary-total {
            font-size: 1.8rem;
            color: #D4AF37;
            text-align: center;
            margin-top: 25px;
            font-weight: 700;
        }
        .btn-submit {
            background: #D4AF37;
            color: #1A1A1A;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(212,175,55,0.3);
            font-weight: 700;
            text-transform: uppercase;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212,175,55,0.4);
            background: linear-gradient(135deg, #D4AF37 0%, #c3a033 100%);
        }
        .form-group input, .form-group select, .form-group textarea {
            background: #fafafa;
            border: 1px solid #eaeaea;
            color: #1A1A1A !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }
        .form-group label {
            color: #1A1A1A !important;
            font-weight: 600;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            background: #fff;
            border-color: #D4AF37;
            box-shadow: 0 0 0 4px rgba(212,175,55,0.1);
            transform: translateY(-2px);
        }
        @media (max-width: 900px) {
            .reservation-wrapper { flex-direction: column; }
            .sticky-summary { position: static; margin-top: 30px; }
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
                        <li><a href="<?php echo URL_ROOT; ?>/">Accueil</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#chambres">Chambres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#services">Services</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#offres">Offres</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/#contact">Contact</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/reservation" class="active">Réservation</a></li>
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
    </header>
    <main>

    <!-- Page Title -->
    <div class="page-title">
        <div class="container">
            <h1>Réservation en ligne</h1>
            <div class="breadcrumb">
                <a href="<?php echo URL_ROOT; ?>/">Accueil</a> / <span>Réservation</span>
            </div>
        </div>
    </div>

    <!-- Section Réservation -->
    <section class="reservation-section">
        <div class="container">
            <div class="reservation-wrapper">
                <div class="reservation-form-container">
                    <h2>Réservez votre séjour</h2>
                    
                    <form id="reservationForm" class="reservation-form" autocomplete="on">
                        <!-- Dates -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="arrivee">Date d'arrivée</label>
                                <input type="date" id="arrivee" name="arrivee" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="depart">Date de départ</label>
                                <input type="date" id="depart" name="depart" required>
                            </div>
                        </div>
                        
                        <!-- Adultes et Enfants -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="adultes">Adultes</label>
                                <select id="adultes" name="adultes" required>
                                    <option value="1">1 Adulte</option>
                                    <option value="2" selected>2 Adultes</option>
                                    <option value="3">3 Adultes</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="enfants">Enfants</label>
                                <select id="enfants" name="enfants">
                                    <option value="0">0 Enfant</option>
                                    <option value="1">1 Enfant</option>
                                    <option value="2">2 Enfants</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Type de chambre -->
                        <div class="form-group">
                            <label for="chambre">Type de chambre</label>
                            <select id="chambre" name="chambre" required>
                                <option value="">Sélectionnez une chambre</option>
                                <?php 
                                $groups = ['ventilee' => 'Chambres Ventilées', 'climatise' => 'Chambres Climatisées'];
                                foreach ($groups as $type => $label): 
                                ?>
                                <optgroup label="<?= $label ?>">
                                    <?php foreach ($rooms as $room): 
                                        if ($room['type'] === $type || (strpos(strtolower($room['nom']), 'vent') !== false && $type === 'ventilee') || (strpos(strtolower($room['nom']), 'clim') !== false && $type === 'climatise')): 
                                    ?>
                                        <option value="<?= $room['id'] ?>" data-price="<?= $room['prix'] ?>">
                                            <?= htmlspecialchars($room['nom']) ?> - <?= number_format($room['prix'], 0, ',', ' ') ?> FCFA/nuit
                                        </option>
                                    <?php endif; endforeach; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Options supplémentaires -->
                        <div class="form-group">
                            <label>Options supplémentaires</label>
                            <div class="options-grid">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="options[]" value="petit-dejeuner">
                                    <span>Petit-déjeuner inclus (15 000 FCFA/pers)</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="options[]" value="spa">
                                    <span>Accès Spa (massage) (10 000 FCFA/jour)</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="options[]" value="parking">
                                    <span>Parking sécurisé (1 000 FCFA/jour)</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="options[]" value="lit-bebe">
                                    <span>Lit bébé (gratuit)</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Informations personnelles -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom complet</label>
                                <input type="text" id="nom" name="nom" placeholder="Votre nom" autocomplete="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="votre@email.com" autocomplete="email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" placeholder="+225 07 03 24 44 64" autocomplete="tel" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="pays">Pays</label>
                                <select id="pays" name="pays">
                                    <option value="cotedivoire">Côte d'Ivoire</option>
                                    <option value="burkinafaso">Burkina Faso</option>
                                    <option value="france">France</option>
                                    <option value="mali">Mali</option>
                                    <option value="togo">Togo</option>
                                    <option value="canada">Canada</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Demandes spéciales -->
                        <div class="form-group">
                            <label for="demandes">Demandes spéciales</label>
                            <textarea id="demandes" name="demandes" rows="4" placeholder="Allergies, préférences, occasion spéciale..."></textarea>
                        </div>
                        
                        <!-- Bouton de soumission -->
                        <button type="button" class="btn-submit" onclick="openPaymentModal()">Vérifier la disponibilité</button>
                    </form>
                </div>
                <div class="reservation-info">
                    <div class="sticky-summary">
                        <h3><i class="fas fa-receipt"></i> Résumé de votre séjour</h3>
                        <div class="summary-details">
                            <p><span>Arrivée :</span> <strong id="summ-arrivee">--/--/----</strong></p>
                            <p><span>Départ :</span> <strong id="summ-depart">--/--/----</strong></p>
                            <p><span>Chambre :</span> <strong id="summ-chambre">Non sélectionnée</strong></p>
                            <p><span>Nuitées :</span> <strong id="summ-nuits">0</strong></p>
                            <div class="summary-options" id="summ-options"></div>
                            <h2 class="summary-total">Total: <br><span id="summ-total">0</span> FCFA</h2>
                        </div>
                        
                        <!-- Contact Info dans le résumé -->
                        <!-- Contact Info dans le résumé -->
                        <div class="summary-contact" style="margin-top:30px; padding-top:20px; border-top: 1px solid rgba(255,255,255,0.1); text-align:center; font-size: 0.9rem;">
                            <p style="justify-content: center; gap: 10px; color: #fff; margin-bottom: 10px;">
                                <i class="fas fa-phone" style="color: #D4AF37;"></i> <?php echo htmlspecialchars($siteSettings['hotel_phone'] ?? '+225 07 03 24 44 64'); ?>
                            </p>
                            <p style="justify-content: center; gap: 10px; color: #fff;">
                                <i class="fas fa-envelope" style="color: #D4AF37;"></i> <?php echo htmlspecialchars($siteSettings['hotel_email'] ?? 'fleurdelys1821@gmail.com'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Paiement -->
    <div id="paymentModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="paymentModalTitle">
        <div class="modal-content payment-modal">
            <button type="button" class="close close-btn" onclick="closePaymentModal()" aria-label="Fermer la fenêtre de paiement">&times;</button>
            <h2 id="paymentModalTitle">Paiement sécurisé</h2>
            
            <!-- Récapitulatif de la réservation -->
            <div class="recap-reservation" id="recapReservation">
                <!-- Rempli dynamiquement par JavaScript -->
            </div>
            
            <!-- Options de paiement -->
            <div class="payment-options">
                <h3>Choisissez votre moyen de paiement</h3>
                
                <div class="payment-methods">
                    <!-- Mobile Money -->
                    <div class="payment-method" onclick="selectPaymentMethod('moov', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'moov', this)">
                        <img src="<?php echo URL_ROOT; ?>/images/paiement/moov-logo.webp" alt="Moov Money" class="payment-logo">
                        <span>Moov Money</span>
                    </div>
                    
                    <div class="payment-method" onclick="selectPaymentMethod('mtn', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'mtn', this)">
                        <img src="<?php echo URL_ROOT; ?>/images/paiement/mtn-logo.png" alt="MTN Money" class="payment-logo">
                        <span>MTN Money</span>
                    </div>
                    
                    <div class="payment-method" onclick="selectPaymentMethod('orange', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'orange', this)">
                        <img src="<?php echo URL_ROOT; ?>/images/paiement/orange-logo.png" alt="Orange Money" class="payment-logo">
                        <span>Orange Money</span>
                    </div>
                    
                    <div class="payment-method" onclick="selectPaymentMethod('wave', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'wave', this)">
                        <img src="<?php echo URL_ROOT; ?>/images/paiement/wave-logo.png" alt="Wave" class="payment-logo">
                        <span>Wave</span>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('visa', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'visa', this)">
                        <i class="fas fa-credit-card payment-logo"></i>
                        <span>Visa</span>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('mastercard', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'mastercard', this)">
                        <i class="fas fa-credit-card payment-logo"></i>
                        <span>Mastercard</span>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('paypal', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'paypal', this)">
                        <i class="fab fa-paypal payment-logo"></i>
                        <span>PayPal</span>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('virement', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'virement', this)">
                        <i class="fas fa-university payment-logo"></i>
                        <span>Virement bancaire</span>
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('especes', this)" role="button" tabindex="0" onkeydown="handlePaymentMethodKey(event, 'especes', this)">
                        <i class="fas fa-money-bill-wave payment-logo"></i>
                        <span>Paiement en espèces</span>
                    </div>
                </div>
                
                <!-- Formulaire de paiement -->
                <div id="paymentForm" class="payment-form" style="display: none;">
                    <p id="paymentMethodHint" style="margin-bottom: 12px; color: #555;"></p>

                    <div class="form-group" id="phoneGroup">
                        <label for="phoneNumber" id="phoneLabel">Numéro de téléphone</label>
                        <input type="tel" id="phoneNumber" placeholder="+225 00 00 00 00" required>
                    </div>
                    
                    <div class="form-group" id="paymentCodeGroup">
                        <label for="paymentCode" id="paymentCodeLabel">Code de confirmation</label>
                        <input type="text" id="paymentCode" placeholder="Entrez le code reçu" required>
                    </div>
                    
                    <button type="button" class="btn-payment" onclick="processPayment()">
                        Confirmer le paiement
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($siteSettings['hotel_address'] ?? 'Axe N’douci-Tiassalé, Tiassalé'); ?></p>
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
<script>
    // Calcul Dynamique en temps réel du Résumé
    function calculateSummary() {
        const arrivee = document.getElementById('arrivee').value;
        const depart = document.getElementById('depart').value;
        const chambreSelect = document.getElementById('chambre');
        const chambreText = chambreSelect.options[chambreSelect.selectedIndex]?.text || '';
        
        let nuits = 0;
        if(arrivee && depart) {
            const d1 = new Date(arrivee);
            const d2 = new Date(depart);
            if(d2 > d1) nuits = Math.ceil((d2 - d1)/(1000*60*60*24));
        }
        
        let chambrePrix = 0;
        const selectedOption = chambreSelect.options[chambreSelect.selectedIndex];
        if(selectedOption && selectedOption.dataset.price) {
            chambrePrix = parseInt(selectedOption.dataset.price);
        }
        
        let optionTotal = 0;
        let adultCount = parseInt(document.getElementById('adultes').value) || 1;
        document.getElementById('summ-options').innerHTML = '';
        
        document.querySelectorAll('input[name="options[]"]:checked').forEach(opt => {
            let p = 0;
            let label = opt.nextElementSibling.innerText.split('(')[0].trim();
            if(opt.value === 'petit-dejeuner') p = 15000 * adultCount * nuits;
            if(opt.value === 'spa') p = 10000 * adultCount * nuits; // par exemple
            if(opt.value === 'parking') p = 1000 * nuits;
            
            optionTotal += p;
            if(p > 0) document.getElementById('summ-options').innerHTML += `<p style="display:block; margin-bottom:5px; font-size:0.9rem; color:#D4AF37;">+ ${label} : ${p.toLocaleString('fr-FR')} FCFA</p>`;
        });
        
        const grandTotal = (chambrePrix * nuits) + optionTotal;
        
        document.getElementById('summ-arrivee').innerText = arrivee ? arrivee.split('-').reverse().join('/') : '--/--/----';
        document.getElementById('summ-depart').innerText = depart ? depart.split('-').reverse().join('/') : '--/--/----';
        document.getElementById('summ-chambre').innerText = chambreText.split(' -')[0] || 'Non sélectionnée';
        document.getElementById('summ-nuits').innerText = nuits;
        document.getElementById('summ-total').innerText = grandTotal.toLocaleString('fr-FR');
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const formInputs = document.querySelectorAll('#reservationForm input, #reservationForm select');
        formInputs.forEach(el => el.addEventListener('change', calculateSummary));
        formInputs.forEach(el => el.addEventListener('input', calculateSummary));
    });
</script>
</body>
</html>
