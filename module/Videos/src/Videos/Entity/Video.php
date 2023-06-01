<?php

namespace Videos\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Interactions\Entity\InteractionTrait;
use Interactions\InteractionInterface;
use PerfectWeb\Core\Utils\Status;
use Gedmo\Mapping\Annotation as Gedmo;
use PerfectWeb\Core\Traits;
use Application\Entity\User;

/**
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Videos\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity", type="string")
 * @ORM\DiscriminatorMap({
 *      "Videos\Entity\Video" = "Videos\Entity\Video",
 *      "Videos\Entity\ProfileVideo" = "Videos\Entity\ProfileVideo",
 *      "Videos\Entity\PledgeVideo" = "Videos\Entity\PledgeVideo",
 *      "Videos\Entity\ShowVideo" = "Videos\Entity\ShowVideo",
 *      "Videos\Entity\VodVideo" = "Videos\Entity\VodVideo",
 *      "Videos\Entity\PremiereVideo" = "Videos\Entity\PremiereVideo",
 *      "Videos\Entity\BlogVideo" = "Videos\Entity\BlogVideo"
 * })
 */
class Video extends Custom\VideoMethods implements InteractionInterface
{

    use Traits\User;
    use Traits\Status;
    use Traits\Title;
    use InteractionTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="videos", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var mixed
     *
     * @ORM\Column(name="reference_id", type="integer", nullable=true)
     */
    protected $entityReference;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="decimal", precision=6, scale=2, nullable=true)
     */
    protected $cost;

    /**
     * @var \Application\Entity\Categories
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Categories", inversedBy="videos")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="cast", type="string", nullable=true)
     */
    protected $cast;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array", nullable=true)
     */
    protected $tags = [];

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", nullable=false)
     */
    protected $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    protected $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    protected $status = Status::INACTIVE;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_on", type="datetime")
     */
    protected $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publish_on", type="datetime", nullable=true)
     */
    protected $publishOn;

    /**
     * @var VideoCoverImage
     *
     * @ORM\OneToOne(targetEntity="VideoCoverImage", cascade={"all"})
     * @ORM\JoinColumn(name="cover", referencedColumnName="id")
     */
    protected $cover;

    /**
     * @var VideoCaptureImage[]
     *
     * @ORM\OneToMany(targetEntity="VideoCaptureImage", mappedBy="video", cascade={"all"})
     */
    protected $captures;

    public function __construct() {
        $this->captures = new ArrayCollection();
    }

    /**
     * @param \Videos\Entity\VideoCaptureImage $capture
     *
     * @return $this
     */
    public function addCapture(VideoCaptureImage $capture) {
        if (!$this->captures->contains($capture)) {
            $capture->setVideo($this);
            $this->captures->add($capture);
        }
        return $this;
    }

    /**
     * @param \Videos\Entity\VideoCaptureImage $capture
     *
     * @return $this
     */
    public function removeCapture(VideoCaptureImage $capture) {
        if ($this->captures->contains($capture)) {
           $this->captures->remove($capture);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaptures()
    {
        return $this->captures;
    }

    /**
     * @param $captures
     *
     * @return $this
     */
    public function setCaptures($captures)
    {
        $this->captures = $captures;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param \Videos\Entity\VideoCoverImage $cover
     *
     * @return $this
     */
    public function setCover(VideoCoverImage $cover)
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return \Application\Entity\Categories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \Application\Entity\Categories $category
     *
     * @return $this
     */
    public function setCategory(\Application\Entity\Categories $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityReference()
    {
        return $this->entityReference;
    }

    /**
     * @param $entityReference
     *
     * @return $this
     */
    public function setEntityReference($entityReference)
    {
        $this->entityReference = is_object($entityReference) ? $entityReference->getId() : $entityReference ;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * @param $cast
     *
     * @return $this
     */
    public function setCast($cast)
    {
        $this->cast = $cast;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param $filename
     *
     * @return $this
     */
    public function setFilename($filename)
    {

        if (!($this->getUser() instanceof User)) {
            throw new \LogicException('You need to associate the user first');
        }

        if (strpos($filename, '/') === false) {
            $filename = $this->getUser()->getFolderPath(User::FOLDER_VIDEOS).$filename.'.mp4';
        }

        $this->filename = $filename;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param $createdOn
     *
     * @return $this
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishOn()
    {
        return $this->publishOn;
    }

    /**
     * @param \DateTime $publishOn
     *
     * @return $this
     */
    public function setPublishOn(\DateTime $publishOn)
    {
        $this->publishOn = $publishOn;
        return $this;
    }

}