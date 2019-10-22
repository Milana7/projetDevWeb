<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="site")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateursSite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUtilisateursSite()
    {
        return $this->utilisateursSite;
    }

    /**
     * @param mixed $utilisateursSite
     */
    public function setUtilisateursSite($utilisateursSite): void
    {
        $this->utilisateursSite = $utilisateursSite;
    }

}
