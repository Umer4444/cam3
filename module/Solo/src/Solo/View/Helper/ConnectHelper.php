<?php

namespace Solo\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

class ConnectHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\ResourceGroup;

    public function __invoke()
    {
        $performerId = $this->view->user()->getUser()->getId();

        $connect = $this->getEntityManager()
            ->getRepository(\PerfectWeb\Core\Entity\ResourceValue::class)
            ->findBy(
                [
                    'user' => $performerId,
                    'resource' => $this->getGroup('connect')
                ]);

        if ($connect) {
           return  $connect;
        } else {
            throw new \Exception('This user does not have any social media setup.');
        }

    }

}