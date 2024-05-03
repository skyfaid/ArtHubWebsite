<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "participant")]
#[ORM\Entity]
class ParticipantFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", name: "id", nullable: false)]
    private $id;

    #[ORM\Column(type: "date", name: "date_inscription", nullable: false)]
    private $dateInscription;

    #[ORM\Column(type: "integer", name: "nbr_formations", nullable: false)]
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
