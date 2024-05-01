<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantsRepository::class)]
class Participants
{
    #[ORM\Column(name: "participant_id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $participantId = null;

    #[ORM\Column(name: "utilisateur_id", type: "integer", nullable: true)]
    private ?int $utilisateurId = null;

    #[ORM\ManyToOne(targetEntity: Evenements::class)]
    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id")]
    private $event;

    // Add getters and setters manually
    public function getParticipantId(): ?int
    {
        return $this->participantId;
    }

    public function setParticipantId(?int $participantId): void
    {
        $this->participantId = $participantId;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?int $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent($event): void
    {
        $this->event = $event;
    }
}

