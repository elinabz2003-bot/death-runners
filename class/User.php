<?php

namespace App\Classes;

/**
 * Classe User
 */
class User
{
    private ?int $id_user;
    private string $pseudo;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $mot_de_passe;
    private string $date_inscription;
    private string $d_nee;

    /**
     * Constructeur de la classe User
     *
     * @param int|null $id_user Identifiant de l'utilisateur (null si non défini)
     * @param string $pseudo Nom d'utilisateur
     * @param string $nom Nom de famille
     * @param string $prenom Prénom
     * @param string $email Adresse e-mail
     * @param string $mot_de_passe Mot de passe (haché)
     * @param string $date_inscription Date d'inscription au format YYYY-MM-DD
     * @param string $d_nee Date de naissance
     */
    public function __construct(
        ?int $id_user,
        string $pseudo,
        string $nom,
        string $prenom,
        string $email,
        string $mot_de_passe,
        string $date_inscription,
        string $d_nee
    ) {
        $this->id_user          = $id_user;
        $this->pseudo           = $pseudo;
        $this->nom              = $nom;
        $this->prenom           = $prenom;
        $this->email            = $email;
        $this->mot_de_passe     = $mot_de_passe;
        $this->date_inscription = $date_inscription;
        $this->d_nee            = $d_nee;
    }

    /**
     * Représentation textuelle de l'utilisateur
     *
     * @return string Format [User #id] pseudo
     */
    public function __toString(): string
    {
        return "[User #{$this->id_user}] {$this->pseudo}";
    }

    public function getIdUser(): int               { return $this->id_user; }
    public function getPseudo(): string            { return $this->pseudo; }
    public function getNom(): string               { return $this->nom; }
    public function getPrenom(): string            { return $this->prenom; }
    public function getEmail(): string             { return $this->email; }
    public function getMotDePasse(): string        { return $this->mot_de_passe; }
    public function getDateInscription(): string   { return $this->date_inscription; }
    public function getDateNaissance(): string     { return $this->d_nee; }
}
