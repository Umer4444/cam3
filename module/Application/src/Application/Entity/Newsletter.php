<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Utils\Status;
use PerfectWeb\Core\Traits;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="newsletter",indexes={@ORM\Index(name="publish", columns={"publish_date"})})
 * @ORM\Entity
 */
class Newsletter
{

    use Traits\Status;
    use Traits\Content;

    const MEMBER_REGISTRATION = 'member_registration';
    const FORGOT_PASSWORD = 'forgot_password';

    const DOCUMENTS_ACCEPTED = 'documents_accepted';
    const DOCUMENTS_DECLINED = 'documents_declined';

    const PERFORMER_REGISTRATION = 'performer_registration';
    const PERFORMER_APPROVAL = 'performer_approval';

    const PHOTO_APPROVAL = 'photo_approval';

    const STUDIO_REGISTRATION = 'studio_registration';
    const STUDIO_PENDING = 'studio_pending';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\User", mappedBy="websiteNewsletters")
     */
    protected $users;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="datetime", nullable=true)`
     */
    protected $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", nullable=false)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", nullable=false)
     */
    protected $tag;

    /**
     * @var text
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;

    /**
     * @var smallint
     *
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status = Status::ACTIVE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="`default`", type="boolean")
     */
    protected $default = false;

    public function __construct()
    {
        $this->setUsers(new ArrayCollection());
        $this->setPublishDate(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param \DateTime $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

}