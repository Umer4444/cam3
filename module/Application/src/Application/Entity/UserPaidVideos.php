<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPaidVideos
 *
 * @ORM\Table(name="user_paid_videos")
 * @ORM\Entity
 */
class UserPaidVideos
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
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    protected $idUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_video", type="integer", nullable=false)
     */
    protected $idVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $amount;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserPaidVideos
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     * @return UserPaidVideos
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdVideo()
    {
        return $this->idVideo;
    }

    /**
     * @param int $idVideo
     * @return UserPaidVideos
     */
    public function setIdVideo($idVideo)
    {
        $this->idVideo = $idVideo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return UserPaidVideos
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }



}
