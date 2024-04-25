<?php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Utilisateurs;
use App\Entity\Reclamation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Solution
 *
 * @ORM\Table(name="solution", indexes={
 *     @ORM\Index(name="utilisateur_id", columns={"utilisateur_id"}),
 *     @ORM\Index(name="reclamation_id", columns={"reclamation_id"}) 
 * })
 * @ORM\Entity
 */
class Solution
{
    /**
     * @var int
     *
     * @ORM\Column(name="SolutionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $solutionid;

/**
 * @ORM\ManyToOne(targetEntity="App\Entity\Reclamation")
 * @ORM\JoinColumn(name="reclamation_id", referencedColumnName="ReclamationID", nullable=false)
 */
private $reclamation;


    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=255, nullable=true, options={"default"="pending"})
     */
    private $status = 'pending';

   /**
 * @ORM\Column(name="RefundAmount", type="float", precision=10, scale=2, nullable=true)
 * @Assert\Type(
 *     type="float",
 *     message="The value {{ value }} is not a valid {{ type }}.",
 *     groups={"active_refund"}
 * )
 * @Assert\NotBlank(
 *     message="Refund amount cannot be blank.",
 *     groups={"active_refund"}
 * )
 */
private $refundamount;
/**
 * @ORM\Column(name="AdminFeedback", type="text", length=65535, nullable=false)
 * @Assert\NotBlank(message="Admin feedback is required.")
 */
private $adminfeedback;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DateResolved", type="datetime", nullable=false)
     */
    private $dateresolved;
    /**
 * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
 */
private $utilisateurId;

    /**
 * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="solutions")
 * @ORM\JoinColumn(nullable=false, name="utilisateur_id", referencedColumnName="utilisateur_id")
 */
private $utilisateur;
    public function getSolutionId(): ?int
    {
        return $this->solutionid;
    }

    // Setter for SolutionID
    public function setSolutionId(int $solutionid): self
    {
        $this->solutionid = $solutionid;
        return $this;
    }


    // Getter for UtilisateurId
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    // Setter for UtilisateurId
    public function setUtilisateurId(int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;
        return $this;
    }

    // Getter for Status
    public function getStatus(): ?string
    {
        return $this->status;
    }

    // Setter for Status
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    // Getter for RefundAmount
    public function getRefundAmount(): ?float
    {
        return $this->refundamount;
    }

    // Setter for RefundAmount
    public function setRefundAmount(?float $refundamount): self
    {
        $this->refundamount = $refundamount;
        return $this;
    }

    // Getter for AdminFeedback
    public function getAdminFeedback(): ?string
    {
        return $this->adminfeedback;
    }

    // Setter for AdminFeedback
    public function setAdminFeedback(?string $adminfeedback): self
    {
        $this->adminfeedback = $adminfeedback;
        return $this;
    }

    // Getter for DateResolved
    public function getDateResolved(): ?\DateTimeInterface
    {
        return $this->dateresolved;
    }

    // Setter for DateResolved
    public function setDateResolved(?\DateTimeInterface $dateresolved): self
    {
        $this->dateresolved = $dateresolved;
        return $this;
    }

/**
 * Get the value of reclamation
 *
 * @return \App\Entity\Reclamation|null
 */
public function getReclamation(): ?Reclamation
{
    return $this->reclamation;
}

/**
 * Set the value of reclamation
 *
 * @param \App\Entity\Reclamation|null $reclamation
 *
 * @return self
 */
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
