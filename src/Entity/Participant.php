<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity
 */
class Participant
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="date", nullable=false)
     */
    private $dateInscription;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_formations", type="integer", nullable=false)
     */
    private $nbrFormations;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of dateInscription
     */ 
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Set the value of dateInscription
     *
     * @param \DateTime $dateInscription
     * @return self
     */
    public function setDateInscription(\DateTime $dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get the value of nbrFormations
     */ 
    public function getNbrFormations()
    {
        return $this->nbrFormations;
    }

    /**
     * Set the value of nbrFormations
     *
     * @param int $nbrFormations
     * @return self
     */
    public function setNbrFormations(int $nbrFormations)
    {
        $this->nbrFormations = $nbrFormations;

        return $this;
    }
}
