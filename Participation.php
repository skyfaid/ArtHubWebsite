<?php


namespace App\Entity;
use App\Entity\Activite;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation", indexes={@ORM\Index(name="id_activite", columns={"id_activite"}), @ORM\Index(name="utilisateur_id", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_participation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idParticipation;

    /**
     * @var int
     *
     * @ORM\Column(name="id_activite", type="integer", nullable=false)
     */
    private $idActivite;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     */
    private $utilisateurId;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="participation_date", type="date", nullable=false)
     */
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
