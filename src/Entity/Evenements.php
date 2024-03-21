<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenements
 *
 * @ORM\Table(name="evenements", uniqueConstraints={@ORM\UniqueConstraint(name="unique_event_constraint", columns={"nom"})})
 * @ORM\Entity
 */
class Evenements
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datefin", type="date", nullable=true)
     */
    private $datefin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombrePlaces", type="integer", nullable=true)
     */
    private $nombreplaces;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombreParticipants", type="integer", nullable=true)
     */
    private $nombreparticipants = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="posterUrl", type="string", length=255, nullable=true)
     */
    private $posterurl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="videoUrl", type="string", length=255, nullable=true)
     */
    private $videourl;


}
