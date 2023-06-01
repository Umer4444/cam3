<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ModelSettings
 * @package Application\View\Helper
 */
class ModelSettings extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @param null $id
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($id = null)
    {
        return 'not used';
        $sm = $this->getServiceLocator()->getServiceLocator();
        if ($sm->has('Solo/Session')) {

            $modelId = $this->user()->getId();
        } else {

            if(is_null($id)){
                throw new \Exception('You must provide a model id to get the settings for, since you are not in solo, so it does not exist in the session');
            } else {
                $modelId = $id;
            }

        }
        $modelRepo = $sm->get('doctrine.entity_manager.orm_default')
            ->getRepository('Application\Entity\User');
        $model = $modelRepo->findOneBy(array('id' => $modelId));

        return $model;

    }

}