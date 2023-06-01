<?php

namespace Images\Entity;

use Application\Entity\User;
use PerfectWeb\Payment;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Interactions\InteractionInterface;
use Interactions\Entity\InteractionTrait;
use PerfectWeb\Core\Traits;

/**
 * @ORM\Entity
 * @ORM\Table(name="albums")
 * @ORM\EntityListeners({"Images\Listener\AlbumsListener"})
 * @Gedmo\Loggable(logEntryClass="Gedmo\Loggable\Entity\LogEntry")
 */
class Albums extends Extended\Albums implements InteractionInterface, Payment\Interfaces\Purchasable, Payment\Interfaces\Unlockable
{

    use Payment\Traits\Purchasable;
    use InteractionTrait;
    use Traits\User;
    use Traits\Status;
    use Traits\Tags;
    use Traits\Description;
    use Traits\Password;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string")
     * @Gedmo\Versioned
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string")
     * @Gedmo\Versioned
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="albums")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     *
     * @var \Application\Entity\User
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="AlbumCoverImage")
     * @ORM\JoinColumn(name="cover", referencedColumnName="id")
     */
    protected $cover;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array", nullable=true)
     * @Gedmo\Versioned
     */
    protected $tags;

    /**
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @var \Application\Entity\Categories
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Categories")
     */
    protected $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    protected $private = false;

    /**
     * @ORM\Column(name="uploaded_on", type="datetime")
     */
    protected $uploadedOn;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updatedOn;

    /**
     * @ORM\Column(name="status", type="integer")
     * @Gedmo\Versioned
     */
    protected $status = \PerfectWeb\Core\Utils\Status::ACTIVE;

    /**
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(name="cost", type="integer", nullable=true)
     */
    protected $cost;

    /**
     * @ORM\OneToMany(targetEntity="Images\Entity\Photo", mappedBy="album")
     */
    protected $photos;

    function __construct()
    {
        $this->uploadedOn = new \DateTime();
        $this->photos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover(AlbumCoverImage $cover)
    {
        $this->cover = $cover;
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
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getUploadedOn()
    {
        return $this->uploadedOn;
    }

    /**
     * @param mixed $uploadedOn
     */
    public function setUploadedOn($uploadedOn)
    {
        $this->uploadedOn = $uploadedOn;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param mixed $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param mixed $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    public function addPhoto(Photo $photo)
    {
        $photo->setAlbum($this);
        $this->photos->add($photo);
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
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param $private
     *
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }


}
