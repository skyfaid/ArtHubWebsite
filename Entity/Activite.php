<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Activite
 *
 * @ORM\Table(name="activite")
 * @ORM\Entity
 */
class Activite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_activite", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idActivite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_act", type="string", length=255, nullable=false)
     */
    private $nomAct;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="Lieu", type="string", length=255, nullable=false)
     */
    private $lieu;

    /**
     * @var int
     *
     * @ORM\Column(name="nbre_places", type="integer", nullable=false)
     */
    private $nbrePlaces;

    /**
     * @var string
     *
     * @ORM\Column(name="type_act", type="string", length=255, nullable=false)
     */
    private $typeAct;

    /**
     * @var string
     *
     * @ORM\Column(name="posterUrl", type="string", length=255, nullable=false)
     */
    private $posterurl;


}
