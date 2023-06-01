<?php

namespace Application\Entity;

use Application\Traits;
use PerfectWeb\Core\Traits as CoreTraits;
use Doctrine\ORM\Mapping as ORM;

/**
 * WebchatSessions
 *
 * @ORM\Table(name="webchat_sessions", uniqueConstraints={@ORM\UniqueConstraint(name="session", columns={"session"})},
 * indexes={@ORM\Index(name="session_idx", columns={"session"})})
 * @ORM\Entity
 */
class WebchatSessions
{

    use Traits\EndDate;
    use Traits\StartDate;
    use CoreTraits\User;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

     /**
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     *
     * @var mixed
     */
    protected $startDate;

    /**
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     *
     * @var mixed
     */
    protected $endDate;

    /**
     * @var mixed
     *
     * @ORM\Column(name="broadcast_mode", type="string", nullable=true)
     */
    protected $broadcastMode;

    /**
     * @ORM\Column(name="broadcast_type", type="string", nullable=false)
     */
    protected $broadcastType;

    /**
     * @var mixed
     *
     * @ORM\Column(name="number_of_cameras", type="string", nullable=true)
     */
    protected $numberOfCameras = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="session", type="string", nullable=false)
     */
    private $session;

    /**
     * @var string
     *
     * @ORM\Column(name="room_status", type="string", length=32, nullable=false)
     */
    private $roomStatus;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="webchatSessions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="webchatSessions")
     * @ORM\JoinColumn(name="starter_id", referencedColumnName="id")
     */
    protected $starter;

    function __construct()
    {
        $this->startDate = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * @param int $timer
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;
    }

    /**
     * @return mixed
     */
    public function getNumberOfCameras()
    {
        return $this->numberOfCameras;
    }

    /**
     * @param $numberOfCameras
     *
     * @return $this
     */
    public function setNumberOfCameras($numberOfCameras)
    {
        $this->numberOfCameras = $numberOfCameras;
        return $this;
    }

    /**
     * @return int
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param $session
     *
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return User
     */
    public function getStarter()
    {
        return $this->starter;
    }

    /**
     * @param User|null $starter
     *
     * @return $this
     */
    public function setStarter($starter = null)
    {
        $this->starter = $starter;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoomStatus()
    {
        return $this->roomStatus;
    }

    /**
     * @param $roomStatus
     *
     * @return $this
     */
    public function setRoomStatus($roomStatus)
    {
        $this->roomStatus = $roomStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBroadcastMode()
    {
        return $this->broadcastMode;
    }

    /**
     * @param $broadcastMode
     *
     * @return $this
     */
    public function setBroadcastMode($broadcastMode)
    {
        $this->broadcastMode = $broadcastMode;
        return $this;
    }

    /**
     * @param $broadcastType
     *
     * @return $this
     */
    public function setBroadcastType($broadcastType)
    {
        $this->broadcastType = $broadcastType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBroadcastType()
    {
        return $this->broadcastType;
    }

}