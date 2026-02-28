<?php

namespace App\Front\Model;

use PDO;
use App\Classes\User;

/**
 * Classe UserManager (Front)
 *
 * Gère les interactions avec la table "users" pour la partie front du site.
 * Permet de rechercher un utilisateur, vérifier son mot de passe, l'enregistrer,
 * et gérer son bannissement.
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
     * Retourne un utilisateur par son pseudo
     *
     * @param string $pseudo Pseudo de l'utilisateur
     * @return User|null L'utilisateur correspondant ou null si inexistant
     */
    public function findByPseudo(string $pseudo): ?User
    {
        $sql = "SELECT * FROM users WHERE pseudo = :pseudo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pseudo' => $pseudo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
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

    /**
     * Vérifie si le mot de passe fourni correspond au pseudo
     *
     * @param string $pseudo Pseudo de l'utilisateur
     * @param string $plainPassword Mot de passe en clair saisi
     * @return bool True si le mot de passe est correct, sinon false
     */
    public function checkPassword(string $pseudo, string $plainPassword): bool
    {
        $user = $this->findByPseudo($pseudo);
        if (!$user) {
            return false;
        }
        return password_verify($plainPassword, $user->getMotDePasse());
    }

    /**
     * Insère un nouvel utilisateur dans la base de données
     *
     * @param User $u Utilisateur à insérer
     * @return bool True si l'utilisateur a été créé avec succès, sinon false
     */
    public function create(User $u): bool
    {
        $sql = "INSERT INTO users 
                (pseudo, nom, prenom, email, mot_de_passe, d_nee)
                VALUES 
                (:pseudo, :nom, :prenom, :email, :mdp, :dnee)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pseudo' => $u->getPseudo(),
            'nom'    => $u->getNom(),
            'prenom' => $u->getPrenom(),
            'email'  => $u->getEmail(),
            'mdp'    => $u->getMotDePasse(),
            'dnee'   => $u->getDateNaissance(),
        ]);
    }

    /**
     * Bannit un utilisateur en mettant à jour le champ 'banned' à 1.
     *
     * @param int $userId Identifiant de l'utilisateur
     * @return bool True si l'opération a réussi, sinon false
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
     * @param int $userId Identifiant de l'utilisateur
     * @return bool True si l'opération a réussi, sinon false
     */
    public function unbanUser(int $userId): bool
    {
        $sql = "UPDATE users SET banned = 0 WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }
}
