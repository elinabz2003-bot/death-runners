<?php
/**
 * Contrôleur de validation des niveaux du jeu.
 *
 * Ce fichier reçoit des requêtes POST avec une action spécifique pour chaque niveau.
 * Il valide les réponses envoyées par le joueur, met à jour la base de données
 * (progression et score), et retourne une réponse JSON.
 *
 * Actions traitées :
 * - validate_level1 : vérifier si la réponse au niveau 1 est correcte ("2")
 * - validate_level2 : vérifier la complétion du code ("1,11")
 * - validate_level3 : identifier une erreur dans un code (doit contenir "2 arguments" ou "deux arguments")
 * - validate_level4 : choix moral entre deux options ("1" ou "2")
 * - next_level : incrémente simplement le niveau en session
 *
 * @return JSON Réponse contenant success=true/false et optionnellement un message
 */

session_start();
require_once "../model/db.php"; 


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    
    if ($action === 'validate_level1') {
        $answer = $_POST['answer'] ?? '';
        if ($answer === "2") {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET progre = progre + 1, score = score + 100 
                WHERE id_user = :id_user
            ");
            $stmt->execute(['id_user' => $_SESSION['user_id']]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    
    elseif ($action === 'validate_level2') {
        $answer = $_POST['answer'] ?? '';
        $normalized = str_replace(" ", "", $answer);
        if ($normalized === "1,11") {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET progre = progre + 1, score = score + 100 
                WHERE id_user = :id_user
            ");
            $stmt->execute(['id_user' => $_SESSION['user_id']]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    
    elseif ($action === 'validate_level3') {
        $answer = $_POST['answer'] ?? '';
        $lower = strtolower($answer);
        if (strpos($lower, "2 arguments") !== false || strpos($lower, "deux arguments") !== false) {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET progre = progre + 1, score = score + 100 
                WHERE id_user = :id_user
            ");
            $stmt->execute(['id_user' => $_SESSION['user_id']]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    
    elseif ($action === 'validate_level4') {
        $answer = $_POST['answer'] ?? '';
        if ($answer === "1") {
            $message = "Intéressant... Es-tu prêt à aller jusqu’au bout ?";
            $stmt = $pdo->prepare("
                UPDATE users 
                SET progre = progre + 1, score = score + 50 
                WHERE id_user = :id_user
            ");
            $stmt->execute(['id_user' => $_SESSION['user_id']]);

            echo json_encode(['success' => true, 'message' => $message]);
        } elseif ($answer === "2") {
            $message = "Tu penses pouvoir tenir la douleur ? Voyons voir...";
            $stmt = $pdo->prepare("
                UPDATE users 
                SET progre = progre + 1, score = score + 50 
                WHERE id_user = :id_user
            ");
            $stmt->execute(['id_user' => $_SESSION['user_id']]);

            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    
    elseif ($action === 'next_level') {
        $_SESSION['level'] = ($_SESSION['level'] ?? 1) + 1;
        echo json_encode(['success' => true]);
        exit;
    }
}
?>
