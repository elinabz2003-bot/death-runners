<?php

namespace App\Admin\Model;

use PDO;
use App\Classes\Admin;

/**
 * Classe AdminManager
 *
 * Gère la table "admins" qui liste les comptes administrateurs.
 * Permet notamment de vérifier si un utilisateur est admin,
 * de promouvoir un user en admin, de le dé-promouvoir,
 * ou de récupérer les adresses email de tous les admins.
 */
class AdminManager
{
    /**
     * @var PDO Connexion à la base de données
     */
    private PDO $db;

    /**
     * Constructeur
     *
     * @param PDO $db Instance PDO connectée à la BDD
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $idUser
     * @return bool True si l'utilisateur est admin, sinon false
     */
    public function isAdmin(int $idUser): bool
    {
        $sql = "SELECT id_admin 
                FROM admins 
                WHERE id_user = :uid 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $idUser]);
        $row = $stmt->fetch();
        return ($row !== false);
    }

    /**
     * Ajoute un enregistrement dans `admins` pour un user donné
     *
     * @param int    $idUser       Identifiant de l'utilisateur
     * @param string $pseudo       Pseudo de l'utilisateur
     * @param string $email        Email de l'utilisateur
     * @param string $mot_de_passe Mot de passe
     * @return bool True si l'insertion a réussi, sinon false
     */
    public function createAdmin(int $idUser, string $pseudo, string $email, string $mot_de_passe): bool
    {
        $sql = "INSERT INTO admins (id_user, pseudo, email, mot_de_passe) 
                VALUES (:uid, :pseudo, :email, :mdp)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'uid'    => $idUser,
            'pseudo' => $pseudo,
            'email'  => $email,
            'mdp'    => $mot_de_passe,
        ]);
    }

    /**
     * Supprime l'entrée admin pour un user donné
     *
     * @param int $idUser
     * @return bool True si la suppression a réussi, sinon false
     */
    public function removeAdmin(int $idUser): bool
    {
        $sql = "DELETE FROM admins 
                WHERE id_user = :uid";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['uid' => $idUser]);
    }

    /**
     * Récupère toutes les adresses e-mails des admins
     *
     * @return string[] Tableau contenant les adresses e-mail de tous les admins
     */
    public function getAllAdminEmails(): array
    {
        $stmt = $this->db->query("SELECT email FROM admins");
        $emails = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $emails[] = $row['email'];
        }
        return $emails;
    }
}
