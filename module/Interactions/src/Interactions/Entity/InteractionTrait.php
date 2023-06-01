<?php

namespace Interactions\Entity;

use Doctrine\ORM\Mapping as ORM;

trait InteractionTrait
{

    /**
     * @var \Interactions\Entity\Interaction
     */
    protected $interaction;

    /**
     * @return \Interactions\Entity\Interaction
     */
    public function getInteraction()
    {
        return $this->interaction;
    }

    /**
     * @param \Interactions\Entity\Interaction $interaction
     */
    public function setInteraction($interaction)
    {
        $this->interaction = $interaction;
    }

}