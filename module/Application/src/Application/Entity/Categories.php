<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity", type="string")
 * @ORM\DiscriminatorMap({
 *      "Videos\Entity\VodCategory" = "Videos\Entity\VodCategory",
 *      "Videos\Entity\VideoCategory" = "Videos\Entity\VideoCategory",
 *      "Application\Entity\UserCategory" = "Application\Entity\UserCategory",
 *      NULL = "Application\Entity\Categories"
 * })
 */
class Categories
{

    use Traits\Entity;
    use Traits\User;
    use Traits\Name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Categories", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $parent = null;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="categories")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="Videos\Entity\Video", mappedBy="category", fetch="EXTRA_LAZY"))
     *
     */
    protected $videos;

    /**
     *
     * construct function for array collection
     */
    public function __construct()
    {
        $this->videos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param mixed $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get parent
     *
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     * @return Categories
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

}