<?php

namespace App\Classes;

/**
 * Classe Admin
 *
 * Représente un compte administrateur rattaché à un utilisateur.
 */
class Admin
{
    private ?int $id_admin;
    private ?int $id_user;       
    private string $pseudo;
    private string $email;
    private string $mot_de_passe;

    /**
     * Constructeur
     *
     * @param int|null $id_admin
     * @param int|null $id_user
     * @param string   $pseudo
     * @param string   $email
     * @param string   $mot_de_passe
     */
    public function __construct(
        ?int $id_admin,
        ?int $id_user,
        string $pseudo,
        string $email,
        string $mot_de_passe
    ) {
        $this->id_admin     = $id_admin;
        $this->id_user      = $id_user;
        $this->pseudo       = $pseudo;
        $this->email        = $email;
        $this->mot_de_passe = $mot_de_passe;
    }

    public function __toString(): string
    {
        return "[Admin #{$this->id_admin}] {$this->pseudo}";
    }

    
    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMotDePasse(): string
    {
        return $this->mot_de_passe;
    }

    
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        $this->mot_de_passe = $motDePasse;
    }
}
