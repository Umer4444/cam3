<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Show
 *
 * @ORM\Table(name="shows")
 * @ORM\Entity
 */
class Show
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="shows")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="chips_cost", type="integer", nullable=false)
     */
    protected $chipsCost;

    /**
     * @var integer
     *
     * @ORM\Column(name="chips_reserved", type="integer", nullable=false)
     */
    protected $chipsReserved;

    /**
     * @var integer
     *
     * @ORM\Column(name="nr_users", type="integer", nullable=false)
     */
    protected $nrUsers;

    /**
     * @ORM\OneToMany(targetEntity="Videos\Entity\ShowVideo", mappedBy="show")
     **/
    protected $videos;

    public function __construct()
    {

        $this->videos = new ArrayCollection();

    }
    /**
     * @return int
     */
    public function getChipsCost()
    {
        return $this->chipsCost;
    }

    /**
     * @param int $chipsCost
     */
    public function setChipsCost($chipsCost)
    {
        $this->chipsCost = $chipsCost;
    }

    /**
     * @return int
     */
    public function getChipsReserved()
    {
        return $this->chipsReserved;
    }

    /**
     * @param int $chipsReserved
     */
    public function setChipsReserved($chipsReserved)
    {
        $this->chipsReserved = $chipsReserved;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getNrUsers()
    {
        return $this->nrUsers;
    }

    /**
     * @param int $nrUsers
     */
    public function setNrUsers($nrUsers)
    {
        $this->nrUsers = $nrUsers;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}
