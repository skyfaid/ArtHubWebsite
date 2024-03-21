<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_oeuvre_id", columns={"id"}), @ORM\Index(name="fk_reclamation_oeuvre", columns={"reference"}), @ORM\Index(name="fk_utilisateur_id", columns={"utilisateur_id"})})
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
    private $reclamationid;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     */
    private $utilisateurId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=255, nullable=true, options={"default"="pending"})
     */
    private $status = 'pending';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateSubmitted", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datesubmitted = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="productPNG", type="string", length=255, nullable=true)
     */
    private $productpng;

    /**
     * @var int|null
     *
     * @ORM\Column(name="reference", type="integer", nullable=true)
     */
    private $reference;


}
