'use strict';

// Variables globales
let currentPaymentMethod = '';
let selectedRoom = null;
let reservationData = {};
const paymentMethodConfig = {
    moov: { requiresPhone: true, requiresCode: false, hint: 'Paiement Mobile Money instantané (Moov).' },
    mtn: { requiresPhone: true, requiresCode: false, hint: 'Paiement Mobile Money instantané (MTN).' },
    orange: { requiresPhone: true, requiresCode: false, hint: 'Paiement Mobile Money instantané (Orange).' },
    wave: { requiresPhone: true, requiresCode: false, hint: 'Paiement Mobile Money instantané (Wave).' },
    visa: { requiresPhone: false, requiresCode: false, hint: 'Paiement carte Visa.' },
    mastercard: { requiresPhone: false, requiresCode: false, hint: 'Paiement carte Mastercard.' },
    paypal: { requiresPhone: false, requiresCode: false, hint: 'Paiement via compte PayPal.' },
    virement: { requiresPhone: false, requiresCode: false, hint: 'Le virement est enregistré puis validé manuellement.' },
    especes: { requiresPhone: false, requiresCode: false, hint: 'Paiement en espèces à l\'arrivée.' }
};

// Menu toggle pour mobile
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            // Accessibilité : aria-expanded
            const isOpen = navMenu.classList.contains('active');
            menuToggle.setAttribute('aria-expanded', isOpen);
        });
    }

    // Fermer le menu mobile en cliquant en dehors
    document.addEventListener('click', function(e) {
        if (navMenu && !navMenu.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
            navMenu.classList.remove('active');
            if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Effet scrolled sur la navbar (Optimisé avec requestAnimationFrame pour éviter les ralentissements GPU)
    const navbar = document.querySelector('.navbar');
    let isNavbarTicking = false;
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (!isNavbarTicking) {
                window.requestAnimationFrame(function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                    isNavbarTicking = false;
                });
                isNavbarTicking = true;
            }
        }, { passive: true });
    }

    // Initialiser les carrousels
    initRoomCarousel();
    initMenuCarousel();

    // Gestionnaire du formulaire d'inscription
    const inscriptionForm = document.getElementById('inscriptionForm');
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleInscription(e);
        });
        // Feedback temps-réel confirmation mots de passe
        const confirmInput = document.getElementById('confirm_password');
        const passwordInput = document.getElementById('password');
        if (confirmInput && passwordInput) {
            confirmInput.addEventListener('input', function() {
                const matchMsg = document.getElementById('password-match-msg');
                if (!matchMsg) return;
                if (confirmInput.value === '') {
                    matchMsg.textContent = '';
                } else if (confirmInput.value === passwordInput.value) {
                    matchMsg.textContent = '✓ Les mots de passe correspondent';
                    matchMsg.style.color = 'green';
                } else {
                    matchMsg.textContent = '✗ Les mots de passe ne correspondent pas';
                    matchMsg.style.color = '#dc3545';
                }
            });
        }
    }

    const connexionForm = document.getElementById('connexionForm');
    if (connexionForm) {
        connexionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleConnexion(e);
        });
    }

    // Gestionnaire du formulaire de réservation
    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        initReservationDateConstraints();
        const submitBtn = reservationForm.querySelector('button[type="button"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                validateAndProceed();
            });
        }
    }

    // Injection de la barre de progression de scroll (Optimisée via requestAnimationFrame)
    const scrollProgress = document.createElement('div');
    scrollProgress.className = 'scroll-progress';
    document.body.prepend(scrollProgress);

    let isProgressTicking = false;
    window.addEventListener('scroll', () => {
        if (!isProgressTicking) {
            window.requestAnimationFrame(() => {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                scrollProgress.style.width = scrolled + "%";
                isProgressTicking = false;
            });
            isProgressTicking = true;
        }
    }, { passive: true });

    // Initialiser la navigation du restaurant si on est sur la page restaurant
    if (document.querySelector('.menu-navigation')) {
        initRestaurantMenu();
    }

    // Scroll Reveal Intersection Observer
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Facultatif : arrêter d'observer une fois révélé
                    // revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    }

    // Initialiser la vérification de disponibilité
    initAvailabilityCheck();

    // Initialiser le formulaire d'avis
    initReviewForm();
});

/**
 * Gère la soumission du formulaire d'avis client.
 */
