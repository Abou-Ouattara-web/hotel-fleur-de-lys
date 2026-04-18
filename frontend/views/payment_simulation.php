<?php
/**
 * Vue de simulation de paiement dynamique
 * S'adapte au moyen de paiement (MTN, Orange, Moov, Wave, Visa, etc.)
 */

$methodConfigs = [
    'moov' => ['color' => '#0056b3', 'name' => 'Moov Money', 'logo' => 'moov-logo.webp', 'icon' => 'fas fa-mobile-alt'],
    'mtn'  => ['color' => '#ffcc00', 'name' => 'MTN Money', 'logo' => 'mtn-logo.png', 'icon' => 'fas fa-mobile-alt'],
    'orange' => ['color' => '#ff6600', 'name' => 'Orange Money', 'logo' => 'orange-logo.png', 'icon' => 'fas fa-mobile-alt'],
    'wave' => ['color' => '#1dcad3', 'name' => 'Wave', 'logo' => 'wave-logo.png', 'icon' => 'fas fa-mobile-alt'],
    'visa' => ['color' => '#1a1f71', 'name' => 'Visa', 'logo' => '', 'icon' => 'fab fa-cc-visa'],
    'mastercard' => ['color' => '#eb001b', 'name' => 'Mastercard', 'logo' => '', 'icon' => 'fab fa-cc-mastercard'],
    'paypal' => ['color' => '#003087', 'name' => 'PayPal', 'logo' => '', 'icon' => 'fab fa-paypal'],
    'virement' => ['color' => '#4a5568', 'name' => 'Virement Bancaire', 'logo' => '', 'icon' => 'fas fa-university'],
    'especes' => ['color' => '#2d3748', 'name' => 'Espèces', 'logo' => '', 'icon' => 'fas fa-money-bill-wave']
];

$config = $methodConfigs[$method] ?? $methodConfigs['wave'];
$primaryColor = $config['color'];
$paymentName = $config['name'];
$logo = $config['logo'];
$icon = $config['icon'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement <?php echo $paymentName; ?> - Simulation</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: <?php echo $primaryColor; ?>;
            --text-dark: #0f172a;
            --bg-color: #f8fafc;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: var(--text-dark);
        }

        .checkout-container {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #edf2f7;
        }

        .checkout-header {
            background-color: var(--primary-color);
            padding: 30px;
            text-align: center;
            color: <?php echo ($method === 'mtn') ? '#000' : '#fff'; ?>;
        }

        .checkout-header img {
            height: 50px;
            margin-bottom: 10px;
            object-fit: contain;
        }

        .checkout-header i {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .checkout-body {
            padding: 30px;
        }

        .amount-display {
            text-align: center;
            margin-bottom: 30px;
        }

        .amount-display .label {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }

        .amount-display .value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 10px;
        }

        .info-row span:first-child {
            color: #64748b;
        }

        .info-row span:last-child {
            font-weight: 600;
        }

        .btn-pay {
            background-color: var(--primary-color);
            color: <?php echo ($method === 'mtn') ? '#000' : '#fff'; ?>;
            border: none;
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-pay:hover {
            filter: brightness(0.9);
            transform: translateY(-2px);
        }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-cancel:hover {
            color: #64748b;
        }

        .loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: <?php echo ($method === 'mtn') ? '#000' : '#fff'; ?>;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .success-animation {
            display: none;
            text-align: center;
            padding: 40px 20px;
        }

        .success-animation i {
            font-size: 60px;
            color: #10b981;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="checkout-container" id="paymentContainer">
        <div class="checkout-header">
            <?php if ($logo): ?>
                <img src="<?php echo URL_ROOT; ?>/images/paiement/<?php echo $logo; ?>" alt="<?php echo $paymentName; ?> Logo">
            <?php else: ?>
                <i class="<?php echo $icon; ?>"></i>
            <?php endif; ?>
            <h2>Paiement <?php echo $paymentName; ?></h2>
        </div>
        <div class="checkout-body">
            <div class="amount-display">
                <span class="label">Montant à payer</span>
                <span class="value"><?php echo number_format($reservation['prix_total'], 0, ',', ' '); ?> FCFA</span>
            </div>

            <div class="info-details">
                <div class="info-row">
                    <span>Hôtel</span>
                    <span>FLEUR DE LYS TIASSALÉ</span>
                </div>
                <div class="info-row">
                    <span>Référence</span>
                    <span><?php echo $reference; ?></span>
                </div>
                <div class="info-row">
                    <span>Réservation</span>
                    <span>#<?php echo $reservationId; ?></span>
                </div>
            </div>

            <button class="btn-pay" id="payButton" onclick="processPayment()">
                <span>Confirmer le paiement</span>
                <div class="loader" id="loader"></div>
            </button>

            <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-cancel">Annuler la transaction</a>
        </div>
    </div>

    <div class="checkout-container success-animation" id="successContainer">
        <i class="fas fa-check-circle"></i>
        <h2>Paiement Réussi !</h2>
        <p>Votre transaction via <?php echo $paymentName; ?> a été validée.</p>
        <p>Redirection vers l'hôtel...</p>
    </div>

    <script>
        async function processPayment() {
            const btn = document.getElementById('payButton');
            const loader = document.getElementById('loader');
            const btnText = btn.querySelector('span');

            btn.disabled = true;
            btnText.style.opacity = '0.5';
            loader.style.display = 'block';

            try {
                // Appel API pour confirmer le paiement
                const response = await fetch('<?php echo URL_ROOT; ?>/api/payment/confirmation', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        reference: '<?php echo $reference; ?>',
                        reservation_id: '<?php echo $reservationId; ?>',
                        status: 'reussi'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('paymentContainer').style.display = 'none';
                    document.getElementById('successContainer').style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = '<?php echo URL_ROOT; ?>/reservation?status=success&ref=<?php echo $reference; ?>';
                    }, 3000);
                } else {
                    alert('Erreur lors de la validation : ' + result.message);
                    btn.disabled = false;
                    btnText.style.opacity = '1';
                    loader.style.display = 'none';
                }
            } catch (error) {
                console.error(error);
                alert('Une erreur est survenue lors de la connexion au serveur.');
                btn.disabled = false;
                btnText.style.opacity = '1';
                loader.style.display = 'none';
            }
        }
    </script>
</body>
</html>
