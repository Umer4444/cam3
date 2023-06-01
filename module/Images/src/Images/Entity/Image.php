<?php

namespace Images\Entity;

use Application\Entity\User;
use PerfectWeb\Core\Utils\Protect;
use PerfectWeb\Core\Utils\Status;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Interactions\InteractionInterface;
use Interactions\Entity\InteractionTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="photos")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity", type="string")
 * @ORM\DiscriminatorMap({
 *      null = "Images\Entity\Image",
 *      "Images\Entity\UserImage" = "Images\Entity\UserImage",
 *      "Images\Entity\Photo" = "Images\Entity\Photo",
 *      "Images\Entity\AlbumCoverImage" = "Images\Entity\AlbumCoverImage",
 *      "Videos\Entity\VideoCoverImage" = "Videos\Entity\VideoCoverImage",
 *      "Videos\Entity\VideoCaptureImage" = "Videos\Entity\VideoCaptureImage",
 *      "Images\Entity\BlogImage" = "Images\Entity\BlogImage",
 *      "Images\Entity\PledgeImage" = "Images\Entity\PledgeImage",
 *      "Images\Entity\CategoryImage" = "Images\Entity\CategoryImage"
 * })
 */
class Image extends Extended\Image implements InteractionInterface
{

    use InteractionTrait;

    // for type column
    const BIG_IMAGE = 'BigImage'; // @deprecated
    const SMALL_IMAGE = 'SmallImage'; // @deprecated
    const COVER = 'Cover';
    const IMAGE = 'Image';  // @deprecated
    const BIG_COVER = 'BigCover';
    const SMALL_COVER = 'SmallCover';
    const PLEDGE_COVER = 'pledge_cover';  // @deprecated
    const CATEGORY_COVER = 'category_cover';  // @deprecated

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="photos", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     *
     * @var \Application\Entity\User
     */
    protected $user;

    /**
     * @ORM\Column(name="reference_id", type="integer", nullable=true)
     */
    protected $entityReference;

    /**
     * @ORM\Column(name="filename", type="string")
     */
    protected $filename = '/assets/defaults/images/no-picture.jpg';

    /**
     * @ORM\Column(name="caption", type="string", nullable=true)
     */
    protected $caption;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 0;

    /**
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = Status::ACTIVE;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="uploaded_on", type="datetime")
     */
    protected $uploadedOn;

    /**
     * @ORM\ManyToOne(targetEntity="Albums", inversedBy="photos")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $album;

    function __construct()
    {
        $this->uploadedOn = new \DateTime();
    }

    function __toString()
    {
        return $this->getFilename();
    }

    /**
     * @return mixed
     */
    public function getEntityReference()
    {
        return $this->entityReference;
    }

    /**
     * @param mixed $entityReference
     */
    public function setEntityReference($entityReference)
    {
        $this->entityReference = is_object($entityReference) ? $entityReference->getId() : $entityReference;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param bool|true $protect
     *
     * @return string
     */
    public function getFilename($protect = true)
    {

        if ($protect && $this->getAlbum() && ($this->getAlbum()->isPrivate() || $this->getAlbum()->getCost())) {
            return Protect::getUrl($this->filename);
        }

        return $this->filename;

    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = str_replace(getcwd().'/public', '', $filename);
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getUploadedOn()
    {
        return $this->uploadedOn;
    }

    /**
     * @param mixed $uploadedOn
     */
    public function setUploadedOn(\DateTime $uploadedOn)
    {
        $this->uploadedOn = $uploadedOn;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return Albums
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum(Albums $album)
    {
        $this->album = $album;
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
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}