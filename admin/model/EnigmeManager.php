<?php

namespace App\Admin\Model;

use PDO;
use App\Classes\Enigme;

/**
 * Classe EnigmeManager
 *
 * Fournit des méthodes pour interagir avec la table "enigmes" dans la base de données.
 * Permet de récupérer, créer, modifier et supprimer des énigmes.
 */
class EnigmeManager
{
    private PDO $db;

    /**
     * Constructeur du gestionnaire d'énigmes
     *
     * @param PDO $db Instance PDO de la base de données
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Retourne toutes les énigmes en objets Enigme
     *
     * @return Enigme[] Liste des énigmes
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM enigmes ORDER BY id_enigme ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $enigmes = [];
        foreach ($rows as $r) {
            $enigmes[] = new Enigme(
                $r['id_enigme'],
                $r['titre'],
                $r['contenu'],
                $r['reponse'],
                $r['difficulte']
            );
        }
        return $enigmes;
    }

    /**
     * Compte le nombre total d'énigmes
     *
     * @return int Nombre total d'énigmes
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM enigmes";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['cnt'];
    }

    /**
     * Insère une nouvelle énigme dans la base de données
     *
     * @param Enigme $enigme Objet énigme à insérer
     * @return bool True si l'insertion réussit, false sinon
     */
    public function create(Enigme $enigme): bool
    {
        $sql = "INSERT INTO enigmes (titre, contenu, reponse, difficulte)
                VALUES (:titre, :contenu, :reponse, :diff)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'titre'   => $enigme->getTitre(),
            'contenu' => $enigme->getContenu(),
            'reponse' => $enigme->getReponse(),
            'diff'    => $enigme->getDifficulte()
        ]);
    }

    /**
     * Supprime une énigme à partir de son identifiant
     *
     * @param int $id Identifiant de l'énigme
     * @return bool True si la suppression réussit, false sinon
     */
    public function deleteById(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM enigmes WHERE id_enigme = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Met à jour une énigme existante dans la base
     *
     * @param Enigme $enigme Objet énigme avec nouvelles valeurs
     * @return bool True si la mise à jour réussit, false sinon
     */
    public function update(Enigme $enigme): bool
    {
        $sql = "UPDATE enigmes 
                SET titre = :titre, contenu = :contenu, reponse = :reponse, difficulte = :diff
                WHERE id_enigme = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'titre'   => $enigme->getTitre(),
            'contenu' => $enigme->getContenu(),
            'reponse' => $enigme->getReponse(),
            'diff'    => $enigme->getDifficulte(),
            'id'      => $enigme->getIdEnigme()
        ]);
    }
}
