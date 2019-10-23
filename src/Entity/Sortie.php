<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idLieu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="idSortie")
     */
    private $utilisateurs; //participants à la sortie

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="sortieOrg")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getDateHeureDebut()
    {
        if ($this->dateHeureDebut instanceof \DateTime)
            return $this->dateHeureDebut->format('d/m/Y');
    }

    /**
     * @param mixed $dateHeureDebut
     */
    public function setDateHeureDebut($dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     */
    public function setDuree($duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return mixed
     */
    public function getDateLimiteInscription()
    {
        if ($this->dateLimiteInscription instanceof \DateTime)
            return $this->dateLimiteInscription->format('d/m/Y');
    }

    /**
     * @param mixed $dateLimiteInscription
     */
    public function setDateLimiteInscription($dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return mixed
     */
    public function getNbInscriptionsMax()
    {
        return $this->nbInscriptionsMax;
    }

    /**
     * @param mixed $nbInscriptionsMax
     */
    public function setNbInscriptionsMax($nbInscriptionsMax): void
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;
    }

    /**
     * @return mixed
     */
    public function getInfosSortie()
    {
        return $this->infosSortie;
    }

    /**
     * @param mixed $infosSortie
     */
    public function setInfosSortie($infosSortie): void
    {
        $this->infosSortie = $infosSortie;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdLieu(): ?Lieu
    {
        return $this->idLieu;
    }

    public function setIdLieu(?Lieu $idLieu): self
    {
        $this->idLieu = $idLieu;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->addIdSortie($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            $utilisateur->removeIdSortie($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?Utilisateur
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Utilisateur $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }


}
