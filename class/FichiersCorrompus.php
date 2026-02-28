<?php

namespace App\Classes;

/**
 * Classe FichiersCorrompus
 *
 * Représente les fichiers liés à une énigme, que l'on doit "réparer".
 */
class FichiersCorrompus
{
    private ?int $id_fichier;
    private ?int $id_enigme;   
    private string $nom_fichier;
    private string $contenu;
    private string $statut;    

    /**
     * Constructeur
     *
     * @param int|null $id_fichier
     * @param int|null $id_enigme
     * @param string   $nom_fichier
     * @param string   $contenu
     * @param string   $statut
     */
    public function __construct(
        ?int $id_fichier,
        ?int $id_enigme,
        string $nom_fichier,
        string $contenu,
        string $statut
    ) {
        $this->id_fichier   = $id_fichier;
        $this->id_enigme    = $id_enigme;
        $this->nom_fichier  = $nom_fichier;
        $this->contenu      = $contenu;
        $this->statut       = $statut;
    }

    public function __toString(): string
    {
        return "[FichierCorrompu #{$this->id_fichier}] {$this->nom_fichier} (statut: {$this->statut})";
    }

    
    public function getIdFichier(): ?int     { return $this->id_fichier; }
    public function getIdEnigme(): ?int      { return $this->id_enigme; }
    public function getNomFichier(): string  { return $this->nom_fichier; }
    public function getContenu(): string     { return $this->contenu; }
    public function getStatut(): string      { return $this->statut; }

    
    public function setNomFichier(string $n): void
    {
        $this->nom_fichier = $n;
    }
    public function setContenu(string $c): void
    {
        $this->contenu = $c;
    }
    public function setStatut(string $s): void
    {
        $this->statut = $s;
    }
}
