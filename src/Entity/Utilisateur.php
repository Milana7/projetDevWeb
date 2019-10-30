<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo n'est pas disponible !")
 *
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Votre pseudo doit contenir minimum {{ limit }} caractères !",
     *      maxMessage = "Votre pseudo doit contenir au maximum {{ limit }} caractères !"
     * )
     * @Assert\Regex(pattern="/^[a-z0-9_-]+$/i", message="Votre pseudo doit seulement contenir des lettres, des chiffres, des underscores et des tirets !")
     *
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $pseudo;

    /**
     * @Assert\Regex(pattern="/^[a-z]+$/i", message="Votre nom doit seulement contenir des lettres !")
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\Regex(pattern="/^[a-z]+$/i", message="Votre prenom doit seulement contenir des lettres !")
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @Assert\Regex(pattern="/^[0-9]+$/i", message="Votre numéro de téléphone doit seulement contenir des chiffres !")
     * @ORM\Column(type="string", length=10)
     */
    private $telephone;

    /**
     * @Assert\NotBlank(message="Votre E-mail ne doit pas être vide !")
     * @Assert\Email(message="Votre E-mail n'est pas valide !")
     *
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $mail;

    /**
     * @Assert\NotBlank(message="Votre mot de passe ne peut pas être vide !")
     * @Assert\Length(
     *      min = 6,
     *      max = 4096,
     *      minMessage = "Votre mot de passe doit contenir minimum {{ limit }} caractères !",
     *      maxMessage = "Votre mot de passe doit contenir au maximum {{ limit }} caractères !"
     * )
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="utilisateursSite", fetch="EAGER")
     */
    private $site;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $file;

    /**
     * @Assert\File(
     *     maxSize = "2Mi",
     *     mimeTypes={ "image/png", "image/jpeg" },
     *     uploadErrorMessage="Le fichier n'a pas été téléchargé !",
     *     maxSizeMessage ="Le fichier est trop lourd : {{ limit }} {{ suffix }} !",
     * )
     */
    private $fileTemp;

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

    public function getPassword()
    {
        return $this->password;
    }


    public function setPassword($password): void
    {
        $this->password = $password;
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

    public function getSite()
    {
        return $this->site;
    }

    public function setSite($site): void
    {
        $this->site = $site;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getFileTemp()
    {
        return $this->fileTemp;
    }

    public function setFileTemp($fileTemp): void
    {
        $this->fileTemp = $fileTemp;
    }

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
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): ?self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role): ?self
    {

        $roles = $this->roles;
        $roles[] = $role;
        $this->roles = array_unique($roles);

        return $this;
    }

    public function getSalt()
    {
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */

    public function getUsername()
    {
        return $this->getMail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
}
