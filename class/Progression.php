<?php

namespace App\Classes;

/**
 * Classe Progression
 */
class Progression
{
    private ?int $id_progression;
    private ?int $id_user;       
    private ?int $id_enigme;     
    private string $etat;        
    private int $temps_pris;     
    private string $date_resolution; 

    /**
     * Constructeur
     *
     * @param int|null $id_progression
     * @param int|null $id_user
     * @param int|null $id_enigme
     * @param string   $etat
     * @param int      $temps_pris
     * @param string   $date_resolution
     */
    public function __construct(
        ?int $id_progression,
        ?int $id_user,
        ?int $id_enigme,
        string $etat,
        int $temps_pris,
        string $date_resolution
    ) {
        $this->id_progression  = $id_progression;
        $this->id_user         = $id_user;
        $this->id_enigme       = $id_enigme;
        $this->etat            = $etat;
        $this->temps_pris      = $temps_pris;
        $this->date_resolution = $date_resolution;
    }

    public function __toString(): string
    {
        return "[Progression #{$this->id_progression}] user:{$this->id_user}, enigme:{$this->id_enigme}, etat:{$this->etat}";
    }

    
    public function getIdProgression(): ?int  { return $this->id_progression; }
    public function getIdUser(): ?int        { return $this->id_user; }
    public function getIdEnigme(): ?int      { return $this->id_enigme; }
    public function getEtat(): string        { return $this->etat; }
    public function getTempsPris(): int      { return $this->temps_pris; }
    public function getDateResolution(): string { return $this->date_resolution; }

    
    public function setEtat(string $etat): void
    {
        $this->etat = $etat;
    }

    public function setTempsPris(int $t): void
    {
        $this->temps_pris = $t;
    }

    public function setDateResolution(string $date): void
    {
        $this->date_resolution = $date;
    }
}
