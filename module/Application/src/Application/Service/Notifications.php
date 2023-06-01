<?php
namespace Application\Service;

use Zend\Di\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity;

/**
 * Class Notifications
 * @package Application\Service
 */
class Notifications implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    protected $lastId = null;

    protected $notifications;


    /**
     * @return mixed
     */
    public function getLastId()
    {
        return $this->lastId;
    }

    /**
     * @param mixed $lastId
     */
    public function setLastId($lastId)
    {
        $this->lastId = $lastId;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getNotifications($id = null)
    {


        $this->fetch($id);


        return $this->notifications;
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }


    /**
     * init function for notifications
     *
     * @param int $id
     *
     * @return $this
     */
    public function init($id)
    {


        $this->fetch($id);


        return $this;

    }

    /**
     * @param $id
     *
     * @return int
     */
    public function countNotifications($id = null)
    {


        $this->fetch($id);


        $countNotifications = count($this->getNotifications($id));

        return $countNotifications;
    }

    /**
     * @param null $id
     * @throws \Exception
     */
    public function fetch($id = null)
    {

        if(is_null($id) && is_null($this->getLastId())) {

            throw new \Exception('You must send an Id in order to fetch notifications');
        }
        if ($this->getLastId() != $id) {

            $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

            $notifications = $em->getRepository('Application\Entity\UserNotifications')->findBy(array('idTo' => $id, 'read' => 0));

            if (!is_null($notifications)) $this->setLastId($id);

            $this->setNotifications($notifications);

        }


    }


}