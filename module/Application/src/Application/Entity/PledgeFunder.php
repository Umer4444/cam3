<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PledgeFunder
 * @package Application\Entity
 * @ORM\Entity()
 */
class PledgeFunder extends Funder
{
    /**
    * @var integer
    * @ORM\OneToOne(targetEntity="Pledge")
    * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
    */
    protected $pledge;

    /**
     * @return mixed
     */
    public function getPledge()
    {
        return $this->pledge;
    }

    /**
     * @param mixed $pledge
     * @return PledgeFunder
     */
    public function setPledge($pledge)
    {
        $this->pledge = $pledge;
        return $this;
    }


}