<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpinsRepository;


#[ORM\Entity(repositoryClass: SpinsRepository::class)]
class Spins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "utilisateur_id", nullable: false)]
    private ?Utilisateurs $utilisateur;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $lastSpin = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastSpin(): ?\DateTimeInterface
    {
        return $this->lastSpin;
    }

    public function setLastSpin(\DateTimeInterface $lastSpin): self
    {
        $this->lastSpin = $lastSpin;
        return $this;
    }
}
