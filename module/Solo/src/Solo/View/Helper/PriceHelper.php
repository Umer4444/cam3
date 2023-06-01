<?php

namespace Solo\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

class PriceHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\ResourceGroup;

    public function __invoke()
    {
        $performerId = $this->view->user()->getUser()->getId();

        $price = $this->getEntityManager()
            ->getRepository('PerfectWeb\Core\Entity\ResourceValue')
            ->findBy(
                [
                    'user' => $performerId,
                    'resource' => $this->getGroup('price')
                ]);

        if ($price) {
           return  $price;
        } else {
            throw new \Exception('This user does not have any prices setup.');
        }

    }

}