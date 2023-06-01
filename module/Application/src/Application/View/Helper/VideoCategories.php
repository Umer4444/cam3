<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

class VideoCategories extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\EntityManager;

    public function __invoke()
    {

        $categories = $this->getEntityManager()
            ->getRepository('Application\Entity\Categories')
            ->findBy(
                array('entity' => 'Videos\Entity\Video'),
                array('name' => 'ASC'),
                4,
                0
            );

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('partials/videoCategories',
            array(
                'categories' => $categories
            )
        );
    }

}