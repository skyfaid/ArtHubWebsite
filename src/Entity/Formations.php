<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formations
 *
 * @ORM\Table(name="formations")
 * @ORM\Entity
 */
class Formations
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_participants", type="integer", nullable=false)
     */
    private int $nbrParticipants;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255, nullable=false)
     */
    private string $lien;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private \DateTime $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
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
