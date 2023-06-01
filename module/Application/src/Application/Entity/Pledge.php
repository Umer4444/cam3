<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pledge
 *
 * @ORM\Table(name="pledge")
 * @ORM\Entity
 */
class Pledge
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
     * @ORM\Column(name="id_category", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $categoryId;

    /**
     * @ORM\Column(name="title", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $title;

    /**
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     *
     * @var mixed
     */
    protected $type;

    /**
     * @ORM\Column(name="id_resource", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $resourceId;

    /**
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @var mixed
     */
    protected $content;

    /**
     * @ORM\Column(name="goal_amount", type="float", scale=2, nullable=false)
     *
     * @var mixed
     */
    protected $goalAmount;

    /**
     * @ORM\Column(name="goal_rep_share", type="float", scale=2, nullable=false)
     *
     * @var mixed
     */
    protected $goalRepShare;

    /**
     * @ORM\OneToMany(targetEntity="Images\Entity\PledgeImage", mappedBy="pledge")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $images;

    /**
     * @ORM\Column(name="funding_type", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $fundingType;

    /**
     * @ORM\Column(name="start_date", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $startDate;

    /**
     * @ORM\Column(name="end_date", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $endDate;

    /**
     * @ORM\Column(name="duration", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $duration;

    /**
     * @ORM\Column(name="duration_days", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $durationDays;

    /**
     * @ORM\Column(name="duration_type", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $durationType;

    /**
     * @ORM\Column(name="status", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $status;

    /**
     * @ORM\Column(name="rating", type="float", scale=2, nullable=false)
     *
     * @var mixed
     */
    protected $rating;

    /**
     * @ORM\Column(name="votes", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $votes;

    /**
     * @ORM\Column(name="likes", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $likes;

    /**
     * @ORM\Column(name="dislikes", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $dislikes;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="pledges")
     * @ORM\JoinColumn(name="id_model", referencedColumnName="id")
     */
    protected $model;

    /**
     * @var \Videos\Entity\Video
     *
     * @ORM\OneToMany(targetEntity="Videos\Entity\PledgeVideo", mappedBy="pledge", cascade={"all"})
     */
    protected $videos;

    /**
     * construct function
     */
    function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * @return Pledge
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     * @return Pledge
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Pledge
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @param mixed $type
     * @return Pledge
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param mixed $resourceId
     * @return Pledge
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Pledge
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoalAmount()
    {
        return $this->goalAmount;
    }

    /**
     * @param mixed $goalAmount
     * @return Pledge
     */
    public function setGoalAmount($goalAmount)
    {
        $this->goalAmount = $goalAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoalRepShare()
    {
        return $this->goalRepShare;
    }

    /**
     * @param mixed $goalRepShare
     * @return Pledge
     */
    public function setGoalRepShare($goalRepShare)
    {
        $this->goalRepShare = $goalRepShare;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     * @return Pledge
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFundingType()
    {
        return $this->fundingType;
    }

    /**
     * @param mixed $fundingType
     * @return Pledge
     */
    public function setFundingType($fundingType)
    {
        $this->fundingType = $fundingType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     * @return Pledge
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     * @return Pledge
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
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
     * @param mixed $duration
     * @return Pledge
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDurationDays()
    {
        return $this->durationDays;
    }

    /**
     * @param mixed $durationDays
     * @return Pledge
     */
    public function setDurationDays($durationDays)
    {
        $this->durationDays = $durationDays;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDurationType()
    {
        return $this->durationType;
    }

    /**
     * @param mixed $durationType
     * @return Pledge
     */
    public function setDurationType($durationType)
    {
        $this->durationType = $durationType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Pledge
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     * @return Pledge
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param mixed $votes
     * @return Pledge
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     * @return Pledge
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * @param mixed $dislikes
     * @return Pledge
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return Pledge
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \Videos\Entity\Video
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param \Videos\Entity\Video $videos
     * @return Pledge
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
        return $this;
    }


}