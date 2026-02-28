<?php


/**
 * Récupère l'énigme correspondant à un niveau donné.
 *
 * @param PDO $pdo La connexion à la base de données
 * @param int $level Le niveau de l'utilisateur
 * @return array Les énigmes stockés dans la base de données corresponsant au niveau de l'utilisateur
 */
function getEnigmaByLevel($pdo, $level) {
    $stmt = $pdo->prepare("SELECT * FROM enigmes WHERE level = :level LIMIT 1");
    $stmt->execute(['level' => $level]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Ajoute une nouvelle énigme dans la base de données.
 *
 * @param PDO $pdo
 * @param int $level
 * @param string $question
 * @param string $correct_answer
 * @param string|null $hint
 * @return bool
 */
function addEnigma($pdo, $level, $question, $correct_answer, $hint = null) {
    $stmt = $pdo->prepare("INSERT INTO enigmes (level, question, correct_answer, hint) VALUES (:level, :question, :correct_answer, :hint)");
    return $stmt->execute([
        'level' => $level,
        'question' => $question,
        'correct_answer' => $correct_answer,
        'hint' => $hint
    ]);
}

/**
 * Met à jour une énigme existante.
 *
 * @param PDO $pdo
 * @param int $id
 * @param string $question
 * @param string $correct_answer
 * @param string|null $hint
 * @return bool
 */
function updateEnigma($pdo, $id, $question, $correct_answer, $hint = null) {
    $stmt = $pdo->prepare("UPDATE enigmes SET question = :question, correct_answer = :correct_answer, hint = :hint WHERE id = :id");
    return $stmt->execute([
        'question' => $question,
        'correct_answer' => $correct_answer,
        'hint' => $hint,
        'id' => $id
    ]);
}

/**
 *
 * @param PDO $pdo
 * @param int $id
 * @return bool
 */
function deleteEnigma($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM enigmes WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>
