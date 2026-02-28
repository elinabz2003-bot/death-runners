<?php
/**
 * Contrôleur de traitement du formulaire de contact (Front).
 *
 * Insère le message dans la table `form`, envoie un email à tous les admins,
 * et gère la protection CSRF.
 *
 * @uses $_SESSION pour stocker les messages d'erreur / succès
 */

session_start();




$racine_path = __DIR__ . '/../../';



require_once $racine_path . 'model/db.php';




require_once $racine_path . 'admin/model/AdminDB.php';
require_once $racine_path . 'admin/model/AdminManager.php';


use App\Front\Model\FrontDB;      
use App\Admin\Model\AdminDB;
use App\Admin\Model\AdminManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalide !");
    }

    
    $nom     = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        $_SESSION['contact_error'] = "Tous les champs sont obligatoires.";
        header("Location: form.php");
        exit;
    }

    
    if (strlen($message) > 1000) {
        $_SESSION['contact_error'] =
            "Votre message doit contenir au maximum 1000 caractères.";
        header("Location: form.php");
        exit;
    }

    try {
        
        
        
        $pdo = FrontDB::getPDO();

        
        
        
        $stmt = $pdo->prepare("
            INSERT INTO form (nom, email, sujet, message)
            VALUES (:nom, :email, :sujet, :message)
        ");
        $stmt->execute([
            'nom'     => $nom,
            'email'   => $email,
            'sujet'   => $sujet,
            'message' => $message
        ]);

        
        if ($stmt->rowCount() > 0) {

            
            
            

            
            $pdoAdmin = AdminDB::getPDO();
            $adminMgr = new AdminManager($pdoAdmin);
            $adminEmails = $adminMgr->getAllAdminEmails();

            
            if (empty($adminEmails)) {
                $adminEmails[] = "contact@monsite.fr";
            }

            
            $to = implode(",", $adminEmails);

            
            $mailSubject = "[Death Runners] Nouveau message de $nom";
            $mailBody = "Nom : $nom\n"
                      . "Email : $email\n"
                      . "Sujet : $sujet\n\n"
                      . "$message\n";

            
            $headers = "From: $email\r\n"
                     . "Reply-To: $email\r\n"
                     . "Content-Type: text/plain; charset=utf-8\r\n";

            
            $mailSent = mail($to, $mailSubject, $mailBody, $headers);
            if ($mailSent) {
                $_SESSION['contact_success'] =
                    "Votre message a été envoyé et enregistré !";
            } else {
                $_SESSION['contact_success'] =
                    "Message enregistré, mais échec de l'envoi mail.";
            }

        } else {
            $_SESSION['contact_error'] =
                "Erreur lors de l'insertion du message en BDD.";
        }

        
        header("Location: form.php");
        exit;

    } catch (\PDOException $e) {
        $_SESSION['contact_error'] =
            "Erreur lors de l'envoi du message : " . $e->getMessage();
        header("Location: form.php");
        exit;
    }

} else {
    $_SESSION['contact_error'] = "Méthode non autorisée.";
    header("Location: form.php");
    exit;
}
