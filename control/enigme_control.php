<?php
/**
 * Contrôleur pour la gestion des énigmes via des requêtes AJAX.
 *
 * Ce fichier traite les requêtes POST selon l'action demandée :
 * - get_enigma : récupérer une énigme par niveau
 * - add_enigma : ajouter une nouvelle énigme
 * - update_enigma : modifier une énigme existante
 * - delete_enigma : supprimer une énigme par ID
 *
 * @uses getEnigmaByLevel(PDO $pdo, int $level)
 * @uses addEnigma(PDO $pdo, int $level, string $question, string $correct_answer, string $hint)
 * @uses updateEnigma(PDO $pdo, int $id, string $question, string $correct_answer, string $hint)
 * @uses deleteEnigma(PDO $pdo, int $id)
 *
 * @return JSON Réponse JSON avec clés 'success' et éventuellement 'message' ou 'enigma'
 */

session_start();
require_once "../model/db.php";             
require_once "../model/enigme_model.php";   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'get_enigma') {
        $level = intval($_POST['level'] ?? 1);
        $enigma = getEnigmaByLevel($pdo, $level);
        if ($enigma) {
            echo json_encode(['success' => true, 'enigma' => $enigma]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucune énigme trouvée pour ce niveau.']);
        }
        exit;
    }
    elseif ($action === 'add_enigma') {
        $level = intval($_POST['level'] ?? 1);
        $question = $_POST['question'] ?? '';
        $correct_answer = $_POST['correct_answer'] ?? '';
        $hint = $_POST['hint'] ?? '';
        if (addEnigma($pdo, $level, $question, $correct_answer, $hint)) {
            echo json_encode(['success' => true, 'message' => 'Énigme ajoutée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l’ajout de l’énigme.']);
        }
        exit;
    }
    elseif ($action === 'update_enigma') {
        $id = intval($_POST['id'] ?? 0);
        $question = $_POST['question'] ?? '';
        $correct_answer = $_POST['correct_answer'] ?? '';
        $hint = $_POST['hint'] ?? '';
        if (updateEnigma($pdo, $id, $question, $correct_answer, $hint)) {
            echo json_encode(['success' => true, 'message' => 'Énigme mise à jour avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour.']);
        }
        exit;
    }
    elseif ($action === 'delete_enigma') {
        $id = intval($_POST['id'] ?? 0);
        if (deleteEnigma($pdo, $id)) {
            echo json_encode(['success' => true, 'message' => 'Énigme supprimée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la suppression.']);
        }
        exit;
    }
}
?>
