<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 5,
     *     max = 50,
     *     minMessage = "Le nom de la sortie doit être composé d'au moins {{ limit }} caractères",
     *     maxMessage = "Le nom de la sortie doit être composé d'au moins {{ limit }} caractères"
     * )
     */
    private $nom;

    /**
     * @var datetime
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank(message="La date de début est obligatoire")
     * @Assert\Type("DateTime")
     * @Assert\DateTime(
     *     format = "d-m-Y H:i",
     *     message = "Le format de la date ne correspond pas à celui attendu (jj/mm/aaaa hh:mm)"
     * )
     * @Assert\Expression(
     *     "value > this.getDateLimiteInscription()",
     *     message = "La date de début de la sortie doit être supérieure à la date de fermeture des inscriptions"
     * )
     * @Assert\GreaterThan(
     *     "now",
     *     message = "La sortie ne peut pas commencer avant sa création"
     * )
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @var datetime
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date limite d'inscription est obligatoire")
     * @Assert\Type("DateTime")
     * @Assert\DateTime(
     *     format = "d-m-Y H:i",
     *     message = "Le format de la date ne correspond pas à celui attendu (jj/mm/aaaa hh:mm)"
     * )
     * @Assert\GreaterThan(
     *     "now",
     *     message = "Les inscriptions ne peuvent être fermées avant la création de la sortie"
     * )
     */
    protected $dateLimiteInscription;

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

    private $idLieu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="idSortie")
     */
    private $utilisateurs; //participants à la sortie

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="sortieOrg")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $motif;


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
     * @throws \Exception
     */
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
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
     return $this->dateLimiteInscription;
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

    /**
     * @return mixed
     */
    public function getIdLieu()
    {
        return $this->idLieu;
    }

    /**
     * @param mixed $idLieu
     */
    public function setIdLieu(int $idLieu): void
    {
        $this->idLieu = $idLieu;
    }

    /**
     * @return mixed
     */
    public function getLieu(): Lieu
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     */
    public function setLieu(Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     */
    public function setMotif($motif): void
    {
        $this->motif = $motif;
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
