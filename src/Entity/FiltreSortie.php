<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FiltreSortie
 * @package App\Entity
 */
class FiltreSortie
{
    private $nomSite;

    private $nomSortie;

    private $dateDebut;

    private $datefin;

    private $mesSortiesOrg;

    private $mesSortiesInscr;

    private $sortiesExpirees;

    private $sortiesNonInscr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSite(): ?string
    {
        return $this->nomSite;
    }

    public function setNomSite(?string $nomSite): self
    {
        $this->nomSite = $nomSite;

        return $this;
    }

    public function getNomSortie(): ?string
    {
        return $this->nomSortie;
    }

    public function setNomSortie(?string $nomSortie): self
    {
        $this->nomSortie = $nomSortie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getMesSortiesOrg(): ?bool
    {
        return $this->mesSortiesOrg;
    }

    public function setMesSortiesOrg(?bool $mesSortiesOrg): self
    {
        $this->mesSortiesOrg = $mesSortiesOrg;

        return $this;
    }

    public function getMesSortiesInscr(): ?bool
    {
        return $this->mesSortiesInscr;
    }

    public function setMesSortiesInscr(?bool $mesSortiesInscr): self
    {
        $this->mesSortiesInscr = $mesSortiesInscr;

        return $this;
    }

    public function getSortiesExpirees(): ?bool
    {
        return $this->sortiesExpirees;
    }

    public function setSortiesExpirees(?bool $sortiesExpirees): self
    {
        $this->sortiesExpirees = $sortiesExpirees;

        return $this;
    }

    public function getSortiesNonInscr(): ?bool
    {
        return $this->sortiesNonInscr;
    }

    public function setSortiesNonInscr(?bool $sortiesNonInscr): self
    {
        $this->sortiesNonInscr = $sortiesNonInscr;

        return $this;
    }
}
