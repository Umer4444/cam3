<?php

namespace Videos\Entity;

use Application\Entity\Pledge;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PledgeVideo extends Video
{

    /**
     * @var \Application\Entity\Pledge
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Pledge", inversedBy="videos", cascade={"all"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $pledge;

    /**
     * @return \Application\Entity\Pledge
     */
    public function getPledge()
    {
        return $this->pledge;
    }

    /**
     * @param \Application\Entity\Pledge $pledge
     *
     * @return $this
     */
    public function setPledge(Pledge $pledge)
    {
        $this->pledge = $pledge;
        return $this;
    }

}