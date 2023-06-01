<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebchatUsers
 *
 * @ORM\Entity
 * @ORM\Table(name="webchat_users")
 */
class WebchatUsers
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=16, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gravatar", type="string", length=32, nullable=false)
     */
    private $gravatar;

    /**
     * @var string
     *
     * @ORM\Column(name="id_model", type="string", length=50, nullable=false)
     */
    private $idModel;

    /**
     * @var string
     *
     * @ORM\Column(name="id_user", type="string", length=50, nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_type", type="string", length=20, nullable=false)
     */
    private $chatType = 'normal';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=false)
     */
    private $lastActivity = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="loggedin", type="integer", nullable=true)
     */
    private $loggedin;

    /**
     * @var string
     *
     * @ORM\Column(name="broadcast_mode", type="string", nullable=true)
     */
    private $broadcastMode;

    /**
     * @var string
     *
     * @ORM\Column(name="quality", type="string", nullable=true)
     */
    private $quality;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_font", type="string", length=255, nullable=true)
     */
    private $chatFont;

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getGravatar()
    {

        return $this->gravatar;
    }

    /**
     * @param string $gravatar
     */
    public function setGravatar($gravatar)
    {

        $this->gravatar = $gravatar;
    }

    /**
     * @return string
     */
    public function getIdModel()
    {

        return $this->idModel;
    }

    /**
     * @param string $idModel
     */
    public function setIdModel($idModel)
    {

        $this->idModel = $idModel;
    }

    /**
     * @return string
     */
    public function getIdUser()
    {

        return $this->idUser;
    }

    /**
     * @param string $idUser
     */
    public function setIdUser($idUser)
    {

        $this->idUser = $idUser;
    }

    /**
     * @return string
     */
    public function getChatType()
    {

        return $this->chatType;
    }

    /**
     * @param string $chatType
     */
    public function setChatType($chatType)
    {

        $this->chatType = $chatType;
    }

    /**
     * @return \DateTime
     */
    public function getLastActivity()
    {

        return $this->lastActivity;
    }

    /**
     * @param \DateTime $lastActivity
     */
    public function setLastActivity($lastActivity)
    {

        $this->lastActivity = $lastActivity;
    }

    /**
     * @return int
     */
    public function getLoggedin()
    {

        return $this->loggedin;
    }

    /**
     * @param int $loggedin
     */
    public function setLoggedin($loggedin)
    {

        $this->loggedin = $loggedin;
    }

    /**
     * @return string
     */
    public function getBroadcastMode()
    {

        return $this->broadcastMode;
    }

    /**
     * @param string $broadcastMode
     */
    public function setBroadcastMode($broadcastMode)
    {

        $this->broadcastMode = $broadcastMode;
    }

    /**
     * @return string
     */
    public function getQuality()
    {

        return $this->quality;
    }

    /**
     * @param string $quality
     */
    public function setQuality($quality)
    {

        $this->quality = $quality;
    }

    /**
     * @return string
     */
    public function getChatFont()
    {

        return $this->chatFont;
    }

    /**
     * @param string $chatFont
     */
    public function setChatFont($chatFont)
    {

        $this->chatFont = $chatFont;
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

}
