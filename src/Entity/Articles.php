<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticlesRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
#[ORM\Table(name: "articles", indexes: [new ORM\Index(name: "utilisateur_id", columns: ["utilisateur_id"])])]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $articleId = null;

    #[Assert\NotBlank(message: 'The title cannot be empty')]
    #[Assert\Length(max: 100, maxMessage: 'The title cannot exceed {{ limit }} characters')]
    #[ORM\Column(type: "string", length: 100)]
    private string $titre;

    #[Assert\NotBlank(message: 'The content cannot be empty')]
    #[Assert\Length(min: 200, minMessage: 'The content must be at least {{ limit }} characters long')]
    #[ORM\Column(type: "text")]
    private string $contenu;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $derniereModification;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "utilisateur_id",onDelete:"CASCADE")]
    private ?Utilisateurs $utilisateur = null;

    // Getters and setters ...

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDerniereModification(): ?\DateTimeInterface
    {
        return $this->derniereModification;
    }

    public function setDerniereModification(\DateTimeInterface $derniereModification): self
    {
        $this->derniereModification = $derniereModification;

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
