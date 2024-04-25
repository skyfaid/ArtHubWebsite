<?php


namespace App\Entity;
use App\Entity\Utilisateurs;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={
 *     @ORM\Index(name="fk_utilisateur_id", columns={"utilisateur_id"}),
 *     @ORM\Index(name="fk_oeuvre_id", columns={"oeuvre_id"})
 * })
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="ReclamationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ReclamationID;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     */
    private $utilisateur_id;

 

    /**
     * @ORM\ManyToOne(targetEntity=Oeuvre::class)
     * @ORM\JoinColumn(name="oeuvre_id", referencedColumnName="id", nullable=false)
     */
    private $oeuvre;

     /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Description must not be empty.")
     */
    private $Description;
    

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=255, nullable=true, options={"default"="pending"})
     */
    private $status = 'pending';

/**
 * @var \DateTime
 *
 * @ORM\Column(name="DateSubmitted", type="datetime", nullable=false)
 */
private $datesubmitted;

    /**
     * @var string|null
     *
     * @ORM\Column(name="productPNG", type="string", length=255, nullable=true)
     */
    private $productpng;
     /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class)
     * @ORM\JoinColumn(nullable=false, name="utilisateur_id", referencedColumnName="utilisateur_id")
     */
    private $utilisateur;




    // Getter and setter for utilisateurId
    public function getUtilisateurId(): int
    {
        return $this->utilisateur_id;
    }
    
    public function setUtilisateurId(int $utilisateur_id): self
    {
        $this->utilisateur_id = $utilisateur_id;
        return $this;
    }
    // Getter and setter for id
    public function getReclamationID(): int
    {
        return $this->ReclamationID;
    }

    public function setReclamationID(int $ReclamationID): self
    {
        $this->ReclamationID = $ReclamationID;
        return $this;
    }

    // Getter and setter for description
    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    // Getter and setter for status
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    // Getter and setter for datesubmitted
    public function getDateSubmitted(): \DateTime
    {
        return $this->datesubmitted;
    }

    public function setDateSubmitted(\DateTime $datesubmitted): self
    {
        $this->datesubmitted = $datesubmitted;
        return $this;
    }
    public function __construct()
    {
        $this->datesubmitted = new \DateTime();
    }

    // Getter and setter for productpng
    public function getProductPng(): ?string
    {
        return $this->productpng;
    }

    public function setProductPng(?string $productpng): self
    {
        $this->productpng = $productpng;
        return $this;
    }
    public function getOeuvre(): ?Oeuvre
    {
        return $this->oeuvre;
    }
    
    public function setOeuvre(?Oeuvre $oeuvre): self
    {
        $this->oeuvre = $oeuvre;
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

/**
     * String representation of the Reclamation entity.
     *
     * @return string
     */
    public function __toString(): string
    {
        // Assuming that you want to represent the Reclamation by the title of the Oeuvre and its ID
        return $this->oeuvre ? $this->oeuvre->getTitre() . ' - ' . $this->getReclamationID() : '';
    }


}
