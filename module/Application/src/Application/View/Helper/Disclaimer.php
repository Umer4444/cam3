<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

/**
 * Class Disclaimer
 * @package Application\View\Helper
 */
class Disclaimer extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    public function __invoke()
    {

        $disclaimer = $this->getEntityManager()
            ->getRepository('Application\Entity\StaticPages')
            ->findOneBy(
                array(
                    'page' => "disclaimer_page"
                )
            );

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('disclaimer/popup',
                array(
                    'disclaimer' => $disclaimer
                )
            );

    }
}
