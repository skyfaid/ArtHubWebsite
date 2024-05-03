<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]

#[ORM\Table(name: "activite", indexes: [new ORM\Index(name: "id_activite", columns: ["id_activite"])])]

class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_activite", type: "integer", nullable: false)]
    private $idActivite;

    #[ORM\Column(name: "nom_act", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom de l'activité est requis.")]
    #[Assert\Length(max: 25, maxMessage: "Le nom de l'activité ne doit pas dépasser {{ limit }} caractères.")]
    #[Assert\Type(type: "string", message: "Le nom de l'activité doit être une chaîne de caractères.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom de l'activité ne doit contenir que des lettres et des espaces.")]
    private $nomAct;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "Start date is required")]
    private ?DateTime $datedebut;

    #[ORM\Column(type: "date", nullable: false)]
    #[Assert\GreaterThanOrEqual(propertyPath: "datedebut", message: "End date must be after start date")]
    private ?DateTime $datefin = null;

    #[ORM\Column(name: "Lieu", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le lieu est requis.")]
    #[Assert\Type(type: "string", message: "Le lieu doit être une chaîne de caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+ .+, .+, [A-Za-z]+$/", message: "Le lieu doit être sous forme d'une adresse valide.")]
    private $lieu;

    #[ORM\Column(name: "nbre_places", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Le nombre de places est requis.")]
    #[Assert\Type(type: "integer", message: "Le nombre de places doit être un entier.")]
    #[Assert\Range(min: 0, max: 100, minMessage: "Le nombre de places doit être supérieur ou égal à {{ limit }}.", maxMessage: "Le nombre de places doit être inférieur ou égal à {{ limit }}.")]
    private $nbrePlaces;

    #[ORM\Column(name: "type_act", type: "string", length: 255, nullable: false)]
    private $typeAct;

    #[ORM\Column(name: "posterUrl", type: "string", length: 255, nullable: false)]
    private $posterurl;

    // Getters and setters...
    public function getIdActivite(): ?int
    {
        return $this->idActivite;
    }

    public function getNomAct(): ?string
    {
        return $this->nomAct;
    }

    public function setNomAct(string $nomAct): self
    {
        $this->nomAct = $nomAct;
        return $this;
    }

    public function getDatedebut(): ?DateTime
    {
        return $this->datedebut;
    }

    public function setDatedebut(?DateTime $datedebut): self
    {
        $this->datedebut = $datedebut;
        return $this;
    }

    public function getDatefin(): ?DateTime
    {
        return $this->datefin;
    }

    public function setDatefin(?DateTime $datefin): self
    {
        $this->datefin = $datefin;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getNbrePlaces(): ?int
    {
        return $this->nbrePlaces;
    }

    public function setNbrePlaces(int $nbrePlaces): self
    {
        $this->nbrePlaces = $nbrePlaces;
        return $this;
    }

    public function getTypeAct(): ?string
    {
        return $this->typeAct;
    }

    public function setTypeAct(string $typeAct): self
    {
        $this->typeAct = $typeAct;
        return $this;
    }

    public function getPosterurl(): ?string
    {
        return $this->posterurl;
    }

    public function setPosterurl(string $posterurl): self
    {
        $this->posterurl = $posterurl;
        return $this;
    }
}
