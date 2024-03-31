<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenements
 *
 * @ORM\Table(name="evenements", uniqueConstraints={@ORM\UniqueConstraint(name="unique_event_constraint", columns={"nom"})})
 * @ORM\Entity
 */
class Evenements
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Event name is required")
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date", nullable=false)
     * @Assert\NotBlank(message="Start date is required")
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datefin", type="date", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="datedebut", message="End date must be after start date")
     */
    private $datefin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Location is required")
     */
    private $lieu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Event type is required")
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombrePlaces", type="integer", nullable=true)
     * @Assert\NotBlank(message="Number of places is required")
     * @Assert\GreaterThanOrEqual(value=0, message="Number of places must be non-negative")
     */
    private $nombreplaces;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombreParticipants", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(value=0, message="Number of participants must be non-negative")
     */
    private $nombreparticipants = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="posterUrl", type="string", length=255, nullable=true)
     */
    private $posterurl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="videoUrl", type="string", length=255, nullable=true)
     */
    private $videourl;

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

}
