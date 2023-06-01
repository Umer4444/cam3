<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

/**
 * Class CategoryNav
 * @package Application\View\Helper
 */
class FilterNav extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    /**
     * @return string
     */
    public function __invoke()
    {
        $trending = $this->getEntityManager()
            ->getRepository('Images\Entity\Albums')
            ->findBy([],[],6,0);

        $categories = $this->getEntityManager()
            ->getRepository('Application\Entity\Categories')
            ->findBy([],[],6,0);

        $filters = $this->getEntityManager()
            ->getRepository('Application\Entity\Filters')
            ->findAll();

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('partials/filter',
                array(
                    'trendings' => $trending,
                    'categories' => $categories,
                    'filters' => $filters,
                )
            );

    }

}