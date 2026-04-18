<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée | FLEUR DE LYS</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/base.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        body { margin: 0; }
        .error-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: var(--black);
            color: var(--white);
            padding: 2rem;
        }
        .error-code {
            font-size: clamp(80px, 20vw, 160px);
            font-family: 'Cormorant Garamond', serif;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 0.5rem;
            animation: fadeInUp 0.8s ease;
            text-shadow: 0 0 80px rgba(212, 175, 55, 0.3);
        }
        .error-divider {
            width: 80px;
            height: 2px;
            background: var(--gold);
            margin: 1rem auto;
            animation: fadeInUp 0.8s ease 0.1s both;
        }
        .error-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease 0.2s both;
        }
        .error-message {
            font-size: 1rem;
            color: var(--gray-light);
            max-width: 500px;
            margin-bottom: 2.5rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease 0.3s both;
        }
        .error-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.4s both;
        }
        .btn-home {
            padding: 0.9rem 2.5rem;
            background: var(--gold);
            color: var(--black);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212,175,55,0.3);
        }
        .btn-outline {
            padding: 0.9rem 2.5rem;
            background: transparent;
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 1px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
        }
        .error-icon {
            font-size: 3rem;
            color: var(--gold);
            opacity: 0.4;
            margin-bottom: 1rem;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <main class="error-page">
        <div class="error-icon"><i class="fas fa-compass"></i></div>
        <div class="error-code">404</div>
        <div class="error-divider"></div>
        <h1 class="error-title">Page introuvable</h1>
        <p class="error-message">
            La page que vous recherchez a peut-être été déplacée, supprimée,<br>
            ou n'a jamais existé. Laissez-nous vous guider.
        </p>
        <div class="error-links">
            <a href="<?php echo URL_ROOT; ?>/" class="btn-home"><i class="fas fa-home"></i>&nbsp; Retour à l'accueil</a>
            <a href="<?php echo URL_ROOT; ?>/reservation" class="btn-outline"><i class="fas fa-calendar-check"></i>&nbsp; Faire une réservation</a>
        </div>
    </main>
</body>
</html>
