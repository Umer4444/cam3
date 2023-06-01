<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use PerfectWeb\Core\Traits;

/**
 * UserNewsletter subscriptions
 *
 * @ORM\Table(name="user_newsletter")
 * @ORM\Entity
 */
class UserNewsletter
{

    use Traits\User;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="newsletter")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $user;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="subscribedUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $performer;

    /**
     * @var integer
     *
     * @ORM\Column(name="send", type="integer", nullable=false, unique=false)
     */
    protected $send;

    /**
     * @return int
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * @param int $performer
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param int $send
     */
    public function setSend($send)
    {
        $this->send = $send;
    }

}
