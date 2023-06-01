<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity
 */
class Rules
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="free_chat", type="string", nullable=false)
     */
    private $freeChat;

    /**
     * @var string
     *
     * @ORM\Column(name="paid_chat", type="string", nullable=false)
     */
    private $paidChat;

    /**
     * @var string
     *
     * @ORM\Column(name="videos", type="string", nullable=false)
     */
    private $videos;

    /**
     * @var string
     *
     * @ORM\Column(name="photos", type="string", nullable=false)
     */
    private $photos;

    /**
     * @var integer
     *
     * @ORM\Column(name="fine", type="string", nullable=true)
     */
    private $fine;

    /**
     * @var string
     *
     * @ORM\Column(name="fine_text", type="string", nullable=true)
     */
    private $fineText;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getFreeChat()
    {
        return $this->freeChat;
    }

    /**
     * @param string $freeChat
     */
    public function setFreeChat($freeChat)
    {
        $this->freeChat = $freeChat;
    }

    /**
     * @return string
     */
    public function getPaidChat()
    {
        return $this->paidChat;
    }

    /**
     * @param string $paidChat
     */
    public function setPaidChat($paidChat)
    {
        $this->paidChat = $paidChat;
    }

    /**
     * @return string
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param string $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * @return string
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param string $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getFine()
    {
        return $this->fine;
    }

    /**
     * @param int $fine
     */
    public function setFine($fine)
    {
        $this->fine = $fine;
    }

    /**
     * @return string
     */
    public function getFineText()
    {
        return $this->fineText;
    }

    /**
     * @param string $fineText
     */
    public function setFineText($fineText)
    {
        $this->fineText = $fineText;
    }

}
