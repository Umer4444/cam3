<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

class Moderate extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use Traits\Entity;

    public function __invoke($entity, $route)
    {

        $view = new ViewModel();
        $view->setTemplate('partials/moderate');

        $view->setVariables([
            'object' => $entity,
            'url' => $this->getServiceLocator()->get('url')->__invoke($route, ['id' => $entity->getId()])
        ]);

        return $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($view);

    }

}