<?php

namespace Solo\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

class SocialMediaHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\ResourceGroup;

    public function __invoke()
    {
        $performerId = $this->view->user()->getUser()->getId();

        $socialMedia = $this->getEntityManager()
            ->getRepository('PerfectWeb\Core\Entity\ResourceValue')
            ->findBy(
                [
                    'user' => $performerId,
                    'resource' => $this->getGroup('connect')
                ]);

        if ($socialMedia) {
            return $this->getServiceLocator()->getServiceLocator()
                ->get('ZfcTwigRenderer')
                ->render('socialMedia', ['media' => $socialMedia]);
        } else {
            throw new \Exception('This user does not have any social media setup.');
        }

    }

}