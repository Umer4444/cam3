<?php

namespace Solo\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class ModelLatestVideoHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function __invoke()
    {
        $performerId = $this->getServiceLocator()->get('user')->getUser()->getId();
        $latestVideo = $this->getServiceLocator()->getServiceLocator()
                            ->get('doctrine.entitymanager.orm_default')
                            ->getRepository('Videos\Entity\Video')
                            ->findOneBy(array('user' => $performerId, 'active' => 1), array('added' => 'DESC'));

        return $this->getServiceLocator()->getServiceLocator()
                    ->get('ZfcTwigRenderer')->render('latest_video', array('video' => $latestVideo));

    }

}