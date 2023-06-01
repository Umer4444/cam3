<?php

namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Navigation\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFactory extends AbstractNavigationFactory
{
    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'register';
    }

}