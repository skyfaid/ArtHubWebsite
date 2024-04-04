<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateurs", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="pseudo", columns={"pseudo"}),
 *     @ORM\UniqueConstraint(name="email", columns={"email"})
 * })
 */
class Utilisateurs implements UserInterface

{
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    
    public function getRoles()
    {
        return [$this->role];
    }

    public function getPassword()
    {
        return $this->motDePasseHash;
    }

    public function getSalt()
    {
        
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

   
    /**
     * @ORM\Column(name="utilisateur_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $utilisateurId;

    /**
     * @ORM\Column(name="pseudo", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Pseudo cannot be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z][a-zA-Z0-9]*$/",
     *     message="Pseudo must start with a letter and contain only alphanumeric characters."
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="First name cannot be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="First name must contain only letters."
     * )
     * @Assert\Length(
     *     max=20,
     *     maxMessage="First name cannot exceed 20 characters."
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Last name cannot be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Last name must contain only letters."
     * )
     * @Assert\Length(
     *     max=20,
     *     maxMessage="Last name cannot exceed 20 characters."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Email cannot be blank.")
     * @Assert\Email(message="The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(name="mot_de_passe_hash", type="string", length=255, nullable=false)
     */
    private $motDePasseHash;

    /**
     * @ORM\Column(name="role", type="string", length=50, nullable=true, options={"default"="user"})
     */
    private $role;

    /**
     * @ORM\Column(name="date_inscription", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateInscription;

    /**
     * @ORM\Column(name="derniere_connexion", type="datetime", nullable=true)
     */
    private $derniereConnexion;

    /**
     * @ORM\Column(name="url_image_profil", type="string", length=255, nullable=true)
     */
    private $urlImageProfil;

    /**
     * @ORM\Column(name="estActif", type="boolean", nullable=true)
     */
    private $estactif;

    /**
     * @ORM\Column(name="gender", type="string", length=20, nullable=true)
        * @Assert\NotBlank(message="Gender cannot be blank.")
     * @Assert\Choice(choices={"male", "female", "other"}, message="Choose a valid gender.")
     */
    private $gender;

    /**
     * @ORM\Column(name="reset_code", type="string", length=255, nullable=true)
     
     */
    private $resetCode;

    /**
     * @ORM\Column(name="phone_number", type="string", length=20, nullable=true)
      * @Assert\NotBlank(message="Phone number cannot be blank.")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(name="facial_data_hash", type="string", length=255, nullable=true)
     */
    private $facialDataHash;

    // Add your getters and setters here
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    // Repeat for other properties...
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasseHash(): ?string
    {
        return $this->motDePasseHash;
    }

    public function setMotDePasseHash(string $motDePasseHash): self
    {
        $this->motDePasseHash = $motDePasseHash;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function getDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(?\DateTimeInterface $derniereConnexion): self
    {
        $this->derniereConnexion = $derniereConnexion;
        return $this;
    }

    public function getUrlImageProfil(): ?string
    {
        return $this->urlImageProfil;
    }

    public function setUrlImageProfil(?string $urlImageProfil): self
    {
        $this->urlImageProfil = $urlImageProfil;
        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->estactif;
    }

    public function setEstActif(?bool $estactif): self
    {
        $this->estactif = $estactif;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): self
    {
        $this->resetCode = $resetCode;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getFacialDataHash(): ?string
    {
        return $this->facialDataHash;
    }

    public function setFacialDataHash(?string $facialDataHash): self
    {
        $this->facialDataHash = $facialDataHash;
        return $this;
    }   
}
