<?php

namespace Application\Traits;

trait Entity {

    /**
     * @var mixed
     */
    protected $entity = null;

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

}