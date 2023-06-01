<?php

namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class PerformerFactory extends AbstractNavigationFactory
{

    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'performer';
    }

}