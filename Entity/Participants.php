<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Participants
 *
 * @ORM\Table(name="participants", indexes={@ORM\Index(name="fk_event_id", columns={"event_id"}), @ORM\Index(name="utilisateur_id", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Participants
{
    /**
     * @var int
     *
     * @ORM\Column(name="participant_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $participantId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=true)
     */
    private $utilisateurId;

    /**
     * @var \Evenements
     *
     * @ORM\ManyToOne(targetEntity="Evenements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;


}
