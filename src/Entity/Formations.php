<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "formations")]
#[ORM\Entity]
class Formations
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $nbrParticipants;

    #[ORM\Column(type: "string", length: 255)]
    private string $lien;

    #[ORM\Column(type: "date")]
    private \DateTime $dateDebut;

    #[ORM\Column(type: "date")]
    private \DateTime $dateFin;

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getNbrParticipants(): int
    {
        return $this->nbrParticipants;
    }

    public function getLien(): string
    {
        return $this->lien;
    }

    public function getDateDebut(): \DateTime
    {
        return $this->dateDebut;
    }

    public function getDateFin(): \DateTime
    {
        return $this->dateFin;
    }

    // Setters

    public function setNbrParticipants(int $nbrParticipants): void
    {
        $this->nbrParticipants = $nbrParticipants;
    }

    public function setLien(string $lien): void
    {
        $this->lien = $lien;
    }

    public function setDateDebut(\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin(\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }
}
