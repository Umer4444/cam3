<?php

namespace Application\Listener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Entity;

class MessageListener implements EventSubscriber, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    CONST CLASS_NAME = 'Application\Listener\MessageListener';

    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }

    public function prePersist(Entity\Message $message, LifecycleEventArgs $eventArgs)
    {

        $entityManager = $eventArgs->getEntityManager();

        /**
         * @var \Application\Service\Wallet $wallet
         */
        $wallet = $this->getServiceLocator()->get('wallet');
        $sender = $message->getSender();
        $receiver = $message->getReceiver();

        if (($tip = $message->getTip()) != 0 && $tip <= ($userCredit = $message->getSender()->getCredit())) {

            $wallet->sendAmount(-$tip, $sender);
            $wallet->sendAmount($tip, $receiver);

            // @todo this needs to hapen in the wallet
            $transaction = new Entity\TransferWall();
            $transaction->setType('tip');
            $transaction->setSender($sender);
            $transaction->setReceiver($receiver);
            $transaction->setAmount($tip);

            $entityManager->persist($transaction);
            $entityManager->flush();

        }

    }
}
