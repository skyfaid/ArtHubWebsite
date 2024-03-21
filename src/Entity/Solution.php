<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solution
 *
 * @ORM\Table(name="solution", indexes={@ORM\Index(name="ReclamationID", columns={"ReclamationID"}), @ORM\Index(name="utilisateur_id", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Solution
{
    /**
     * @var int
     *
     * @ORM\Column(name="SolutionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $solutionid;

    /**
     * @var int
     *
     * @ORM\Column(name="ReclamationID", type="integer", nullable=false)
     */
    private $reclamationid;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     */
    private $utilisateurId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=255, nullable=true, options={"default"="pending"})
     */
    private $status = 'pending';

    /**
     * @var float|null
     *
     * @ORM\Column(name="RefundAmount", type="float", precision=10, scale=2, nullable=true)
     */
    private $refundamount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AdminFeedback", type="text", length=65535, nullable=true)
     */
    private $adminfeedback;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DateResolved", type="datetime", nullable=true)
     */
    private $dateresolved;


}
