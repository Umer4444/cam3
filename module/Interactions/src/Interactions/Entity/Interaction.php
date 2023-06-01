<?php

namespace Interactions\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="interactions")
 */
class Interaction
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    protected $views = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    protected $rating = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="votes", type="integer")
     *
     */
    protected $votes = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer")
     */
    protected $likes = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="dislikes", type="integer")
     */
    protected $dislikes = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="reference_id", type="integer", nullable=false)
     */
    protected $entityReference;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", nullable=false)
     */
    protected $entity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param $views
     *
     * @return $this
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param $rating
     *
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param $votes
     *
     * @return $this
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param $likes
     *
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * @param $dislikes
     *
     * @return $this
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntityReference()
    {
        return $this->entityReference;
    }

    /**
     * @param $entityReference
     *
     * @return $this
     */
    public function setEntityReference($entityReference)
    {
        $this->entityReference = $entityReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

}