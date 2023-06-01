<?php

namespace Application\Navigation;

use Application\Entity\Role;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Navigation\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Exception;

class BackendFactory extends AbstractNavigationFactory
{

	/**
     * overwrite get pages to include store admin as subpages for store sidemenu item
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     * @throws InvalidArgumentException
     */
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {

        if (null === $this->pages) {
            $configuration = $serviceLocator->get('Config');

            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }

            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $auth = $serviceLocator->get('zfcuser_auth_service');

            if (!($auth->getIdentity()->getRole() == Role::PERFORMER && $auth->getIdentity()->getDomain())) {
                unset($pages['store']);
            }

            $this->pages = $this->preparePages($serviceLocator, $pages);
        }

        return $this->pages;
    }

    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'backend';
    }

}