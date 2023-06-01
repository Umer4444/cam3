<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ScheduledMedia
 *
 * @ORM\Table(name="scheduled_media")
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="Gedmo\Loggable\Entity\LogEntry")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "Application\Entity\ScheduledMedia" = "Application\Entity\ScheduledMedia",
 *      "Application\Entity\Logo" = "Application\Entity\Logo",
 *      "Application\Entity\ChatBackground" = "Application\Entity\ChatBackground"
 * })
 */
class ScheduledMedia
{

    use \PerfectWeb\Core\Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="start", type="datetime", nullable=false)
     */
    protected $start;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="end", type="datetime",  nullable=false)
     */
    protected $end;

    /**
     * @ORM\Column(name="filename", type="string", nullable=false)
     * @Gedmo\UploadableFilePath
     * @Gedmo\Versioned
     */
    protected $filename = '/uploads/logos/logo.png';

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="scheduledMedia")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    function __construct()
    {
        $this->start = new \DateTime();
        $this->end = new \DateTime('tomorrow');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
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
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return str_replace('public', '', $this->filename);
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

}
