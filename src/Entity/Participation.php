<?php
namespace App\Entity;
use App\Entity\Activite;

use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity]

#[ORM\Table(name: "participation", indexes: [new ORM\Index(name: "utilisateur_id", columns: ["utilisateur_id"])
,new ORM\Index(name: "id_activite", columns: ["id_activite"])])]


class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", name: "id_participation", nullable: false)]
    private $idParticipation;

    #[ORM\Column(type: "integer", name: "id_activite", nullable: false)]
    private $idActivite;

    #[ORM\Column(type: "integer", name: "utilisateur_id", nullable: false)]
    private $utilisateurId;

    #[ORM\Column(type: "integer", name: "score", nullable: false)]
    private $score;

    #[ORM\Column(type: "date", name: "participation_date", nullable: false)]
    private $participationDate;

    // Getters
    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getIdActivite(): ?int
    {
        return $this->idActivite;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getParticipationDate(): ?\DateTime
    {
        return $this->participationDate;
    }

    // Setters
    public function setIdParticipation(int $idParticipation): self
    {
        $this->idParticipation = $idParticipation;
        return $this;
    }

    public function setIdActivite(int $idActivite): self
    {
        $this->idActivite = $idActivite;
        return $this;
    }

    public function setUtilisateurId(int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;
        return $this;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function setParticipationDate(\DateTime $participationDate): self
    {
        $this->participationDate = $participationDate;
        return $this;
    }

}
