<?php
/**
 * Contrôleur pour l'effet visuel "glitch".
 *
 * Ce fichier reçoit une requête POST et renvoie une réponse JSON contenant :
 * - un booléen 'glitch' indiquant que l'effet est actif
 * - une valeur d'intensité aléatoire entre 1 et 5
 * - un message pour l'interface utilisateur
 *
 * Cette réponse est utilisée côté client pour appliquer un effet de perturbation visuelle.
 *
 * @return JSON Contenant les clés 'glitch', 'intensity' et 'message'
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $intensity = rand(1, 5); 
    echo json_encode([
        'glitch' => true,
        'intensity' => $intensity,
        'message' => 'Effet glitch activé'
    ]);
    exit;
}
?>