function initReviewForm() {
    const form = document.getElementById('submit-review-form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = {
            nom: formData.get('nom'),
            note: formData.get('note'),
            commentaire: formData.get('commentaire')
        };

        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Envoi...';

        try {
            const response = await fetch(`${window.URL_ROOT}/api/reviews`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                form.reset();
                // Remettre la note par défaut à 3 étoiles (visuel)
                const star3 = document.getElementById('star3');
                if (star3) star3.checked = true;
            } else {
                showNotification(result.message || "Une erreur est survenue.", 'error');
            }
        } catch (error) {
            console.error('Erreur avis:', error);
            showNotification('Erreur de connexion au serveur.', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Envoyer mon avis';
        }
    });
}

/**
 * Gère la soumission du formulaire de disponibilité.
 * Interroge l'API et filtre visuellement les chambres du carrousel.
 */
function initAvailabilityCheck() {
    const form = document.getElementById('check-availability-form');
    if (!form) return;

    // Prédéfinir les dates (Aujourd'hui et Demain)
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0];
    const arriveeInput = form.querySelector('input[name="arrivee"]');
    const departInput = form.querySelector('input[name="depart"]');
    
    if (arriveeInput) arriveeInput.value = today;
    if (departInput) {
        departInput.value = tomorrow;
        departInput.min = today;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const arrivee = arriveeInput.value;
        const depart = departInput.value;

        if (!arrivee || !depart) return;

        showNotification('Vérification des chambres disponibles...', 'info');

        try {
            const response = await fetch(`${window.URL_ROOT}/api/rooms/availability?arrivee=${arrivee}&depart=${depart}`);
            const result = await response.json();

            if (result.success) {
                const availableRooms = result.data; // Tableau d'objets chambres
                const cards = document.querySelectorAll('.carousel-card');
                let foundCount = 0;

                cards.forEach(card => {
                    const roomTitle = card.querySelector('h3').textContent.trim();
                    // On vérifie si le nom de la chambre est dans la liste renvoyée par l'API
                    const isAvailable = availableRooms.some(r => r.nom.trim() === roomTitle);
                    
                    if (isAvailable) {
                        card.style.display = 'block';
                        foundCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (foundCount === 0) {
                    showNotification('Désolé, aucune chambre n\'est disponible pour ces dates.', 'error');
                } else {
                    showNotification(`${foundCount} chambre(s) disponible(s) !`, 'success');
                    // Réinitialiser le carrousel pour éviter les trous
                    if (typeof initRoomCarousel === 'function') initRoomCarousel();
                    
                    // Scroll vers les chambres
                    document.getElementById('chambres').scrollIntoView({ behavior: 'smooth' });
                }
            }
        } catch (error) {
            console.error('Erreur disponibilité:', error);
            showNotification('Erreur lors de la vérification.', 'error');
        }
    });
}

function initReservationDateConstraints() {
    const arriveeInput = document.getElementById('arrivee');
    const departInput = document.getElementById('depart');
    if (!arriveeInput || !departInput) return;

    const today = new Date().toISOString().split('T')[0];
    arriveeInput.min = today;
    departInput.min = today;

    arriveeInput.addEventListener('change', function() {
        if (arriveeInput.value) {
            departInput.min = arriveeInput.value;
            if (departInput.value && departInput.value <= arriveeInput.value) {
                departInput.value = '';
            }
        }
    });
}

function handlePaymentMethodKey(event, method, element) {
    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        selectPaymentMethod(method, element);
    }
}

// Carrousel des chambres
function initRoomCarousel() {
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (!track || !prevBtn || !nextBtn) return;
    
    let currentIndex = 0;
    const cards = document.querySelectorAll('.carousel-card');
    const cardWidth = cards[0]?.offsetWidth || 300;
    const cardMargin = 20;
    const visibleCards = getVisibleCardsCount();
    
    function getVisibleCardsCount() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }
    
    function updateCarousel() {
        const maxIndex = Math.max(0, cards.length - visibleCards);
        currentIndex = Math.min(currentIndex, maxIndex);
        const translateX = -(currentIndex * (cardWidth + cardMargin));
        track.style.transform = `translateX(${translateX}px)`;
    }
    
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        const maxIndex = cards.length - visibleCards;
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        }
    });
    
    window.addEventListener('resize', () => {
        const newVisibleCards = getVisibleCardsCount();
        if (newVisibleCards !== visibleCards) {
            currentIndex = 0;
            updateCarousel();
        }
    });
}

