<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - FLEUR DE LYS</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/pages/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/vendor/fontawesome/css/all.min.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <img src="<?php echo URL_ROOT; ?>/images/logo-fleur-de-lys.png" alt="Fleur de Lys" class="logo-img" width="60" height="60">
                    <span class="logo-text"><?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'FLEUR DE LYS'); ?></span>
                </div>
                <div class="nav-menu">
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/">Accueil</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/reservation">Réserver</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/logout" class="btn-logout-nav">Déconnexion</a></li>
                    </ul>
                </div>

                <button class="menu-toggle" id="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="dashboard-section">
        <div class="container">
            <div class="dashboard-grid">
                
                <!-- Sidebar Profil -->
                <aside class="profile-sidebar glass-sidebar">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($user['nom'] ?? '?', 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    
                    <nav class="profile-nav">
                        <a href="javascript:void(0)" class="nav-item active" onclick="showSection('reservations', this)"><i class="fas fa-calendar-alt"></i> Mes Séjours</a>
                        <a href="javascript:void(0)" class="nav-item" onclick="showSection('restaurant', this)"><i class="fas fa-utensils"></i> Mes Tables</a>
                        <a href="javascript:void(0)" class="nav-item" onclick="showSection('profil', this)"><i class="fas fa-user-edit"></i> Mon Profil</a>
                        <a href="<?php echo URL_ROOT; ?>/logout" style="color: #e74c3c; margin-top: 1rem;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    </nav>
                </aside>

                <!-- Content Area -->
                <div class="dashboard-content">
                    
                    <!-- Banner & Stats -->
                    <div class="welcome-banner">
                        <div class="banner-text">
                            <h2>Bienvenue, <?php echo htmlspecialchars($user['prenom']); ?></h2>
                            <p>Nous sommes ravis de vous revoir. Gérez vos activités en toute sérénité.</p>
                        </div>
                    </div>

                    <div class="stats-bar">
                        <div class="stat-item glass">
                            <i class="fas fa-bed"></i>
                            <div class="stat-info">
                                <span class="stat-value"><?= count($reservations) ?></span>
                                <span class="stat-label">Séjours</span>
                            </div>
                        </div>
                        <div class="stat-item glass">
                            <i class="fas fa-utensils"></i>
                            <div class="stat-info">
                                <span class="stat-value"><?= count($restaurantReservations) ?></span>
                                <span class="stat-label">Dîners</span>
                            </div>
                        </div>
                        <div class="stat-item glass">
                            <i class="fas fa-star"></i>
                            <div class="stat-info">
                                <span class="stat-value">Or</span>
                                <span class="stat-label">Statut</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reservations -->
                    <div class="reservations-card dashboard-section-content" id="reservations">
                        <div class="card-header">
                            <h3>Mes Séjours à l'Hôtel</h3>
                            <span class="badge status-confirmee"><?php echo count($reservations); ?> Réservations</span>
                        </div>

                        <?php if (empty($reservations)): ?>
                            <div class="empty-state" style="text-align: center; padding: 4rem 0;">
                                <i class="fas fa-calendar-times" style="font-size: 4rem; color: #eee; margin-bottom: 2rem; display: block;"></i>
                                <p>Vous n'avez pas encore de réservations.</p>
                                <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-primary" style="display: inline-block; margin-top: 1.5rem;">Réserver maintenant</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="res-table">
                                    <thead>
                                        <tr>
                                            <th>Chambre</th>
                                            <th>Période</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservations as $res): ?>
                                            <tr class="res-row">
                                                <td>
                                                    <div class="room-info">
                                                        <img src="<?php echo URL_ROOT; ?>/images/<?php echo $res['chambre_image'] ?: 'room-placeholder.jpg'; ?>" class="room-img" alt="Chambre">
                                                        <div class="room-details">
                                                            <h4><?php echo htmlspecialchars($res['chambre_nom']); ?></h4>
                                                            <small>#<?php echo $res['numero_reservation']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="date-period">
                                                        <div><i class="fas fa-arrow-right" style="color:#D4AF37; font-size:0.7rem;"></i> <?php echo date('d/m/Y', strtotime($res['date_arrivee'])); ?></div>
                                                        <div><i class="fas fa-arrow-left" style="color:#888; font-size:0.7rem;"></i> <?php echo date('d/m/Y', strtotime($res['date_depart'])); ?></div>
                                                    </div>
                                                </td>
                                                <td style="font-weight: 600;">
                                                    <?php echo number_format($res['prix_total'], 0, ',', ' '); ?> F
                                                </td>
                                                <td class="status-cell">
                                                    <?php 
                                                        $statusLabels = [
                                                            'en_attente' => 'En Attente',
                                                            'confirmee'  => 'Confirmée',
                                                            'annulee'    => 'Annulée',
                                                            'terminee'   => 'Terminée'
                                                        ];
                                                        $statusClass = 'status-' . $res['statut'];
                                                    ?>
                                                    <span class="status-badge <?php echo $statusClass; ?>">
                                                        <?php echo $statusLabels[$res['statut']] ?? $res['statut']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo URL_ROOT; ?>/api/reservation/invoice?id=<?php echo $res['id']; ?>" class="btn-invoice" title="Télécharger facture">
                                                        <i class="fas fa-file-pdf"></i> Facture
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Section Restaurant -->
                    <div class="reservations-card dashboard-section-content" id="restaurant" style="display:none;">
                        <div class="card-header">
                            <h3>Mes Réservations de Table</h3>
                        </div>

                        <?php if (empty($restaurantReservations)): ?>
                            <div class="empty-state">
                                <i class="fas fa-utensils" style="font-size: 3rem; color: #eee; margin-bottom: 1.5rem; display: block;"></i>
                                <p>Aucune réservation de table pour le moment.</p>
                                <a href="<?php echo URL_ROOT; ?>/restaurant" class="btn-primary" style="display: inline-block; margin-top: 1rem;">Réserver une table</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="res-table">
                                    <thead>
                                        <tr>
                                            <th>Date & Heure</th>
                                            <th>Détails</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($restaurantReservations as $rres): ?>
                                            <tr class="res-row">
                                                <td>
                                                    <strong><?= date('d/m/Y', strtotime($rres['date_reservation'])) ?></strong><br>
                                                    <small><?= date('H:i', strtotime($rres['heure_reservation'])) ?></small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-users"></i> <?= $rres['couverts'] ?> couverts<br>
                                                    <small><?= htmlspecialchars($rres['occasion'] ?: 'Dîner standard') ?></small>
                                                </td>
                                                <td>
                                                    <span class="status-badge status-<?= $rres['statut'] ?>">
                                                        <?= htmlspecialchars($rres['statut'] === 'confirmee' ? 'Confirmée' : ($rres['statut'] === 'annulee' ? 'Annulée' : 'En attente')) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Section Profil -->
                    <div class="reservations-card dashboard-section-content" id="profil" style="display:none;">
                        <div class="card-header">
                            <h3>Mon Profil Personnel</h3>
                        </div>
                        <form class="profile-form" onsubmit="updateProfile(event)">
                            <div class="form-row" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" id="profPrenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" id="profNom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:20px;">
                                <label>Email</label>
                                <input type="email" id="profEmail" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="form-group" style="margin-bottom:20px;">
                                <label>Téléphone</label>
                                <input type="text" id="profTel" value="<?= htmlspecialchars($user['telephone']) ?>" required>
                            </div>
                            <button type="submit" class="btn-primary" id="btnUpdateProfile">Enregistrer les modifications</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="footer" style="background:#1A1A1A; padding: 4rem 0; color:#fff; text-align:center;">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteSettings['hotel_name'] ?? 'FLEUR DE LYS'); ?> - Espace Client</p>
        </div>
    </footer>

    <script>
        function showSection(sectionId, btn) {
            // Cacher toutes les sections
            document.querySelectorAll('.dashboard-section-content').forEach(s => s.style.display = 'none');
            // Afficher la cible
            document.getElementById(sectionId).style.display = 'block';
            
            // UI update sidebar
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            btn.classList.add('active');
        }

        async function updateProfile(e) {
            e.preventDefault();
            const btn = document.getElementById('btnUpdateProfile');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour...';
            btn.disabled = true;

            const data = {
                prenom: document.getElementById('profPrenom').value,
                nom: document.getElementById('profNom').value,
                email: document.getElementById('profEmail').value,
                telephone: document.getElementById('profTel').value
            };

            try {
                const res = await fetch('<?= URL_ROOT ?>/api/user/update-profile', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.success) {
                    alert('Votre profil a été mis à jour avec succès !');
                    location.reload();
                } else {
                    alert('Erreur: ' + result.message);
                }
            } catch (err) {
                alert('Erreur de connexion au serveur.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
