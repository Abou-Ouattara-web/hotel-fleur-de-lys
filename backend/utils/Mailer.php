<?php

namespace App\Utils;

/**
 * Utilitaire Mailer
 * Gère l'envoi des communications clients par email.
 */
class Mailer {
    private static $from = "Hôtel Fleur de Lys <noreply@fleurdelys-tiassale.com>";
    
    /**
     * Envoie un email de confirmation de réservation.
     */
    public static function sendConfirmation(array $reservation): bool {
        $to = $reservation['email'];
        $subject = "Confirmation de réservation - " . $reservation['numero_reservation'];
        
        // Construction du message HTML (Responsive simple)
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #D4AF37; }
                .header { background: #1A1A1A; color: #D4AF37; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { font-size: 12px; color: #777; margin-top: 20px; text-align: center; }
                .btn { display: inline-block; padding: 10px 20px; background: #D4AF37; color: #000; text-decoration: none; border-radius: 5px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Hôtel FLEUR DE LYS</h1>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>{$reservation['prenom']} {$reservation['nom']}</strong>,</p>
                    <p>Nous avons le plaisir de vous confirmer votre réservation pour votre prochain séjour parmi nous.</p>
                    
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr><td><strong>Numéro :</strong></td><td>{$reservation['numero_reservation']}</td></tr>
                        <tr><td><strong>Arrivée :</strong></td><td>".date('d/m/Y', strtotime($reservation['date_arrivee']))."</td></tr>
                        <tr><td><strong>Départ :</strong></td><td>".date('d/m/Y', strtotime($reservation['date_depart']))."</td></tr>
                        <tr><td><strong>Chambre :</strong></td><td>{$reservation['chambre_nom']}</td></tr>
                        <tr><td><strong>Total :</strong></td><td>".number_format($reservation['prix_total'], 0, ',', ' ')." FCFA</td></tr>
                    </table>

                    <p style='margin-top: 20px;'>
                        <a href='https://fleurdelys-tiassale.com/api/reservation/invoice?id={$reservation['id']}' class='btn'>📁 Télécharger ma Facture PDF</a>
                    </p>

                    <p>Nous avons hâte de vous accueillir !</p>
                </div>
                <div class='footer'>
                    <p>Hôtel Fleur de Lys - Axe N'douci-Tiassalé, Tiassalé, Côte d'Ivoire</p>
                    <p>Tél: +225 07 03 24 44 64 | Email: fleurdelys1821@gmail.com</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return self::send($to, $subject, $message);
    }

    /**
     * Méthode d'envoi générique avec Fallback Log
     */
    private static function send(string $to, string $subject, string $message): bool {
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . self::$from . "\r\n";
        $headers .= "Reply-To: fleurdelys1821@gmail.com" . "\r\n";

        // 1. Tenter l'envoi réel
        $success = @mail($to, $subject, $message, $headers);

        // 2. Toujours enregistrer dans les logs pour le débogage (XAMPP friendly)
        self::logEmail($to, $subject, $message);

        return $success;
    }

    /**
     * Enregistre le contenu de l'email dans un fichier local pour vérification.
     */
    private static function logEmail($to, $subject, $message) {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        
        $logFile = $logDir . '/emails_' . date('Y-m-d') . '.log';
        $logEntry = "[" . date('H:i:s') . "] TO: $to | SUBJECT: $subject \n";
        $logEntry .= "BODY: " . strip_tags($message) . "\n";
        $logEntry .= str_repeat("-", 50) . "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
