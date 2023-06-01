<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class SlugifyHelper extends AbstractHelper implements ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;
    
    public function __invoke($string)
    {

        $service = $this->getServiceLocator()->getServiceLocator()
            ->get('Zf2SlugGenerator\SlugService'); //get slug service

        $slug = $service->create($string, false); //slugify it

        return $slug;
    }

} 