// Carrousel du menu
function initMenuCarousel() {
    const track = document.getElementById('menuTrack');
    const prevBtn = document.getElementById('menuPrevBtn');
    const nextBtn = document.getElementById('menuNextBtn');
    
    if (!track || !prevBtn || !nextBtn) return;
    
    let currentIndex = 0;
    const cards = document.querySelectorAll('.menu-card');
    const cardWidth = cards[0]?.offsetWidth || 300;
    const cardMargin = 20;
    const visibleCards = getVisibleCardsCount();
    
    function getVisibleCardsCount() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }
    
    function updateCarousel() {
        const maxIndex = Math.max(0, cards.length - visibleCards);
        currentIndex = Math.min(currentIndex, maxIndex);
        const translateX = -(currentIndex * (cardWidth + cardMargin));
        track.style.transform = `translateX(${translateX}px)`;
    }
    
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        const maxIndex = cards.length - visibleCards;
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        }
    });
    
    window.addEventListener('resize', () => {
        const newVisibleCards = getVisibleCardsCount();
        if (newVisibleCards !== visibleCards) {
            currentIndex = 0;
            updateCarousel();
        }
    });
}

// Fonction pour changer d'onglet dans le menu
function showMenu(category) {
    const track = document.getElementById('menuTrack');
    if (!track) return;
    
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    event.target.classList.add('active');
    
    const cards = document.querySelectorAll('.menu-card');
    let firstIndex = -1;
    
    cards.forEach((card, index) => {
        const itemText = card.querySelector('h3')?.textContent || '';
        let show = false;
        
        switch(category) {
            case 'entrees':
                show = ['Foie Gras', 'Homard', 'Saint-Jacques'].some(item => itemText.includes(item));
                break;
            case 'plats':
                show = ['Filet', 'Bar', 'Risotto'].some(item => itemText.includes(item));
                break;
            case 'desserts':
                show = ['Soufflé', 'Île', 'Macarons'].some(item => itemText.includes(item));
                break;
            case 'vins':
                show = ['Château', 'Dom', 'Meursault'].some(item => itemText.includes(item));
                break;
        }
        
        if (show && firstIndex === -1) {
            firstIndex = index;
        }
    });
    
    if (firstIndex !== -1) {
        const visibleCards = getVisibleCardsCount();
        const maxIndex = Math.max(0, firstIndex);
        const cardWidth = cards[0]?.offsetWidth || 300;
        const cardMargin = 20;
        const translateX = -(maxIndex * (cardWidth + cardMargin));
        track.style.transform = `translateX(${translateX}px)`;
    }
}

// Fonction pour initialiser le menu du restaurant (nouvelle version)
function initRestaurantMenu() {
    const navButtons = document.querySelectorAll('.menu-nav-btn');
    const menuCategories = document.querySelectorAll('.menu-category');
    
    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            navButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const categoryToShow = this.getAttribute('data-category');
            
            menuCategories.forEach(category => {
                category.classList.remove('active');
            });
            
            const activeCategory = document.getElementById(categoryToShow);
            if (activeCategory) {
                activeCategory.classList.add('active');
            }
        });
    });
}

// Fonction pour afficher une catégorie spécifique (version simplifiée)
function showMenuCategory(category) {
    const categories = document.querySelectorAll('.menu-category-block');
    categories.forEach(cat => {
        cat.classList.remove('active');
    });
    
    const activeCategory = document.getElementById(category);
    if (activeCategory) {
        activeCategory.classList.add('active');
    }
    
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.textContent.toLowerCase().includes(category)) {
            tab.classList.add('active');
        }
    });
}

// Fonctions pour le modal de paiement
function openPaymentModal() {
    const arrivee = document.getElementById('arrivee')?.value;
    const depart = document.getElementById('depart')?.value;
    const chambre = document.getElementById('chambre')?.value;
    const adultes = document.getElementById('adultes')?.value;
    const enfants = document.getElementById('enfants')?.value;
    
    if (!arrivee || !depart || !chambre) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }
    
    if (new Date(depart) <= new Date(arrivee)) {
        showNotification('La date de départ doit être après la date d\'arrivée', 'error');
        return;
    }
    
    const nights = Math.ceil((new Date(depart) - new Date(arrivee)) / (1000 * 60 * 60 * 24));
    const chambrePrice = getChambrePrice(chambre);
    const total = nights * chambrePrice;
    
    reservationData = {
        arrivee,
        depart,
        chambre,
        adultes,
        enfants,
        nights,
        chambrePrice,
        total
    };
    
    const recap = document.getElementById('recapReservation');
    if (recap) {
        recap.innerHTML = `
            <h3>Récapitulatif</h3>
            <p><strong>Arrivée:</strong> ${formatDate(arrivee)}</p>
            <p><strong>Départ:</strong> ${formatDate(depart)}</p>
            <p><strong>Nombre de nuits:</strong> ${nights}</p>
            <p><strong>Chambre:</strong> ${getChambreName(chambre)}</p>
            <p><strong>Personnes:</strong> ${adultes} adulte(s) ${enfants > 0 ? `, ${enfants} enfant(s)` : ''}</p>
            <p><strong>Prix total:</strong> ${total.toLocaleString('fr-FR')} FCFA</p>
        `;
    }
    
    document.getElementById('paymentModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    resetPaymentForm();
}

