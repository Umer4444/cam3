<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity
 */
class Notifications
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_from", type="integer", nullable=false)
     */
    protected $idFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_from", type="string", nullable=false)
     * @var mixed
     */
    protected $typeFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_to", type="integer", nullable=false)
     */
    protected $idTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_to", type="string", nullable=false)
     * @var mixed
     */
    protected $typeTo;

    /**
     * @ORM\Column(name="type", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $type;

    /**
     * @ORM\Column(name="notification", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $notification;

    /**
     * @ORM\Column(name="ip", type="string",  nullable=true)
     *
     * @var mixed
     */
    protected $ip;

    /**
     * @ORM\Column(name="`read`", type="boolean", nullable=true, options={"default" = false})
     *
     * @var mixed
     */
    protected $read;

    /**
     * @ORM\Column(name="`date`", type="integer",  nullable=true, options={"default" = 0})
     *
     * @var mixed
     */
    protected $date;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdFrom()
    {
        return $this->idFrom;
    }

    /**
     * @param int $idFrom
     */
    public function setIdFrom($idFrom)
    {
        $this->idFrom = $idFrom;
    }

    /**
     * @return int
     */
    public function getIdTo()
    {
        return $this->idTo;
    }

    /**
     * @param int $idTo
     */
    public function setIdTo($idTo)
    {
        $this->idTo = $idTo;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * @param mixed $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTypeFrom()
    {
        return $this->typeFrom;
    }

    /**
     * @param mixed $typeFrom
     */
    public function setTypeFrom($typeFrom)
    {
        $this->typeFrom = $typeFrom;
    }

    /**
     * @return mixed
     */
    public function getTypeTo()
    {
        return $this->typeTo;
    }

    /**
     * @param mixed $typeTo
     */
    public function setTypeTo($typeTo)
    {
        $this->typeTo = $typeTo;
    }


}
