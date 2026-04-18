<?php

/**
 * Configuration des clés API pour les paiements
 * 
 * IMPORTANT: Ne commitez jamais ce fichier sur un dépôt public (comme GitHub)
 * si de vraies clés de production y sont insérées.
 */

return [
    // Configuration pour CinetPay (Très utilisé en Côte d'Ivoire pour Orange, MTN, Moov, Wave)
    'cinetpay' => [
        'site_id' => 'VOTRE_SITE_ID_CINETPAY',  // ID du site fourni par CinetPay
        'apikey'  => 'VOTRE_APIKEY_CINETPAY',   // Clé API fournie par CinetPay
        'base_url' => 'https://api-checkout.cinetpay.com/v2/payment',
        'env'     => 'sandbox', // ou 'production'
    ],

    // Configuration pour PayExpresse (Alternative)
    'payexpresse' => [
        'api_key' => 'VOTRE_CLE_API_PAYEXPRESSE',
        'api_secret' => 'VOTRE_SECRET_PAYEXPRESSE',
        'env'     => 'sandbox', // ou 'production'
    ],

    // Configuration directe Wave (Si intégration directe sans agrégateur)
    'wave' => [
        'api_key' => 'VOTRE_CLE_API_WAVE',
        'webhook_secret' => 'VOTRE_SECRET_WEBHOOK_WAVE',
        'base_url' => 'https://api.wave.com/v1',
        'env'     => 'sandbox', // ou 'production'
    ],
    
    // Configuration directe PayPal
    'paypal' => [
        'client_id' => 'VOTRE_CLIENT_ID_PAYPAL',
        'secret'    => 'VOTRE_SECRET_PAYPAL',
        'env'       => 'sandbox', // ou 'production' (change l'URL d'API)
    ],
    
    // Configuration directe Stripe (Pour Visa/Mastercard)
    'stripe' => [
        'public_key' => 'VOTRE_CLE_PUBLIQUE_STRIPE',
        'secret_key' => 'VOTRE_CLE_SECRETE_STRIPE',
        'webhook_secret' => 'VOTRE_SECRET_WEBHOOK_STRIPE',
    ]
];
