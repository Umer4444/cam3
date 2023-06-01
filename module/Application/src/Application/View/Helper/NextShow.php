<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

/**
 * Class NextShow
 * @package Application\View\Helper
 */
class NextShow extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    public function __invoke()
    {
        $newestSchedule = $this->getEntityManager()
            ->getRepository('Application\Entity\ModelSchedule')
            ->getNextShow();

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('partials/nextShow',
                array(
                    'event' => $newestSchedule
                )
            );

    }
}
