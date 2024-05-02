<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "vente")]
class Vente
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $datevente = null;

    public function getDatevente(): ?\DateTimeInterface
    {
        return $this->datevente;
    }

    public function setDatevente(?\DateTimeInterface $datevente): self
    {
        $this->datevente = $datevente;
        return $this;
    }

    #[ORM\Column(type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $prixvente = null;

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(?float $prixvente): self
    {
        $this->prixvente = $prixvente;
        return $this;
    }

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $modepaiement = null;

    public function getModepaiement(): ?string
    {
        return $this->modepaiement;
    }

    public function setModepaiement(?string $modepaiement): self
    {
        $this->modepaiement = $modepaiement;
        return $this;
    }

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $quantite = null;

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Oeuvre::class)]
    #[ORM\JoinColumn(name: "ID_OeuvreVendue", referencedColumnName: "id")]
    private ?Oeuvre $oeuvre = null;

    public function getOeuvre(): ?Oeuvre
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Oeuvre $oeuvre): self
    {
        $this->oeuvre = $oeuvre;
        return $this;
    }
}