function selectPaymentMethod(method, element = null) {
    currentPaymentMethod = method;
    
    const methods = document.querySelectorAll('.payment-method');
    methods.forEach(m => m.classList.remove('selected'));
    
    if (element) {
        element.classList.add('selected');
    }
    
    const phoneInput = document.getElementById('phoneNumber');
    const codeInput = document.getElementById('paymentCode');
    const phoneGroup = document.getElementById('phoneGroup');
    const codeGroup = document.getElementById('paymentCodeGroup');
    const methodHint = document.getElementById('paymentMethodHint');
    const config = paymentMethodConfig[method] || { requiresPhone: false, requiresCode: false, hint: '' };

    if (phoneInput) {
        phoneInput.required = config.requiresPhone;
        phoneInput.placeholder = config.requiresPhone ? '+225 00 00 00 00' : 'Optionnel selon la méthode';
    }

    if (codeInput) {
        codeInput.required = config.requiresCode;
        codeInput.placeholder = config.requiresCode ? 'Code requis' : 'Référence/Code (optionnel)';
    }

    if (phoneGroup) {
        phoneGroup.style.display = config.requiresPhone ? 'block' : 'none';
    }
    if (codeGroup) {
        codeGroup.style.display = config.requiresCode ? 'block' : 'none';
    }
    if (methodHint) {
        methodHint.textContent = config.hint;
    }

    document.getElementById('paymentForm').style.display = 'block';
}

/**
 * Déclenche l'envoi de la réservation finale et le traitement du paiement.
 * Coordonne les appels API /api/reservation puis /api/payment de manière asynchrone.
 * 
 * @returns {Promise<void>}
 */
async function processPayment() {
    const phoneNumber = document.getElementById('phoneNumber')?.value;
    const paymentCode = document.getElementById('paymentCode')?.value;
    
    if (!currentPaymentMethod) {
        showNotification('Veuillez sélectionner un moyen de paiement', 'error');
        return;
    }

    const config = paymentMethodConfig[currentPaymentMethod] || {};
    if (config.requiresPhone && !phoneNumber) {
        showNotification('Le numéro de téléphone est requis pour Mobile Money', 'error');
        return;
    }
    
    showNotification('Paiement en cours...', 'info');

    const fullName = (document.getElementById('nom')?.value || '').trim();
    const nameParts = fullName.split(/\s+/).filter(Boolean);
    const prenom = nameParts.shift() || '';
    const nom = nameParts.join(' ') || prenom;
    const email = (document.getElementById('email')?.value || '').trim();
    const telephone = (document.getElementById('telephone')?.value || '').trim();
    const chambreSelect = document.getElementById('chambre');
    const chambreValue = chambreSelect?.value || '';
    const options = Array.from(document.querySelectorAll('input[name="options[]"]:checked')).map(input => input.value);

    try {
        // 1) Créer la réservation (client + reservation + paiement en_attente)
        const reservationPayload = {
            prenom,
            nom,
            email,
            telephone,
            chambre: chambreValue,
            arrivee: reservationData.arrivee,
            depart: reservationData.depart,
            adultes: reservationData.adultes,
            enfants: reservationData.enfants,
            options,
            total: reservationData.total,
            payment_method: currentPaymentMethod,
            phone: phoneNumber || '',
            payment_code: paymentCode
        };

        const reservationResponse = await fetch(`${window.URL_ROOT}/api/reservation`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(reservationPayload)
        });
        const reservationRaw = await reservationResponse.text();
        let reservationResult;
        try {
            reservationResult = JSON.parse(reservationRaw);
        } catch (_) {
            throw new Error('Réponse serveur invalide (réservation)');
        }

        if (!reservationResult.success) {
            throw new Error(reservationResult.message || 'Erreur lors de la création de la réservation');
        }

        // 2) Finaliser le paiement
        const paymentPayload = {
            action: 'process',
            reservation_id: reservationResult.reservation_id,
            method: currentPaymentMethod,
            phone: phoneNumber || '',
            amount: reservationData.total,
            code: paymentCode
        };

        const paymentResponse = await fetch(`${window.URL_ROOT}/api/payment`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(paymentPayload)
        });
        const paymentRaw = await paymentResponse.text();
        let paymentResult;
        try {
            paymentResult = JSON.parse(paymentRaw);
        } catch (_) {
            throw new Error('Réponse serveur invalide (paiement)');
        }

        if (!paymentResult.success) {
            throw new Error(paymentResult.message || 'Paiement refusé');
        }

        // --- GESTION DE LA REDIRECTION (Wave, etc.) ---
        if (paymentResult.redirect_url) {
            showNotification('Redirection vers la plateforme de paiement...', 'info');
            setTimeout(() => {
                window.location.href = paymentResult.redirect_url;
            }, 1000);
            return;
        }

        const paymentStatus = paymentResult.data?.status || 'reussi';
        if (paymentStatus === 'reussi') {
            showNotification(`Réservation confirmée (${reservationResult.reservation_number}) ! Un email de confirmation vous a été envoyé.`, 'success');
            // Télécharger la facture automatiquement
            setTimeout(() => {
                window.location.href = `${window.URL_ROOT}/api/reservation/invoice?id=${reservationResult.reservation_id}`;
            }, 1000);
        } else if (paymentStatus === 'en_attente') {
            showNotification(`Réservation enregistrée (${reservationResult.reservation_number}) - Paiement en attente de validation`, 'info');
        } else {
            showNotification(paymentResult.message || 'Paiement en attente', 'info');
        }

        closePaymentModal();
    } catch (error) {
        showNotification(error.message || 'Erreur de paiement', 'error');
    }
}

