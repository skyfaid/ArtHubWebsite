<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateursRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueUserConstraint;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[ORM\Table(name: "utilisateurs", uniqueConstraints: [
    new ORM\UniqueConstraint(name: "pseudo", columns: ["pseudo"]),
    new ORM\UniqueConstraint(name: "email", columns: ["email"])
])]

class Utilisateurs implements UserInterface
{

    public function getRoles(): array
    {
        // Example, assuming $role property is a string. You might need to adjust it based on your application's needs.
        return [$this->role];
    }

    public function getPassword(): string
    {
        // Assuming motDePasseHash is your password field
        return $this->motDePasseHash;
    }

    public function getSalt(): ?string
    {
        // Not needed for modern algorithms but must be included for interface compatibility
        return null;
    }

    public function getUsername(): string
    {
        // Assuming pseudo is your "username" field
        return $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // Not needed in this example
    }

    public function getUserIdentifier(): string
    {
        // This method is new in Symfony 5.3 and should return the identifier used for authentication
        // For example, if you're using email as the unique identifier:
        return $this->getEmail();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $utilisateurId = null;

    #[Assert\NotBlank(message: 'The username cannot be blank')]
    #[UniqueUserConstraint]
    #[Assert\Regex(pattern: '/^[a-zA-Z]{3}[a-zA-Z0-9]*$/', message: 'The username must start with at least 3 alphabetic characters ')]
    #[ORM\Column(type: "string", length: 50)]
    private string $pseudo;

    #[Assert\NotBlank(message: 'The first name is required')]
    #[Assert\Length(max: 20, maxMessage: 'The first name cannot be longer than {{ limit }} characters')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'The first name must contain only alphabetic characters')]
    #[ORM\Column(type: "string", length: 50)]
    private string $prenom;

    #[Assert\NotBlank(message: 'The last name is required')]
    #[Assert\Length(max: 20, maxMessage: 'The last name cannot be longer than {{ limit }} characters')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'The last name must contain only alphabetic characters')]
    #[ORM\Column(type: "string", length: 50)]
    private string $nom;

    #[UniqueUserConstraint]
    #[Assert\NotBlank(message: 'The email address is required')]
    #[Assert\Email(message: 'Please enter a valid email address')]
    #[ORM\Column(type: "string", length: 100)]
    private string $email;

    #[ORM\Column(name: "mot_de_passe_hash", type: "string", length: 255)]
    private string $motDePasseHash;

    #[ORM\Column(type: "string", length: 50, nullable: true, options: ["default" => "user"])]
    private ?string $role = 'user';

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateInscription;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $derniereConnexion = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $urlImageProfil = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $estactif = null;

    #[Assert\NotBlank(message: 'Please select a gender')]
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $resetCode = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $resetCodeExpires;

    #[Assert\NotBlank(message: 'Please provide your phone number')]
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $facialDataHash = null;

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasseHash(): ?string
    {
        return $this->motDePasseHash;
    }

    public function setMotDePasseHash(string $motDePasseHash): static
    {
        $this->motDePasseHash = $motDePasseHash;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(?\DateTimeInterface $derniereConnexion): static
    {
        $this->derniereConnexion = $derniereConnexion;

        return $this;
    }

    public function getUrlImageProfil(): ?string
    {
        return $this->urlImageProfil;
    }

    public function setUrlImageProfil(?string $urlImageProfil): static
    {
        $this->urlImageProfil = $urlImageProfil;

        return $this;
    }

    public function isEstactif(): ?bool
    {
        return $this->estactif;
    }

    public function setEstactif(?bool $estactif): static
    {
        $this->estactif = $estactif;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): static
    {
        $this->resetCode = $resetCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function getResetCodeExpires(): ?\DateTimeInterface
    {
        return $this->resetCodeExpires;
    }

    public function setResetCodeExpires(?\DateTimeInterface $resetCodeExpires): static
    {
        $this->resetCodeExpires = $resetCodeExpires;

        return $this;
    }

    
}
