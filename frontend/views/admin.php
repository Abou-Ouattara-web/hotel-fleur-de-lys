<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Fleur de Lys</title>
    <!-- Utilisation du style global -->
    <link rel="stylesheet" href="<?= URL_ROOT ?>/frontend/public/css/base.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>/css/pages/admin.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>/vendor/fontawesome/css/all.min.css">
    
    <!--/**
     * OPTIMISATION & STRATÉGIE D'ASSETS :
     * Les bibliothèques tiers (Chart.js et FullCalendar) sont hébergées LOCALEMENT.
     * 1. Confidentialité : Aucune donnée de navigation n'est partagée avec des CDNs tiers.
     * 2. Fiabilité : Le dashboard fonctionne même sans accès Internet externe ou si un CDN est bloqué.
     * 3. Performance : Réduction du temps de résolution DNS.
     **/-->
    <script src="<?= URL_ROOT ?>/vendor/chartjs/chart.min.js"></script>
    <script src="<?= URL_ROOT ?>/vendor/fullcalendar/index.global.min.js"></script>
</head>
<body class="admin-body">
    
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <!-- fetchpriority="high" pour charger le logo immédiatement (LCP) -->
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img" width="60" height="60" fetchpriority="high">
                    <span class="logo-text"><?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'FLEUR DE LYS'); ?></span>
                </div>
                <div class="header-logo-container">
                    <div>
                        <h2>FLEUR DE LYS</h2>
                        <small>Administration</small>
                    </div>
                </div>
                <button class="admin-mobile-toggle" id="adminSidebarToggle" aria-label="Menu">
                    <i class="fas fa-times close-icon"></i>
                    <i class="fas fa-bars open-icon"></i>
                </button>
            </div>
            
            <nav class="sidebar-menu">
                <a href="javascript:void(0)" class="menu-item active" onclick="showTab('stats', this)">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('reservations', this)">
                    <i class="fas fa-calendar-check"></i> Réservations
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('planning', this)">
                    <i class="fas fa-calendar-alt"></i> Planning
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('chambres', this)">
                    <i class="fas fa-bed"></i> Chambres
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('avis', this)">
                    <i class="fas fa-star"></i> Avis Clients
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('offres', this)">
                    <i class="fas fa-tags"></i> Offres Spéciales
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('restaurant', this)">
                    <i class="fas fa-utensils"></i> Restaurant
                </a>
                <a href="javascript:void(0)" class="menu-item" onclick="showTab('settings', this)">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= URL_ROOT ?>/api/auth/logout" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="content-header">
                <div>
                    <h1>Tableau de Bord</h1>
                    <p class="text-muted">Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? 'Admin') ?></strong></p>
                </div>
                <div class="header-actions">
                    <button class="btn-sidebar-trigger" id="mobileMenuOpen">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="<?= URL_ROOT ?>/" class="btn-logout" style="color: var(--admin-primary); background: rgba(212,175,55,0.1); padding: 10px 20px; border-radius: 8px;">
                        <i class="fas fa-external-link-alt"></i> Voir le site
                    </a>
                </div>
            </header>

            <!-- Dashboard Stats (KPIs) : Résumé des indicateurs clés -->
            <div id="stats" class="tab-content active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                        <div class="stat-info">
                            <h4>Réservations</h4>
                            <div class="value"><?= count($reservations) ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="stat-info">
                            <h4>Revenus</h4>
                            <div class="value"><?php 
                                // Calcul du revenu total basé sur les réservations existantes
                                $totalRev = array_sum(array_column($reservations, 'prix_total'));
                                echo number_format($totalRev, 0, ',', ' '); 
                            ?> <small>FCFA</small></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-door-open"></i></div>
                        <div class="stat-info">
                            <h4>Chambres</h4>
                            <div class="value"><?= count($rooms) ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                        <div class="stat-info">
                            <h4>Note Moyenne</h4>
                            <div class="value">4.8 <small>/5</small></div>
                        </div>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="card" style="grid-column: span 2;">
                        <div class="card-header"><h3>Revenus mensuels</h3></div>
                        <canvas id="revChart" height="100"></canvas>
                    </div>
                    <div class="card">
                        <div class="card-header"><h3>Volume de réservations</h3></div>
                        <canvas id="volChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Section Réservations -->
            <div id="reservations" class="tab-content">
                <div class="card">
                    <div class="card-header"><h3>Liste des Réservations</h3></div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th># Numéro</th>
                                    <th>Client</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Chambre</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td><strong><?= $res['numero_reservation'] ?></strong></td>
                                    <td><?= htmlspecialchars($res['prenom'] . ' ' . $res['nom']) ?></td>
                                    <td><?= date('d/m', strtotime($res['date_arrivee'])) ?></td>
                                    <td><?= date('d/m', strtotime($res['date_depart'])) ?></td>
                                    <td><?= htmlspecialchars($res['chambre_nom']) ?></td>
                                    <td>
                                        <select onchange="updateReservationStatus(<?= $res['id'] ?>, this.value)" class="badge <?= $res['statut'] === 'confirmee' ? 'bg-success' : ($res['statut'] === 'annulee' ? 'bg-danger' : 'bg-pending') ?>">
                                            <option value="en_attente" <?= $res['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="confirmee" <?= $res['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                            <option value="annulee" <?= $res['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-delete" onclick="deleteReservation(<?= $res['id'] ?>)"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Planning -->
            <div id="planning" class="tab-content">
                <div class="card">
                    <div class="card-header"><h3>Planning Visuel</h3></div>
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Gestion Chambres -->
            <div id="chambres" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3>Liste des Chambres</h3>
                        <button class="btn-logout" style="background: var(--admin-primary); color: white;" onclick="openAddRoomModal()">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><img src="<?= URL_ROOT ?>/images/<?= $room['image'] ?: 'room-placeholder.jpg' ?>" style="width: 50px; border-radius: 4px;"></td>
                                    <td><?= htmlspecialchars($room['nom']) ?></td>
                                    <td><?= number_format($room['prix'], 0, ',', ' ') ?> F</td>
                                    <td><span class="badge <?= $room['disponible'] ? 'bg-success' : 'bg-danger' ?>"><?= $room['disponible'] ? 'Libre' : 'Occupe' ?></span></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($room)) ?>)"><i class="fas fa-edit"></i></button>
                                        <button class="btn-action btn-delete" onclick="deleteRoom(<?= $room['id'] ?>)"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modération Avis -->
            <div id="avis" class="tab-content">
                <div class="card">
                    <div class="card-header"><h3>Avis Clients</h3></div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reviews as $rev): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($rev['date_avis'])) ?></td>
                                    <td><?= htmlspecialchars($rev['nom']) ?></td>
                                    <td class="star-rating"><?= str_repeat('★', $rev['note']) ?></td>
                                    <td>
                                        <?php if(!$rev['approuve']): ?>
                                            <button class="btn-action btn-edit" onclick="moderateReview(<?= $rev['id'] ?>, 'approve')"><i class="fas fa-check"></i></button>
                                        <?php endif; ?>
                                        <button class="btn-action btn-delete" onclick="moderateReview(<?= $rev['id'] ?>, 'delete')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gestion Offres Spéciales -->
            <div id="offres" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3>Gestion des Offres Spéciales</h3>
                        <button class="btn-logout" style="background: var(--admin-primary); color: white;" onclick="openAddOfferModal()">
                            <i class="fas fa-plus"></i> Ajouter une Offre
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Titre</th>
                                    <th>Tag</th>
                                    <th>Prix/Texte</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offers as $off): ?>
                                <tr>
                                    <td><img src="<?= URL_ROOT ?>/images/<?= $off['image'] ?: 'offer-placeholder.png' ?>" style="width: 50px; height: 40px; object-fit: cover; border-radius: 4px;"></td>
                                    <td><?= htmlspecialchars($off['titre']) ?></td>
                                    <td><span class="badge bg-pending"><?= htmlspecialchars($off['tag'] ?: 'Standard') ?></span></td>
                                    <td><?= htmlspecialchars($off['prix_texte']) ?></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="openEditOfferModal(<?= htmlspecialchars(json_encode($off)) ?>)"><i class="fas fa-edit"></i></button>
                                        <button class="btn-action btn-delete" onclick="deleteOffer(<?= $off['id'] ?>)"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gestion Réservations Restaurant -->
            <div id="restaurant" class="tab-content">
                <div class="card">
                    <div class="card-header"><h3>Réservations de Tables</h3></div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Date & Heure</th>
                                    <th>Couverts</th>
                                    <th>Occasion / Notes</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($restaurantReservations as $rres): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($rres['nom']) ?></strong><br>
                                        <small><?= htmlspecialchars($rres['telephone']) ?></small>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($rres['date_reservation'])) ?><br>
                                        <small><?= date('H:i', strtotime($rres['heure_reservation'])) ?></small>
                                    </td>
                                    <td><i class="fas fa-users"></i> <?= $rres['couverts'] ?></td>
                                    <td>
                                        <?php if($rres['occasion']): ?>
                                            <span class="badge bg-pending"><?= htmlspecialchars($rres['occasion']) ?></span><br>
                                        <?php endif; ?>
                                        <small><?= htmlspecialchars($rres['notes']) ?></small>
                                    </td>
                                    <td>
                                        <select onchange="updateRestaurantStatus(<?= $rres['id'] ?>, this.value)" class="badge <?= $rres['statut'] === 'confirmee' ? 'bg-success' : ($rres['statut'] === 'annulee' ? 'bg-danger' : 'bg-pending') ?>">
                                            <option value="en_attente" <?= $rres['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="confirmee" <?= $rres['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                            <option value="annulee" <?= $rres['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-delete" onclick="deleteRestaurantReservation(<?= $rres['id'] ?>)"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paramètres du Site -->
            <div id="settings" class="tab-content">
                <div class="card">
                    <div class="card-header"><h3>Paramètres de l'Hôtel</h3></div>
                    <form id="settingsForm" onsubmit="saveSettings(event)">
                        <div class="form-group">
                            <label>Nom de l'établissement</label>
                            <input type="text" name="hotel_name" value="<?= htmlspecialchars($siteSettings['hotel_name'] ?? 'HOTEL FLEUR DE LYS') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Téléphone Contact</label>
                            <input type="text" name="hotel_phone" value="<?= htmlspecialchars($siteSettings['hotel_phone'] ?? '+225 07 03 24 44 64') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email Contact</label>
                            <input type="email" name="hotel_email" value="<?= htmlspecialchars($siteSettings['hotel_email'] ?? 'fleurdelys1821@gmail.com') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Adresse Physique</label>
                            <input type="text" name="hotel_address" value="<?= htmlspecialchars($siteSettings['hotel_address'] ?? 'Axe N’douci-Tiassalé, Tiassalé') ?>" required>
                        </div>
                        <button type="submit" class="btn-logout" style="background: var(--admin-primary); color: white; border:none; padding: 12px 30px; cursor: pointer;">
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <!-- Modal Chambre -->
    <div class="modal-overlay" id="roomModal">
        <div class="modal-box">
            <h3>Modifier la Chambre</h3>
            <input type="hidden" id="editRoomId">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" id="editRoomNom">
            </div>
            <div class="form-group">
                <label>Prix / Nuit</label>
                <input type="number" id="editRoomPrix">
            </div>
            <div class="form-group">
                <label>Image de la chambre</label>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <img id="roomImagePreview" src="<?= URL_ROOT ?>/images/room-placeholder.jpg" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                    <input type="file" id="editRoomImage" accept="image/*" onchange="previewImage(this)">
                </div>
                <small class="text-muted">Format recommandé : JPG, PNG ou WebP (max 5Mo)</small>
            </div>
            <div class="form-group">
                <label>Disponibilité</label>
                <select id="editRoomDispo">
                    <option value="1">Disponible</option>
                    <option value="0">Indisponible</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="editRoomDesc" rows="3"></textarea>
            </div>
            <div class="modal-actions" style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button class="btn-logout" style="background:#eee; color:#333; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;" onclick="closeEditModal()">Annuler</button>
                <button class="btn-logout" style="background:var(--admin-primary); color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;" onclick="saveRoom()">Sauvegarder</button>
            </div>
        </div>
    </div>

    <!-- Modal Offre -->
    <div class="modal-overlay" id="offerModal">
        <div class="modal-box">
            <h3><i class="fas fa-tags"></i> Gestion de l'Offre</h3>
            <input type="hidden" id="editOfferId">
            <div class="form-group">
                <label>Titre de l'Offre</label>
                <input type="text" id="editOfferTitre" placeholder="Ex: Pack Romance">
            </div>
            <div class="form-group">
                <label>Tag (Catégorie)</label>
                <input type="text" id="editOfferTag" placeholder="Ex: Couple, Affaires, Famille">
            </div>
            <div class="form-group">
                <label>Prix ou Texte Promotionnel</label>
                <input type="text" id="editOfferPrix" placeholder="Ex: À partir de 75 000 FCFA">
            </div>
            <div class="form-group">
                <label>Image de l'offre</label>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <img id="offerImagePreview" src="<?= URL_ROOT ?>/images/offer-placeholder.png" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                    <input type="file" id="editOfferImage" accept="image/*" onchange="previewOfferImage(this)">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="editOfferDesc" rows="3"></textarea>
            </div>
            <div class="modal-actions" style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button class="btn-logout" style="background:#eee; color:#333; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;" onclick="closeOfferModal()">Annuler</button>
                <button class="btn-logout" style="background:var(--admin-primary); color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;" onclick="saveOffer()">Sauvegarder</button>
            </div>
        </div>
    </div>

    <script>
        let calendar = null;

        /**
         * Bascule entre les différents onglets du Dashboard
         * @param {string} tabId - L'ID de la section à afficher
         * @param {HTMLElement} el - L'élément menu cliqué pour activer sa classe "active"
         */
        function showTab(tabId, el) {
            // Masquer tous les contenus et retirer les classes actives des menus
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.menu-item').forEach(b => b.classList.remove('active'));
            
            // Afficher l'onglet sélectionné
            const target = document.getElementById(tabId);
            if (target) {
                target.classList.add('active');
            }
            if(el) el.classList.add('active');

            // Initialisation différée du Calendrier pour optimiser le chargement (Planning)
            if (tabId === 'planning' && !calendar) {
                initCalendar();
            } else if (tabId === 'planning' && calendar) {
                calendar.render();
            }
        }

        /**
         * Initialise la vue Planning (FullCalendar)
         */
        function initCalendar() {
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: '<?= URL_ROOT ?>/api/admin/reservations/calendar',
                eventClick: function(info) {
                    alert('Réservation : ' + info.event.title + '\nStatut : ' + info.event.extendedProps.statut);
                }
            });
            calendar.render();
        }

        async function moderateReview(id, action) {
            const endpoint = `/api/admin/reviews/${action}`;
            if (action === 'delete' && !confirm('Voulez-vous vraiment supprimer cet avis ?')) return;

            try {
                const response = await fetch('<?= URL_ROOT ?>' + endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const result = await response.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + (result.message || 'Action impossible'));
                }
            } catch (error) {
                console.error(error);
                alert('Erreur de connexion.');
            }
        }

        // --- Logique Graphiques ---
        const statsData = <?php echo json_encode($stats); ?>;
        const labels = statsData.map(d => d.mois);
        const caData = statsData.map(d => d.CA);
        const volData = statsData.map(d => d.nb_reservations);

        const commonOptions = {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        };

        // Graphique Revenus
        new Chart(document.getElementById('revChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenus',
                    data: caData,
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: commonOptions
        });

        // Graphique Volume
        new Chart(document.getElementById('volChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Réservations',
                    data: volData,
                    backgroundColor: '#1A1A1A'
                }]
            },
            options: commonOptions
        });

        // --- CMS Lite : Gestion Réservations ---
        async function updateReservationStatus(id, statut) {
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/reservations/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, statut })
                });
                const result = await res.json();
                if (!result.success) {
                    alert('Erreur: ' + result.message);
                }
            } catch (err) { alert('Erreur de connexion.'); }
        }

        async function deleteReservation(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette réservation ? Cela effacera aussi les données de paiement associées.')) return;
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/reservations/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.success) location.reload();
                else alert('Erreur: ' + result.message);
            } catch (err) { alert('Erreur de connexion.'); }
        }

        // --- CMS Lite : Gestion Chambres ---
        function openAddRoomModal() {
            document.getElementById('editRoomId').value   = '';
            document.getElementById('editRoomNom').value  = '';
            document.getElementById('editRoomPrix').value = '';
            document.getElementById('editRoomImage').value = '';
            document.getElementById('roomImagePreview').src = '<?= URL_ROOT ?>/images/room-placeholder.jpg';
            document.getElementById('editRoomDispo').value = '1';
            document.getElementById('editRoomDesc').value = '';
            document.querySelector('#roomModal h3').innerHTML = '<i class="fas fa-bed"></i> Ajouter une Chambre';
            document.getElementById('roomModal').classList.add('active');
        }

        function openEditModal(room) {
            document.getElementById('editRoomId').value   = room.id;
            document.getElementById('editRoomNom').value  = room.nom;
            document.getElementById('editRoomPrix').value = room.prix;
            document.getElementById('editRoomImage').value = ''; // On ne pré-remplit pas le file input
            document.getElementById('roomImagePreview').src = '<?= URL_ROOT ?>/images/' + (room.image || 'room-placeholder.jpg');
            document.getElementById('editRoomDispo').value = room.disponible;
            document.getElementById('editRoomDesc').value = room.description || '';
            document.querySelector('#roomModal h3').innerHTML = '<i class="fas fa-bed"></i> Modifier la Chambre';
            document.getElementById('roomModal').classList.add('active');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('roomImagePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeEditModal() {
            document.getElementById('roomModal').classList.remove('active');
        }

        async function deleteRoom(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette chambre ?')) return;
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/rooms/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.success) location.reload();
                else alert('Erreur: ' + result.message);
            } catch (err) { alert('Erreur de connexion.'); }
        }

        /**
         * Envoie les données d'une chambre (nouvelle ou existante) au serveur.
         * Utilise FormData pour permettre l'upload d'images en multipart/form-data.
         */
        async function saveRoom() {
            const id          = document.getElementById('editRoomId').value;
            const nom         = document.getElementById('editRoomNom').value.trim();
            const prix        = document.getElementById('editRoomPrix').value;
            const imageFile   = document.getElementById('editRoomImage').files[0];
            const disponible  = document.getElementById('editRoomDispo').value;
            const description = document.getElementById('editRoomDesc').value.trim();

            if (!nom || !prix) { alert('Veuillez remplir le nom et le prix.'); return; }
            
            // Choix dynamique de l'endpoint selon s'il s'agit d'une édition ou d'une création
            const endpoint = id ? '/api/admin/rooms/update' : '/api/admin/rooms/add';

            const formData = new FormData();
            if (id) formData.append('id', id);
            formData.append('nom', nom);
            formData.append('prix', prix);
            formData.append('disponible', disponible);
            formData.append('description', description);
            if (imageFile) {
                formData.append('image', imageFile);
            }

            try {
                const res = await fetch('<?= URL_ROOT ?>' + endpoint, {
                    method: 'POST',
                    body: formData // On n'envoie PAS de Content-Type header, le navigateur le gère pour FormData
                });
                const result = await res.json();
                if (result.success) {
                    closeEditModal();
                    location.reload();
                } else {
                    alert('Erreur : ' + result.message);
                }
            } catch (err) {
                console.error(err);
                alert('Erreur de connexion.');
            }
        }

        /**
         * Sauvegarde les paramètres généraux du site (Nom, Email, etc.)
         * Envoie un objet JSON directement au contrôleur Admin.
         */
        async function saveSettings(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/settings/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.success) {
                    alert('Paramètres enregistrés avec succès !');
                    location.reload();
                } else {
                    alert('Erreur: ' + result.message);
                }
            } catch (err) { alert('Erreur de connexion.'); }
        }

        /**
         * Contrôleur de Menu Mobile pour l'Administration
         */
        const sidebar = document.querySelector('.admin-sidebar');
        const sidebarToggle = document.getElementById('adminSidebarToggle'); // Bouton de fermeture
        const menuOpen = document.getElementById('mobileMenuOpen'); // Bouton d'ouverture (Header)

        // Fermer/Afficher la sidebar sur mobile
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-active');
            });
        }
        
        // Ouvrir spécifiquement via le bouton du Header
        if (menuOpen) {
            menuOpen.addEventListener('click', () => {
                sidebar.classList.add('mobile-active');
            });
        }

        // --- CMS Lite : Gestion Offres ---
        function openAddOfferModal() {
            document.getElementById('editOfferId').value    = '';
            document.getElementById('editOfferTitre').value = '';
            document.getElementById('editOfferTag').value   = '';
            document.getElementById('editOfferPrix').value  = '';
            document.getElementById('editOfferImage').value = '';
            document.getElementById('offerImagePreview').src = '<?= URL_ROOT ?>/images/offer-placeholder.png';
            document.getElementById('editOfferDesc').value  = '';
            document.querySelector('#offerModal h3').innerHTML = '<i class="fas fa-tags"></i> Ajouter une Offre';
            document.getElementById('offerModal').classList.add('active');
        }

        function openEditOfferModal(offer) {
            document.getElementById('editOfferId').value    = offer.id;
            document.getElementById('editOfferTitre').value = offer.titre;
            document.getElementById('editOfferTag').value   = offer.tag;
            document.getElementById('editOfferPrix').value  = offer.prix_texte;
            document.getElementById('editOfferImage').value = ''; 
            document.getElementById('offerImagePreview').src = '<?= URL_ROOT ?>/images/' + (offer.image || 'offer-placeholder.png');
            document.getElementById('editOfferDesc').value  = offer.description || '';
            document.querySelector('#offerModal h3').innerHTML = '<i class="fas fa-tags"></i> Modifier l\'Offre';
            document.getElementById('offerModal').classList.add('active');
        }

        function previewOfferImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('offerImagePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeOfferModal() {
            document.getElementById('offerModal').classList.remove('active');
        }

        async function saveOffer() {
            const id          = document.getElementById('editOfferId').value;
            const titre       = document.getElementById('editOfferTitre').value.trim();
            const prix        = document.getElementById('editOfferPrix').value.trim();
            const tag         = document.getElementById('editOfferTag').value.trim();
            const imageFile   = document.getElementById('editOfferImage').files[0];
            const description = document.getElementById('editOfferDesc').value.trim();

            if (!titre || !prix) { alert('Veuillez remplir le titre et le prix.'); return; }
            
            const endpoint = id ? '/api/admin/offers/update' : '/api/admin/offers/add';
            const formData = new FormData();
            if (id) formData.append('id', id);
            formData.append('titre', titre);
            formData.append('prix_texte', prix);
            formData.append('tag', tag);
            formData.append('description', description);
            if (imageFile) formData.append('image', imageFile);

            try {
                const res = await fetch('<?= URL_ROOT ?>' + endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert('Erreur : ' + result.message);
                }
            } catch (err) { alert('Erreur de connexion.'); }
        }

        async function deleteOffer(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette offre ?')) return;
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/offers/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.success) location.reload();
                else alert('Erreur: ' + result.message);
            } catch (err) { alert('Erreur de connexion.'); }
        }

        // --- CMS Lite : Gestion Restaurant ---
        async function updateRestaurantStatus(id, statut) {
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/restaurant/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, statut })
                });
                const result = await res.json();
                if (!result.success) {
                    alert('Erreur: ' + result.message);
                } else {
                    location.reload(); // Pour mettre à jour les classes de badges
                }
            } catch (err) { alert('Erreur de connexion.'); }
        }

        async function deleteRestaurantReservation(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette réservation de table ?')) return;
            try {
                const res = await fetch('<?= URL_ROOT ?>/api/admin/restaurant/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.success) location.reload();
                else alert('Erreur: ' + result.message);
            } catch (err) { alert('Erreur de connexion.'); }
        }

        // Fermer le modal en cliquant en dehors de la boîte
        document.getElementById('roomModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
        document.getElementById('offerModal').addEventListener('click', function(e) {
            if (e.target === this) closeOfferModal();
        });
    </script>
</body>
</html>
