<?php

namespace Solo\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banners
 *
 * @ORM\Table(name="banners")
 * @ORM\Entity(repositoryClass="Application\Repository\BannersRepository")
 */
class Banners
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(name="id_user", type="integer",  nullable=false)
     *
     * @var mixed
     */
    protected $id_user;

    /**
     * @ORM\Column(name="id_owner", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $id_owner;

    /**
     * @ORM\Column(name="user_type", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $user_type;

    /**
     * @ORM\Column(name="content", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $content;

    /**
     * @ORM\Column(name="banner_zone", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $bannerZone;

    /**
     * @ORM\Column(name="banner_size", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $bannerSize;

    /**
     * @ORM\Column(name="start_date", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $start_date;
    /**
     * @ORM\Column(name="end_date", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $end_date;

    /**
     * @ORM\Column(name="status", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getBannerSize()
    {
        return $this->bannerSize;
    }

    /**
     * @param mixed $bannerSize
     */
    public function setBannerSize($bannerSize)
    {
        $this->bannerSize = $bannerSize;
    }

    /**
     * @return mixed
     */
    public function getBannerZone()
    {
        return $this->bannerZone;
    }

    /**
     * @param mixed $bannerZone
     */
    public function setBannerZone($bannerZone)
    {
        $this->bannerZone = $bannerZone;
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
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
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
     * @return mixed
     */
    public function getIdOwner()
    {
        return $this->id_owner;
    }

    /**
     * @param mixed $id_owner
     */
    public function setIdOwner($id_owner)
    {
        $this->id_owner = $id_owner;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * @param mixed $user_type
     */
    public function setUserType($user_type)
    {
        $this->user_type = $user_type;
    }

}
