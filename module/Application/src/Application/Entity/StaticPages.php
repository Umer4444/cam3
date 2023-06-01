<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaticPages
 *
 * @ORM\Table(name="static_pages", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})},
 * indexes={@ORM\Index(name="page", columns={"page"})})
 * @ORM\Entity
 */
class StaticPages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="page", type="string", length=25, nullable=false)
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="parent", type="string", length=255, nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="video", type="integer", nullable=true)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = 'frontend';

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="integer", nullable=false)
     */
    private $added = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = '1';

    /**
     * @var text
     *
     * @ORM\Column(name="link", type="string", nullable=true)
     */
    private $link = null;

    /**
     * @return int
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param int $added
     */
    public function setAdded($added)
    {
        $this->added = $added;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return int
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param int $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @param \Application\Entity\text $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return \Application\Entity\text
     */
    public function getLink()
    {
        return $this->link;
    }

}