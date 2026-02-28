<?php

namespace App\Classes;

/**
 * Classe Form
 */
class Form
{
    private ?int $id_form;
    private ?int $id_user;    
    private string $nom;
    private string $email;
    private string $sujet;
    private string $message;
    private string $date_envoi;

    /**
     * Constructeur
     *
     * @param int|null $id_form
     * @param int|null $id_user
     * @param string   $nom
     * @param string   $email
     * @param string   $sujet
     * @param string   $message
     * @param string   $date_envoi
     */
    public function __construct(
        ?int $id_form,
        ?int $id_user,
        string $nom,
        string $email,
        string $sujet,
        string $message,
        string $date_envoi
    ) {
        $this->id_form   = $id_form;
        $this->id_user   = $id_user;
        $this->nom       = $nom;
        $this->email     = $email;
        $this->sujet     = $sujet;
        $this->message   = $message;
        $this->date_envoi= $date_envoi;
    }

    public function __toString(): string
    {
        return "[Form #{$this->id_form}] De: {$this->nom}, Sujet: {$this->sujet}";
    }

    
    public function getIdForm(): ?int    { return $this->id_form; }
    public function getIdUser(): ?int    { return $this->id_user; }
    public function getNom(): string     { return $this->nom; }
    public function getEmail(): string   { return $this->email; }
    public function getSujet(): string   { return $this->sujet; }
    public function getMessage(): string { return $this->message; }
    public function getDateEnvoi(): string { return $this->date_envoi; }

    
    public function setNom(string $n): void
    {
        $this->nom = $n;
    }
    public function setEmail(string $e): void
    {
        $this->email = $e;
    }
    public function setSujet(string $s): void
    {
        $this->sujet = $s;
    }
    public function setMessage(string $m): void
    {
        $this->message = $m;
    }
}
