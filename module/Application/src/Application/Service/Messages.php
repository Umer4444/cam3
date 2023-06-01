<?php
namespace Application\Service;

use Zend\Di\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity;

/**
 * Class Messages
 * @package Application\Service
 */
class Messages implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    protected $lastId = null;

    protected $messages;


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
    public function getMessages($id = null)
    {


        $this->fetch($id);


        return $this->messages;
    }

    /**
     * @param mixed $notifications
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
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
    public function countMessages($id = null)
    {


        $this->fetch($id);


        $countMessages = count($this->getMessages($id));

        return $countMessages;
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

            $messages = $em->getRepository('Application\Entity\Message')
                ->findBy(array('receiverId' => $id, 'read' => 0, 'type' => 'inbox'));


            if (!is_null($messages)) $this->setLastId($id);

            $this->setMessages($messages);

        }


    }


}