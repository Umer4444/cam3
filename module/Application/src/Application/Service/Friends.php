<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity;

/**
 * Class FriendsService
 * @package Application\Service
 */
class Friends implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @param \Application\Entity\User $user
     * @param \Application\Entity\User $friend
     *
     * @return array
     */
    public function addFriend(Entity\User $user, Entity\User $friend)
    {

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $notification = new Entity\UserNotifications();
        $notification->setIdFrom($user->getId());
        $notification->setIdTo($friend->getId());
        $notification->setTypeFrom($user->getRoles()[0]->getRoleId());
        $notification->setTypeTo($friend->getRoles()[0]->getRoleId());
        $notification->setType('friend_request');
        $notification->setNotification($user->getUsername().' has sent you a friend request');
        $notification->setIp($_SERVER['REMOTE_ADDR']);
        $notification->setRead(0);
        $notification->setDate(time());
        $notification->setResource(0);
        $notification->setLinkedResource(0);
        $em->persist($notification);

        $newRelation = new Entity\Friends();
        $newRelation->setUser($user);
        $newRelation->setFriend($friend);
        $em->persist($newRelation);

        $em->flush();
        $response['status'] = "success";
        $response['message'] = "Friend request sent!";
        return $response;

    }

    /**
     * @param \Application\Entity\User $user
     * @param \Application\Entity\User $friend
     *
     * @return array
     */
    public function removeFriend(Entity\User $user, Entity\User $friend)
    {

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $response['message'] = "Something went wrong. Try again";

        $notification = new Entity\Notifications();
        $notification->setIdFrom($user->getId());
        $notification->setIdTo($friend->getId());
        $notification->setTypeFrom($user->getRoles()[0]->getRoleId());
        $notification->setTypeTo($friend->getRoles()[0]->getRoleId());
        $notification->setType('friend_request');
        $notification->setNotification($user->getUsername().' has unfriended you!');
        $notification->setIp($_SERVER['REMOTE_ADDR']);
        $notification->setRead(0);
        $notification->setDate(time());
        $em->persist($notification);

        $friend = $em->getRepository('Application\Entity\Friends')->findOneBy(['user' => $user, 'friend' => $friend]);

        if(!empty($friend)){
            $em->remove($friend);
            $em->flush();
            $response['message'] = "Unfriended!";
            $response['status'] = "success";
        }

        return $response;

    }


}