function resetPaymentForm() {
    document.getElementById('paymentForm').style.display = 'none';
    document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
    currentPaymentMethod = '';
    
    if (document.getElementById('phoneNumber')) {
        document.getElementById('phoneNumber').value = '';
    }
    if (document.getElementById('paymentCode')) {
        document.getElementById('paymentCode').value = '';
    }
    if (document.getElementById('paymentMethodHint')) {
        document.getElementById('paymentMethodHint').textContent = '';
    }
    if (document.getElementById('phoneGroup')) {
        document.getElementById('phoneGroup').style.display = 'block';
    }
    if (document.getElementById('paymentCodeGroup')) {
        document.getElementById('paymentCodeGroup').style.display = 'block';
    }
}

// Fonctions utilitaires
function getChambrePrice(chambreId) {
    const prices = {
        'ventilee-': 20000,
        'ventilee-Prestige': 22000,
        'ventilee-Supérieure': 25000,
        'ventilee-Familiale': 30000,
        'ventilee-Standard': 35000,
        'climatise-': 40000,
        'climatise-executive': 42000,
        'climatise-prestige': 45000,
        'climatise-Standard': 50000,
        'climatise-royale': 55000
    };
    
    return prices[chambreId] || 20000;
}

function getChambreName(chambreId) {
    const names = {
        'ventilee-': 'Chambre Ventilée',
        'ventilee-Prestige': 'Chambre Ventilée Prestige',
        'ventilee-Supérieure': 'Chambre Ventilée Supérieure',
        'ventilee-Familiale': 'Chambre Ventilée Familiale',
        'ventilee-Standard': 'Chambre Ventilée standard',
        'climatise-': 'Chambre Climatisée',
        'climatise-executive': 'Chambre Climatisée Executive',
        'climatise-prestige': 'Chambre Climatisée Prestige',
        'climatise-Standard': 'Chambre Climatisée Standard',
        'climatise-royale': 'Chambre Climatisée Royale'
    };
    
    return names[chambreId] || 'Chambre';
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

function validateAndProceed() {
    openPaymentModal();
}

/**
 * Intercepte et traite la soumission du formulaire d'inscription.
 * Procède à une vérification complète côté client avant d'envoyer l'appel API.
 * 
 * @param {Event} e L'événement de soumission du formulaire
 * @returns {Promise<void>}
 */
async function handleInscription(e) {
    const nom              = document.getElementById('nom')?.value?.trim();
    const prenom           = document.getElementById('prenom')?.value?.trim();
    const email            = document.getElementById('email')?.value?.trim();
    const telephone        = document.getElementById('telephone')?.value?.trim();
    const date_naissance   = document.getElementById('date_naissance')?.value || null;
    const password         = document.getElementById('password')?.value;
    const confirmPassword  = document.getElementById('confirm_password')?.value;
    const newsletter       = document.getElementById('newsletter') ? document.getElementById('newsletter').checked : false; // Si élément existe

    // Validation côté client (doublon de la validation serveur pour meilleure UX)
    if (!prenom || !nom || !email || !password) {
        showNotification('Veuillez remplir tous les champs obligatoires.', 'error');
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showNotification('Adresse e-mail invalide.', 'error');
        return;
    }
    if (password.length < 8) {
        showNotification('Le mot de passe doit contenir au moins 8 caractères.', 'error');
        return;
    }
    if (!confirmPassword || password !== confirmPassword) {
        showNotification('Les mots de passe ne correspondent pas ou la confirmation est vide.', 'error');
        return;
    }

    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) { btn.disabled = true; btn.textContent = 'Inscription en cours...'; }

    try {
        const response = await fetch(`${window.URL_ROOT}/api/auth/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ prenom, nom, email, telephone, password, confirm_password: confirmPassword, date_naissance, newsletter })
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message || 'Inscription réussie ! Bienvenue chez Fleur de Lys', 'success');
            e.target.reset();
            setTimeout(() => {
                window.location.href = window.URL_ROOT + '/';
            }, 2000);
        } else {
            showNotification(result.message || "Erreur lors de l'inscription", 'error');
        }
    } catch (error) {
        showNotification("Erreur de connexion au serveur", 'error');
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = "S'inscrire"; }
    }
}

/**
 * Intercepte et traite la soumission du formulaire de connexion.
 * 
 * @param {Event} e L'événement de soumission du formulaire
 * @returns {Promise<void>}
 */
async function handleConnexion(e) {
    const email    = document.getElementById('login_email')?.value?.trim();
    const password = document.getElementById('login_password')?.value;

    if (!email || !password) {
        showNotification('Veuillez renseigner votre email et votre mot de passe', 'error');
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showNotification('Adresse e-mail invalide', 'error');
        return;
    }

    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) { btn.disabled = true; btn.textContent = 'Connexion en cours...'; }

    try {
        const response = await fetch(`${window.URL_ROOT}/api/auth/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message || 'Connexion réussie !', 'success');
            setTimeout(() => {
                // Redirection basée sur le rôle
                if (result.role === 'admin') {
                    window.location.href = window.URL_ROOT + '/admin';
                } else {
                    window.location.href = window.URL_ROOT + '/';
                }
            }, 1200);
        } else {
            showNotification(result.message || "Identifiants incorrects", 'error');
        }
    } catch (error) {
        showNotification("Erreur de connexion au serveur", 'error');
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = 'Se connecter'; }
    }
}

