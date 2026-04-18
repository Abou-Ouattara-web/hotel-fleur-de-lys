<?php
/**
 * Script de création du compte administrateur par défaut.
 * À supprimer après utilisation.
 */

// On charge manuellement les classes nécessaires car nous sommes hors du flux normal
require_once __DIR__ . '/../../backend/config/Database.php';

use App\Config\Database;

try {
    $db = Database::getInstance();
    
    // 1. Vérifier si l'admin existe déjà
    $stmt = $db->prepare("SELECT id FROM clients WHERE email = 'admin@fleurdelys.com'");
    $stmt->execute();
    
    if ($stmt->fetch()) {
        die("<h2 style='color:orange;'>Le compte administrateur existe déjà.</h2>
             <p>Vous pouvez vous connecter avec <b>admin@fleurdelys.com</b> / <b>admin123</b></p>
             <a href='login'>Aller à la page de connexion</a>");
    }

    // 2. Création du compte avec le rôle 'admin'
    // On utilise PASSWORD_BCRYPT pour correspondre au modèle User
    $password = password_hash('admin123', PASSWORD_BCRYPT);
    
    $stmt = $db->prepare("
        INSERT INTO clients (prenom, nom, email, telephone, mot_de_passe, role) 
        VALUES ('Admin', 'Fleur De Lys', 'admin@fleurdelys.com', '+225 00000000', ?, 'admin')
    ");
    
    $stmt->execute([$password]);

    echo "<div style='font-family: sans-serif; padding: 20px; border: 2px solid #D4AF37; border-radius: 10px; max-width: 500px; margin: 50px auto; text-align: center;'>
            <h2 style='color: green;'>✅ Succès !</h2>
            <p>Le compte administrateur a été créé avec succès.</p>
            <hr>
            <p><b>Email :</b> admin@fleurdelys.com</p>
            <p><b>Mot de passe :</b> admin123</p>
            <hr>
            <p><a href='login' style='display: inline-block; padding: 10px 20px; background: #D4AF37; color: #000; text-decoration: none; border-radius: 5px; font-weight: bold;'>Se connecter maintenant</a></p>
            <p style='color: red; font-size: 0.9em;'>⚠️ <b>ALERTE :</b> Supprimez ce fichier (<code>frontend/public/create_admin.php</code>) immédiatement pour sécuriser votre site.</p>
          </div>";

} catch (Exception $e) {
    echo "<h2 style='color:red;'>Erreur lors de la création :</h2> " . $e->getMessage();
}
