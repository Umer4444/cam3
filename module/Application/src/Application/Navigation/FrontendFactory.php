<?php

namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class FrontendFactory extends AbstractNavigationFactory
{

    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'frontend';
    }

}