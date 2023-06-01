<?php

namespace Videos\Entity;

use Application\Entity\Show;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ShowVideo extends Video
{

    /**
     * @var \Application\Entity\Show
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Show", inversedBy="videos", cascade={"all"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $show;

    /**
     * @return \Application\Entity\Show
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * @param \Application\Entity\Show $show
     *
     * @return $this
     */
    public function setShow(Show $show)
    {
        $this->show = $show;
        return $this;
    }

}