<?php

namespace Interactions\Listener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class InteractionListener implements EventSubscriber
{

    /**
     * Class interface
     */
    const INTERFACE_FQNS = \Interactions\InteractionInterface::class;

    /**
     * Interaction class
     *
     * @var string
     */
    protected $interactionClass = \Interactions\Entity\Interaction::class;

    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
            Events::postLoad,
            Events::preUpdate,
        );
    }

    public function postLoad(EventArgs $args)
    {

        $object = $args->getObject();
        $className = get_class($object);

        if ($className == $this->getInteractionClass() || !in_array(self::INTERFACE_FQNS, class_implements($object))) {
            return;
        }

        $interaction = $args->getObjectManager()
                            ->getRepository($this->getInteractionClass())
                            ->findOneBy([
                                'entity' => $className,
                                'entityReference' => $object->getId()
                            ]);

        $object->setInteraction($interaction ?: new $this->interactionClass());

    }

    public function preUpdate(EventArgs $args)
    {

        $object = $args->getObject();
        $className = get_class($object);

        if ($className != $this->getInteractionClass()) {
            return;
        }

        if ($args->hasChangedField('rating')) {
            $args->setNewValue('rating', $args->getOldValue('rating') + $args->getNewValue('rating'));
            $object->setVotes($object->getVotes() + 1);
        }

        if ($args->hasChangedField('likes') && substr($args->getNewValue('likes'), 0, 1) == '+') {
            $args->setNewValue('likes', $args->getOldValue('likes') + 1);
        }

        if ($args->hasChangedField('dislikes') && substr($args->getNewValue('dislikes'), 0, 1) == '+') {
            $args->setNewValue('dislikes', $args->getOldValue('dislikes') + 1);
        }

    }

    public function postPersist(LifecycleEventArgs $args)
    {

        $entityClass = get_class($args->getEntity());

        if (
            get_class($args->getEntity()) == $this->getInteractionClass() ||
            !in_array(self::INTERFACE_FQNS, class_implements($args->getEntity()))
        ) {
            return;
        }

        $interaction = new $this->interactionClass();
        $interaction->setEntityReference($args->getEntity()->getId());
        $interaction->setEntity($entityClass);

        $args->getEntityManager()->persist($interaction);
        $args->getEntityManager()->flush();

    }


    /**
     * @return mixed
     */
    public function getInteractionClass()
    {
        return $this->interactionClass;
    }

    /**
     * @param mixed $interactionClass
     */
    public function setInteractionClass($interactionClass)
    {
        $this->interactionClass = $interactionClass;
    }

}