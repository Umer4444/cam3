<?php

namespace Application\View\Helper;

use Application\Entity\Role;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

/**
 * Class Chat
 * @package Application\View\Helper
 */
class Chat extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\User;
    use Traits\Ensure;

    protected $room;

    function __invoke($user)
    {
        $this->setUser($this->ensureUser($user));
        $this->setRoom($this->getUser()->getStream());
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    function __toString()
    {

        $loggedUser = $this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();
        $showRooms = false;

        if (
            $loggedUser && $this->getUser()->getId() == $loggedUser->getId() &&
            $loggedUser->getRole() == Role::PERFORMER
        ) {
            $socketAction = 'createRoom';
        }
        else {
            $socketAction = 'joinRoom';
        }

        $view = new ViewModel();
        $view->setTemplate('chat');
        $view->setVariables([
            'user' => $this->getUser(),
            'showRooms' => $showRooms,
            'room' => $this->getRoom(),
            'socketAction' => $socketAction,
            'background' => $this->getBackground()
        ]);

        try{
            return $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($view);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getBackground()
    {

        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->getEntityManager()->getRepository(\Application\Entity\ChatBackground::class)->createQueryBuilder('b');
        $qb->where($qb->expr()->lte('b.start', ':now'))
           ->andWhere($qb->expr()->gte('b.end', ':now'))
           ->andWhere(
               $qb->expr()->orX(
                   $qb->expr()->eq('b.user', ':user'),
                   $qb->expr()->isNull('b.user')
               )
           )
           ->setMaxResults(1)
           ->setParameters(['now' => (new \DateTime())->format('Y-m-d H:i:s'), 'user' => $this->getUser()]);

        $background = current($qb->getQuery()->getResult()) ?: new \Application\Entity\ChatBackground();

        return $background->getFilename();

    }

}