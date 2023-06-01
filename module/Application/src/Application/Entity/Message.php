<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPosts
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="Application\Repository\MessagesRepository")
 * @ORM\EntityListeners({"Application\Listener\MessageListener"})
 * @ORM\HasLifecycleCallbacks()
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="sentMessages")
     * @ORM\JoinColumn(name="id_sender", referencedColumnName="id")
     */
    protected $sender;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_type", type="string", nullable=false)
     *
     */
    protected $senderType; //used to be enum('user', 'model', 'moderator')

    /**
     * @var \Application\Entity\User
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="receivedMessages")
     * @ORM\JoinColumn(name="id_receiver", referencedColumnName="id")
     *
     */
    protected $receiver;

    /**
     * @var string
     *
     * @ORM\Column(name="receiver_type", type="string", nullable=false)
     *
     */
    protected $receiverType; //used to be enum('user', 'model', 'moderator')

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", nullable=false)
     *
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=false)
     *
     */
    protected $body;

    /**
     * @var integer
     *
     * @ORM\Column(name="inbox", type="integer", nullable=false)
     *
     */
    protected $inbox = 1; //used to be enum('1','0')

    /**
     * @var integer
     *
     * @ORM\Column(name="outbox", type="integer", nullable=false)
     *
     */
    protected $outbox = 1; //used to be enum('1','0')

    /**
     * @var integer
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=false)
     *
     */
    protected $sendDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="`read`", type="integer", nullable=false)
     *
     */
    protected $read = 0; //used to be enum('1','0')

    /**
     * @var float
     *
     * @ORM\Column(name="tip", type="float", nullable=false)
     *
     */
    protected $tip; //used to be enum('1','0')

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     *
     */
    protected $type; //used to be enum('inbox', 'outbox', 'archive', 'delete')

    public function __construct()
    {
        $this->sendDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSenderType()
    {
        return $this->senderType;
    }

    /**
     * @param $senderType
     */
    public function setSenderType($senderType)
    {
        $this->senderType = $senderType;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param \Application\Entity\User $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param \Application\Entity\User $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getReceiverType()
    {
        return $this->receiverType;
    }

    /**
     * @param $receiverType
     */
    public function setReceiverType($receiverType)
    {
        $this->receiverType = $receiverType;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getInbox()
    {
        return $this->inbox;
    }

    /**
     * @param $inbox
     */
    public function setInbox($inbox)
    {
        $this->inbox = $inbox;
    }

    /**
     * @return int
     */
    public function getOutbox()
    {
        return $this->outbox;
    }

    /**
     * @param $outbox
     */
    public function setOutbox($outbox)
    {
        $this->outbox = $outbox;
    }

    /**
     * @return int
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param $sendDate
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * @return int
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * @param $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * @return float
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * @param $tip
     */
    public function setTip($tip)
    {
        $this->tip = $tip;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
