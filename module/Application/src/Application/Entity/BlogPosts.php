<?php

namespace Application\Entity;

use Application\Traits\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Images\Entity\Photo;
use Interactions\InteractionInterface;
use Interactions\Entity\InteractionTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\Utils\Status;

/**
 * BlogPosts
 *
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="Application\Repository\BlogPostsRepository")
 * @Gedmo\Loggable(logEntryClass="Gedmo\Loggable\Entity\LogEntry")
 *
 */
class BlogPosts implements InteractionInterface
{

    use Traits\User;
    use InteractionTrait;
    use Image;

    /**
     * @ORM\Column(name="title", type="string",  nullable=true)
     * @Gedmo\Versioned
     *
     * @var mixed
     */
    protected $title;

    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Gedmo\Versioned
     *
     * @var mixed
     */
    protected $content;

    /**
     * @ORM\Column(name="tags", type="simple_array", nullable=true)
     * @Gedmo\Versioned
     *
     * @var mixed
     */
    protected $tags;

    /**
     * @ORM\Column(name="slug", type="string", nullable=false)
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string
     */
    protected $slug;

    /**
     * @var Categories
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Categories", cascade={"all"})
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_on", type="datetime", nullable=false)
     */
    protected $postedOn;

    /**
     * @var \Images\Entity\BlogImage
     *
     * @ORM\OneToMany(targetEntity="\Images\Entity\BlogImage", mappedBy="post", cascade={"persist"})
     */
    protected $images;

    /**
     * @var \Videos\Entity\BlogVideo
     *
     * @ORM\OneToMany(targetEntity="\Videos\Entity\BlogVideo", mappedBy="blog", cascade={"persist"})
     */
    protected $blogVideo;

    /**
     * @ORM\Column(name="status", type="string", nullable=true)
     * @Gedmo\Versioned
     *
     * @var mixed
     */
    protected $status = Status::PENDING;

    /**
     * @ORM\Column(name="featured", type="boolean", nullable=false)
     * @Gedmo\Versioned
     *
     * @var boolean
     */
    protected $featured = false;

    /**
     * @ORM\Column(name="pinned", type="boolean", nullable=false)
     * @Gedmo\Versioned
     *
     * @var boolean
     */
    protected $pinned = 0;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @ORM\Column(name="reposts", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $reposts = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="repost_date", type="datetime", nullable=false)
     */
    protected $repostDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogPosts")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return BlogPosts
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return BlogPosts
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return BlogPosts
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get category
     *
     * @return Categories
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param Categories $category
     * @return BlogPosts
     */
    public function setCategory(Categories $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return BlogPosts
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get featured
     *
     * @return integer
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * Set featured
     *
     * @param integer $featured
     * @return BlogPosts
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdby
     *
     * @param User $createdBy
     *
     * @return User
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get reposts
     *
     * @return integer
     */
    public function getReposts()
    {
        return $this->reposts;
    }

    /**
     * Set reposts
     *
     * @param integer $reposts
     * @return BlogPosts
     */
    public function setReposts($reposts)
    {
        $this->reposts = $reposts;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    function __toString()
    {
        return $this->getTitle();
    }

    function __construct()
    {
        $this->images = new ArrayCollection();
        $this->postedOn = new \DateTime();
        $this->repostDate = new \DateTime();
    }

    public function addImage(Photo $image)
    {
        $image->setPost($this);
        $this->images->add($image);
    }

    /**
     * @return \Images\Entity\BlogImage
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param \Images\Entity\BlogImage $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return \DateTime
     */
    public function getPostedOn()
    {
        return $this->postedOn;
    }

    /**
     * @param \DateTime $postedOn
     */
    public function setPostedOn($postedOn)
    {
        $this->postedOn = $postedOn;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return \DateTime
     */
    public function getRepostDate()
    {
        return $this->repostDate;
    }

    /**
     * @return \Videos\Entity\BlogVideo
     */
    public function getBlogVideo()
    {
        return $this->blogVideo;
    }

    /**
     * @param \Videos\Entity\BlogVideo $blogVideo
     */
    public function setBlogVideo($blogVideo)
    {
        $this->blogVideo = $blogVideo;
    }

    /**
     * @param \DateTime $repostDate
     */
    public function setRepostDate($repostDate)
    {
        $this->repostDate = $repostDate;
    }

    /**
     * @return boolean
     */
    public function isPinned()
    {
        return $this->pinned;
    }

    /**
     * @param boolean $pinned
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;
    }

}
