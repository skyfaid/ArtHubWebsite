<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Vente
 *
 * @ORM\Table(name="vente", indexes={@ORM\Index(name="ID_OeuvreVendue", columns={"ID_OeuvreVendue"})})
 * @ORM\Entity
 */
class Vente
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ID_OeuvreVendue", type="integer", nullable=true)
     */
    private $idOeuvrevendue;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DateVente", type="date", nullable=true)
     */
    private $datevente;

    /**
     * @var float|null
     *
     * @ORM\Column(name="PrixVente", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixvente;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ModePaiement", type="string", length=255, nullable=true)
     */
    private $modepaiement;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;


}
