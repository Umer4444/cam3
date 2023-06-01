<?php

namespace Solo\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class ModelEventsHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function __invoke()
    {

        $performerId = $this->view->user()->getUser()->getId();

        $banners = $this->getServiceLocator()->getServiceLocator()
            ->get('em')->getRepository('Solo\Entity\Banners')->getBannersModel($performerId);

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('my_events', array('banners' => $banners));

    }

}