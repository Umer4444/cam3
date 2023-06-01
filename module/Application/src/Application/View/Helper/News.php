<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class News extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function __invoke(\Application\Entity\User $user)
    {

        $news = $this->getServiceLocator()->getServiceLocator()
                     ->get('em')
                     ->getRepository(\Application\Entity\Announcements::class)
                     ->getAnnouncements($user);

        return $this->getServiceLocator()
                    ->getServiceLocator()
                    ->get('ZfcTwigRenderer')
                    ->render('news', array('news' => $news));

    }

}