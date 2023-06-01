<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPosts
 * @ORM\Table(name="rb_comments")
 * @ORM\Entity(repositoryClass="Application\Repository\RbCommentsRepository")
 */
class RbComments
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
     * @ORM\Column(name="thread", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $thread;

    /**
     * @ORM\Column(name="uri", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $uri;

    /**
     * @ORM\Column(name="author", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $author;

    /**
     * @ORM\Column(name="contact", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $contact;

    /**
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @var mixed
     */
    protected $content;

    /**
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $visible = 1;

    /**
     * @ORM\Column(name="spam", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $spam = 0;

    /**
     * @ORM\Column(name="published_on", type="datetime")
     *
     * @var mixed
     */
    protected $published;

    /**
     * @ORM\ManyToOne(targetEntity="RbComments", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="RbComments", mappedBy="parent", cascade={"persist"})
     */
    protected $children;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_domain_id", type="integer", nullable=true)
     */
    protected $domain;

    function __construct()
    {
        $this->published = new \DateTime();
        $this->children = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
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
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return boolean
     */
    public function getSpam()
    {
        return $this->spam;
    }

    /**
     * @param boolean $spam
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    }

    /**
     * @return mixed
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param mixed $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return int
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param int $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function addChild(RbComments $child)
    {
        $child->setParent($this);
        $this->children->add($child);
    }

}