<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

class Repost extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use Traits\Entity;

    public function __invoke($entity, $url, $identity = null)
    {

        $view = new ViewModel();
        $view->setTemplate('buttons/repost');

        $this->setEntity(
            is_object($entity) ? $entity : $this->getServiceLocator()->getServiceLocator()->get('em')->find($entity, $identity)
        );

        $view->setVariables([
            'object' => $this->getEntity(),
            'url' => $url,
        ]);

        $this->setView($view);

        return $this;

    }

    function __toString()
    {

        return $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($this->getView());

    }


}