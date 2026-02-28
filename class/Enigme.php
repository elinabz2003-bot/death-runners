<?php

namespace App\Classes;

/**
 * Classe Enigme
 */
class Enigme
{
    private ?int $id_enigme;
    private string $titre;
    private string $contenu;
    private string $reponse;
    private string $difficulte;

    /**
     * Constructeur de la classe Enigme
     *
     * @param int|null $id_enigme Identifiant de l'énigme (null si non défini)
     * @param string $titre Titre de l'énigme
     * @param string $contenu Contenu ou question de l'énigme
     * @param string $reponse Réponse à l'énigme
     * @param string $difficulte Difficulté de l'énigme (Facile, Moyen, Difficile)
     */
    public function __construct(
        ?int $id_enigme,
        string $titre,
        string $contenu,
        string $reponse,
        string $difficulte
    ) {
        $this->id_enigme  = $id_enigme;
        $this->titre      = $titre;
        $this->contenu    = $contenu;
        $this->reponse    = $reponse;
        $this->difficulte = $difficulte;
    }

    /**
     * Représentation textuelle de l'objet Enigme
     *
     * @return string Format [Enigme #id] titre
     */
    public function __toString(): string
    {
        return "[Enigme #{$this->id_enigme}] {$this->titre}";
    }

    
    public function getIdEnigme(): ?int             { return $this->id_enigme; }
    public function getTitre(): string             { return $this->titre; }
    public function getContenu(): string           { return $this->contenu; }
    public function getReponse(): string           { return $this->reponse; }
    public function getDifficulte(): string        { return $this->difficulte; }

    
    public function setIdEnigme(int $id): void     { $this->id_enigme = $id; }
    public function setTitre(string $t): void      { $this->titre = $t; }
    public function setContenu(string $c): void    { $this->contenu = $c; }
    public function setReponse(string $r): void    { $this->reponse = $r; }
    public function setDifficulte(string $d): void { $this->difficulte = $d; }
}
