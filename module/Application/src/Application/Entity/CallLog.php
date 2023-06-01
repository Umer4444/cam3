<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * @ORM\Table(name="call_log")
 * @ORM\Entity
 */
class CallLog
{

    use Traits\User;
    use Traits\Status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="callLogs", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="account_sid", type="string", nullable=false)
     */
    private $accountSid;

    /**
     * @var string
     *
     * @ORM\Column(name="call_sid", type="string", nullable=false)
     */
    private $callSid;

    /**
     * @var string
     *
     * @ORM\Column(name="`from`", type="string", nullable=false)
     */
    private $from;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=false)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=false)
     */
    private $end;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccountSid()
    {
        return $this->accountSid;
    }

    /**
     * @param $accountSid
     *
     * @return $this
     */
    public function setAccountSid($accountSid)
    {
        $this->accountSid = $accountSid;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallSid()
    {
        return $this->callSid;
    }

    /**
     * @param $callSid
     *
     * @return $this
     */
    public function setCallSid($callSid)
    {
        $this->callSid = $callSid;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $duration
     *
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     *
     * @return $this
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return $this
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
        return $this;
    }

}
