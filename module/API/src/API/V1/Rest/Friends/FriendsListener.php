<?php
namespace API\V1\Rest\Friends;

use ZF\Apigility\Doctrine\Server\Event\DoctrineResourceEvent;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;

class FriendsListener implements ListenerAggregateInterface
{

    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            DoctrineResourceEvent::EVENT_DELETE_LIST_PRE,
            array($this, 'deleteListPre')
        );
    }

    public function deleteListPre(DoctrineResourceEvent $event)
    {

        $entity = $event->getObjectManager()
                  ->getRepository($event->getTarget()->getEntityClass())
                  ->findOneBy($event->getData());

        $event->getObjectManager()->remove($entity);
        $event->getObjectManager()->flush();
        $event->stopPropagation();

    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

}