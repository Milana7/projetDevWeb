<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=30,
     *     maxMessage = "Le nom de la sortie doit être composé d'au moins {{ limit }} caractères"
     *
     * )
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="site")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateursSite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif;

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

    /**
     * @return mixed
     */
    public function getIsActif()
    {
        return $this->isActif;
    }

    /**
     * @param mixed $isActif
     */
    public function setIsActif($isActif): void
    {
        $this->isActif = $isActif;
    }



}
