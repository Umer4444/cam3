<?php

namespace PerfectWeb\Core\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Application\Entity\User;

class Config extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke($context = 'site.cfg')
    {

        if ($context instanceof User) {
            $context = $context->getRole().'.cfg.'.$context->getId();
        }

        return $this->getServiceLocator()->getServiceLocator()->get($context);

    }

}