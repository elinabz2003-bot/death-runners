<?php

namespace App\Front\Model;

use PDO;
use App\Classes\Enigme;

/**
 * Classe EnigmeManager (Front)
 *
 * Gère les interactions avec la table "enigmes" dans la base de données
 * depuis la partie front du site. Permet de récupérer, ajouter, mettre à jour
 * ou supprimer des énigmes.
 */
class EnigmeManager
{
    private PDO $db;

    /**
     * Constructeur du gestionnaire d'énigmes
     *
     * @param PDO $db Instance PDO connectée à la base de données
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère une énigme à partir de son identifiant
     *
     * @param int $id Identifiant de l'énigme
     * @return Enigme|null Objet Enigme ou null si non trouvé
     */
    public function getById(int $id): ?Enigme
    {
        $stmt = $this->db->prepare("SELECT * FROM enigmes WHERE id_enigme = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Enigme(
            $row['id_enigme'],
            $row['titre'],
            $row['contenu'],
            $row['reponse'],
            $row['difficulte']
        );
    }

    /**
     * Récupère toutes les énigmes de la base
     *
     * @return Enigme[] Tableau d'objets Enigme
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM enigmes");
        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Enigme(
                $row['id_enigme'],
                $row['titre'],
                $row['contenu'],
                $row['reponse'],
                $row['difficulte']
            );
        }

        return $results;
    }

    /**
     * Ajoute une énigme dans la base de données
     *
     * @param Enigme $e L'énigme à ajouter
     * @return bool True si l'ajout a réussi, false sinon
     */
    public function add(Enigme $e): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO enigmes (titre, contenu, reponse, difficulte)
            VALUES (:titre, :contenu, :reponse, :difficulte)
        ");
        return $stmt->execute([
            'titre'      => $e->getTitre(),
            'contenu'    => $e->getContenu(),
            'reponse'    => $e->getReponse(),
            'difficulte' => $e->getDifficulte()
        ]);
    }

    /**
     * Met à jour une énigme existante
     *
     * @param Enigme $e L'énigme avec les nouvelles valeurs
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function update(Enigme $e): bool
    {
        $stmt = $this->db->prepare("
            UPDATE enigmes SET 
                titre = :titre, 
                contenu = :contenu, 
                reponse = :reponse, 
                difficulte = :difficulte
            WHERE id_enigme = :id
        ");
        return $stmt->execute([
            'titre'      => $e->getTitre(),
            'contenu'    => $e->getContenu(),
            'reponse'    => $e->getReponse(),
            'difficulte' => $e->getDifficulte(),
            'id'         => $e->getIdEnigme()
        ]);
    }

    /**
     * Supprime une énigme de la base de données
     *
     * @param int $id Identifiant de l'énigme à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM enigmes WHERE id_enigme = :id");
        return $stmt->execute(['id' => $id]);
    }
}