<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Utilisateurs;
use App\Entity\Reclamation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "solution", indexes: [
    new ORM\Index(name: "utilisateur_id", columns: ["utilisateur_id"]),
    new ORM\Index(name: "reclamation_id", columns: ["reclamation_id"]) 
])]
#[ORM\Entity]
class Solution
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "SolutionID", type: "integer", nullable: false)]
    private $solutionid;

    #[ORM\OneToOne(targetEntity: Reclamation::class, inversedBy: "solution")]
    #[ORM\JoinColumn(name: "reclamation_id", referencedColumnName: "ReclamationID", nullable: false)]
    private $reclamation;

    #[ORM\Column(name: "Status", type: "string", length: 255, nullable: true, options: ["default" => "pending"])]
    private $status = 'pending';

    #[ORM\Column(name: "RefundAmount", type: "float", precision: 10, scale: 2, nullable: true)]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid {{ type }}.", groups: ["active_refund"])]
    #[Assert\NotBlank(message: "Refund amount cannot be blank.", groups: ["active_refund"])]
    private $refundamount;

    #[ORM\Column(name: "AdminFeedback", type: "text", length: 65535, nullable: false)]
    #[Assert\NotBlank(message: "Admin feedback is required.")]
    private $adminfeedback;

    #[ORM\Column(name: "DateResolved", type: "datetime", nullable: true)]
    private $dateresolved;

    #[ORM\Column(name: "utilisateur_id", type: "integer", nullable: false)]
    private $utilisateurId;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: "solutions")]
    #[ORM\JoinColumn(nullable: false, name: "utilisateur_id", referencedColumnName: "utilisateur_id")]
    private $utilisateur;

    public function getSolutionId(): ?int
    {
        return $this->solutionid;
    }

    public function setSolutionId(int $solutionid): self
    {
        $this->solutionid = $solutionid;
        return $this;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getRefundAmount(): ?float
    {
        return $this->refundamount;
    }

    public function setRefundAmount(?float $refundamount): self
    {
        $this->refundamount = $refundamount;
        return $this;
    }

    public function getAdminFeedback(): ?string
    {
        return $this->adminfeedback;
    }

    public function setAdminFeedback(?string $adminfeedback): self
    {
        $this->adminfeedback = $adminfeedback;
        return $this;
    }

    public function getDateResolved(): ?\DateTimeInterface
    {
        return $this->dateresolved;
    }

    public function setDateResolved(?\DateTimeInterface $dateresolved): self
    {
        $this->dateresolved = $dateresolved;
        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;
        return $this;
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
}
