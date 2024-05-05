<?php

namespace App\Entity;

use App\Entity\Utilisateurs;
use App\Repository\ReclamationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
#[ORM\Table(name: "reclamation", indexes: [
    new ORM\Index(name: "fk_utilisateur_id", columns: ["utilisateur_id"]),
    new ORM\Index(name: "fk_oeuvre_id", columns: ["oeuvre_id"])
])]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "ReclamationID", type: "integer")]
    private $ReclamationID;

    #[ORM\Column(name: "utilisateur_id", type: "integer")]
    private $utilisateur_id;

    #[ORM\Column(name: "phoneNumber", type: "string", length: 8, nullable: false)]
    #[Assert\NotBlank(message: "Phone number must not be empty.")]
    #[Assert\Regex(pattern: "/^\d{8}$/", message: "The phone number must be exactly 8 digits.")]
    private $phoneNumber;

    #[ORM\ManyToOne(targetEntity: Oeuvre::class)]
    #[ORM\JoinColumn(name: "oeuvre_id", referencedColumnName: "id", nullable: false)]
    private $oeuvre;

    #[ORM\Column(name: "Description", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Description must not be empty.")]
    private $Description;

    #[ORM\Column(name: "Status", type: "string", length: 255, nullable: true, options: ["default" => "pending"])]
    private $status = 'pending';

    #[ORM\Column(name: "DateSubmitted", type: "datetime", nullable: false)]
    private $datesubmitted;

    #[ORM\Column(name: "productPNG", type: "string", length: 255, nullable: true)]
    private $productpng;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(nullable: false, name: "utilisateur_id", referencedColumnName: "utilisateur_id")]
    private $utilisateur;

    #[ORM\OneToOne(targetEntity: Solution::class, mappedBy: "reclamation", cascade: ["persist", "remove"])]
    private $solution;

    public function getSolution(): ?Solution
    {
        return $this->solution;
    }

    public function setSolution(?Solution $solution): self
    {
        $this->solution = $solution;
        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }

    public function setUtilisateurId(int $utilisateur_id): self
    {
        $this->utilisateur_id = $utilisateur_id;
        return $this;
    }

    public function getReclamationID(): ?int
    {
        return $this->ReclamationID;
    }

    public function setReclamationID(int $ReclamationID): self
    {
        $this->ReclamationID = $ReclamationID;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDateSubmitted(): ?\DateTimeInterface
    {
        return $this->datesubmitted;
    }

    public function setDateSubmitted(\DateTimeInterface $datesubmitted): self
    {
        $this->datesubmitted = $datesubmitted;
        return $this;
    }

    public function getProductPng(): ?string
    {
        return $this->productpng;
    }

    public function setProductPng(?string $productpng): self
    {
        $this->productpng = $productpng;
        return $this;
    }

    public function getOeuvre(): ?Oeuvre
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Oeuvre $oeuvre): self
    {
        $this->oeuvre = $oeuvre;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function __construct()
    {
        $this->datesubmitted = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->oeuvre ? $this->oeuvre->getTitre() . ' - ' . $this->getReclamationID() : '';
    }
}
