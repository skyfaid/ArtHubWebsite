<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "oeuvre")]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le titre ne doit pas être vide.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le titre doit contenir au moins 3 caractères.", maxMessage: "Le titre ne peut pas dépasser 255 caractères.")]
    private ?string $titre = null;

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La description ne doit pas être vide.")]
    #[Assert\Length(min: 3, max: 2000, minMessage: "La description doit contenir au moins 3 caractères.", maxMessage: "La description ne doit pas dépasser 2000 caractères.")]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La disponibilité ne doit pas être vide.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "La disponibilité doit contenir au moins 3 caractères.", maxMessage: "La disponibilité ne peut pas dépasser 255 caractères.")]
    private ?string $disponibilite = null;

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): self
    {
        $this->disponibilite = $disponibilite;
        return $this;
    }

    #[ORM\Column(type: "float", precision: 10, scale: 0)]
    #[Assert\NotBlank(message: "Le champ prix est obligatoire.")]
    #[Assert\Positive(message: "Le prix doit être positif.")]
    private ?float $prix = null;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date de création est obligatoire.")]
    #[Assert\Type(type: "\DateTimeInterface", message: "La date n'est pas valide.")]
    private ?\DateTimeInterface $datecreation = null;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDateCreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;
        return $this;
    }

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le type ne doit pas être vide.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le type doit contenir au moins 3 caractères.", maxMessage: "Le type ne peut pas dépasser 255 caractères.")]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: "string", length: 255)]
    private ?string $posterurl = null;

    public function getPosterUrl(): ?string
    {
        return $this->posterurl;
    }

    public function setPosterUrl(string $posterurl): self
    {
        $this->posterurl = $posterurl;
        return $this;
    }
}
