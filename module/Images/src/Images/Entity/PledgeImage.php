<?php
/**
 * Created by PhpStorm.
 * User: ioneali
 * Date: 19.03.2015
 * Time: 13:46
 */

namespace Images\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PledgeImage
 * @ORM\Entity
 */
class PledgeImage extends Photo
{
    /**
     * @var \Application\Entity\Pledge
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Pledge", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id", nullable=true)
     *
     */
    protected $pledge;

    /**
     * @return \Application\Entity\BlogPosts
     */
    public function getPledge()
    {
        return $this->pledge;
    }

    /**
     * @param \Application\Entity\BlogPosts $pledges
     */
    public function setPledge($pledge)
    {
        $this->pledge = $pledge;
    }

}