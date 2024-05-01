<?php
namespace App\Entity;

use App\Repository\EventAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventAccessRepository::class)]
#[ORM\Table(name: "event_accesses")]
class EventAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name:"utilisateur_id", referencedColumnName:"utilisateur_id", nullable: false)]
    private ?Utilisateurs $user = null;
    

    #[ORM\ManyToOne(targetEntity: Evenements::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evenements $event = null;

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Utilisateurs
    {
        return $this->user;
    }

    public function getEvent(): ?Evenements
    {
        return $this->event;
    }

    // Setters
    public function setUser(?Utilisateurs $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setEvent(?Evenements $event): self
    {
        $this->event = $event;
        return $this;
    }
}
