<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sortie", inversedBy="utilisateurs")
     */
    private $idSortie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="organisateur", orphanRemoval=true)
     */
    private $sortieOrg;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="utilisateursSite")
     */
    private $site;

    public function __construct()
    {
        $this->idSortie = new ArrayCollection();
        $this->sortieOrg = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }


    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAdministrateur(): bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getIdSortie(): Collection
    {
        return $this->idSortie;
    }

    public function addIdSortie(Sortie $idSortie): self
    {
        if (!$this->idSortie->contains($idSortie)) {
            $this->idSortie[] = $idSortie;
        }

        return $this;
    }

    public function removeIdSortie(Sortie $idSortie): self
    {
        if ($this->idSortie->contains($idSortie)) {
            $this->idSortie->removeElement($idSortie);
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortieOrg(): Collection
    {
        return $this->sortieOrg;
    }

    public function addSortieOrg(Sortie $sortieOrg): self
    {
        if (!$this->sortieOrg->contains($sortieOrg)) {
            $this->sortieOrg[] = $sortieOrg;
            $sortieOrg->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrg(Sortie $sortieOrg): self
    {
        if ($this->sortieOrg->contains($sortieOrg)) {
            $this->sortieOrg->removeElement($sortieOrg);
            // set the owning side to null (unless already changed)
            if ($sortieOrg->getOrganisateur() === $this) {
                $sortieOrg->setOrganisateur(null);
            }
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }
}
