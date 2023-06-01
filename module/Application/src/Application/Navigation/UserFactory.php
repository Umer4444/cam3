<?php

namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class UserFactory extends AbstractNavigationFactory
{

    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'user-profile';
    }

}