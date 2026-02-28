<?php

namespace App\Admin\Model;

use PDO;
use App\Classes\User;

/**
 * Classe UserManager
 *
 * Gère les opérations de base de données liées aux utilisateurs.
 * Permet de récupérer tous les utilisateurs, en compter le total,
 * récupérer les derniers inscrits, et gérer le bannissement des utilisateurs.
 */
class UserManager
{
    private PDO $db;

    /**
     * Constructeur du gestionnaire d'utilisateurs
     *
     * @param PDO $db Instance PDO connectée à la base de données
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les utilisateurs de la base de données.
     *
     * @return User[] Liste des utilisateurs sous forme d'objets User.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM users ORDER BY id_user ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[] = new User(
                $row['id_user'],
                $row['pseudo'],
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['mot_de_passe'],
                $row['date_inscription'],
                $row['d_nee']
            );
        }
        return $users;
    }

    /**
     * Compte le nombre total d'utilisateurs dans la base.
     *
     * @return int Nombre total d'utilisateurs.
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM users";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['cnt'];
    }

    /**
     * Récupère les derniers utilisateurs inscrits.
     *
     * @param int $limit Nombre maximum d'utilisateurs à retourner (par défaut : 5).
     * @return User[] Liste des derniers utilisateurs.
     */
    public function findLastUsers(int $limit = 5): array
    {
        $sql = "SELECT * FROM users ORDER BY date_inscription DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[] = new User(
                $row['id_user'],
                $row['pseudo'],
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['mot_de_passe'],
                $row['date_inscription'],
                $row['d_nee']
            );
        }
        return $users;
    }

    /**
     * Bannit un utilisateur en mettant à jour le champ 'banned' à 1.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return bool Vrai si l'opération a réussi, sinon faux.
     */
    public function banUser(int $userId): bool
    {
        $sql = "UPDATE users SET banned = 1 WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }

    /**
     * Débannit un utilisateur en mettant à jour le champ 'banned' à 0.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return bool Vrai si l'opération a réussi, sinon faux.
     */
    public function unbanUser(int $userId): bool
    {
        $sql = "UPDATE users SET banned = 0 WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }
}
