<?php

namespace App\Entity;

use App\Repository\EvenementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementsRepository::class)]
class Evenements
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Event name is required")]
    private ?string $nom = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "Start date is required")]
    private ?\DateTime $datedebut;

    #[ORM\Column(type: "date", nullable: false)]
    #[Assert\GreaterThanOrEqual(propertyPath: "datedebut", message: "End date must be after start date")]
    private ?\DateTime $datefin = null;

    
    #[ORM\Column(length: 255 )]
    #[Assert\NotBlank(message: "Location is required")]
    private ?string $lieu = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Event type is required")]
    private ?string $type = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: "nombrePlaces", type: "integer")]
    #[Assert\NotBlank(message: "Number of places is required")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Number of places must be non-negative")]
    private ?int $nombreplaces;

    #[ORM\Column(name: "nombreParticipants", type: "integer", nullable: true)]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Number of participants must be non-negative")]
    private ?int $nombreparticipants = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $posterurl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videourl = null;
    
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isExclusive = false;

    public function __construct()
    {
        $this->datedebut = new \DateTime(); // Default to the current time
    }
// Getter and Setter for isExclusive
public function getIsExclusive(): ?bool
{
    return $this->isExclusive; 
}

public function setIsExclusive(bool $isExclusive): self
{
    $this->isExclusive = $isExclusive;
    return $this;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getDatedebut(): ?\DateTime
    {
        return $this->datedebut;
    }

    public function getDatefin(): ?\DateTime
    {
        return $this->datefin;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getNombreplaces(): ?int
    {
        return $this->nombreplaces;
    }

    public function getNombreparticipants(): ?int
    {
        return $this->nombreparticipants;
    }

    public function getPosterurl(): ?string
    {
        return $this->posterurl;
    }

    public function getVideourl(): ?string
    {
        return $this->videourl;
    }

    public function setNom(?string $nom): self
{
    $this->nom = $nom;
    return $this;
}

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function setDatefin(?\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setNombreplaces(?int $nombreplaces): static
    {
        $this->nombreplaces = $nombreplaces;

        return $this;
    }

    public function setNombreparticipants(?int $nombreparticipants): static
    {
        $this->nombreparticipants = $nombreparticipants;

        return $this;
    }

    public function setPosterurl(?string $posterurl): static
    {
        $this->posterurl = $posterurl;

        return $this;
    }

    public function setVideourl(?string $videourl): static
    {
        $this->videourl = $videourl;

        return $this;
    }

    public function isIsExclusive(): ?bool
    {
        return $this->isExclusive;
    }

}
