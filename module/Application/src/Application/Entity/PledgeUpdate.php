<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PledgeUpdate
 *
 * @ORM\Table(name="pledge_update")
 * @ORM\Entity
 */
class PledgeUpdate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_pledge", type="integer", nullable=false)
     */
    private $idPledge;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added;


}