// Afficher / cacher le mot de passe
function togglePassword(inputId, iconEl) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        if (iconEl) iconEl.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        if (iconEl) iconEl.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

/**
 * Créé et insère dynamiquement une notification visuelle temporaire à l'écran (toast).
 * 
 * @param {string} message Le texte à afficher dans la notification.
 * @param {string} [type='success'] Le type d'alerte : 'success', 'error', 'info', etc.
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#D4AF37' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: ${type === 'success' ? '#1A1A1A' : '#FFFFFF'};
        padding: 1rem 2rem;
        border-radius: 4px;
        z-index: 3000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Fonction pour ouvrir la réservation restaurant
function openRestaurantReservation() {
    showNotification('Service de réservation restaurant bientôt disponible', 'info');
}

// Fermer le modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target == modal) {
        closePaymentModal();
    }
}

// Formulaire de réservation restaurant
const restaurantReservationForm = document.getElementById('restaurant-reservation-form');
if (restaurantReservationForm) {
    restaurantReservationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nom = document.getElementById('nom').value;
        const email = document.getElementById('email').value;
        const telephone = document.getElementById('telephone').value;
        const date = document.getElementById('date').value;
        const heure = document.getElementById('heure').value;
        
        if (!nom || !email || !telephone || !date || !heure) {
            showNotification('Veuillez remplir tous les champs obligatoires', 'error');
            return;
        }
        
        showNotification(`Réservation confirmée pour le ${formatDate(date)} à ${heure}`, 'success');
        
        this.reset();
    });
}