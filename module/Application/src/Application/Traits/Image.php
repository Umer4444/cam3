<?php

namespace Application\Traits;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Images\Entity\Photo;

trait Image {

    private $reflectionClass = null;

    function __call($method, $arguments)
    {

        if (!method_exists($this, $method) && substr($method, 0, 3) == 'get') {

            $type = substr($method, 3);
            $this->reflectionClass = $this->reflectionClass ?: new \ReflectionClass(Photo::class);
            $filter = new CamelCaseToUnderscore();

            if ($this->reflectionClass->getConstants()[strtoupper($filter->filter($type))]) {

                foreach ($this->getImages() as $image) {
                    if ($image->getType() == $type) {
                        return $image;
                    }
                }

                // probably the covers are not approved so return a default entity photo
                return new Photo();

            }

        }

        throw new \Exception("Method not defined !");

    }

}