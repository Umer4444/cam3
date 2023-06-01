<?php

namespace PerfectWeb\Payment\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * Purchased content
 *
 * @ORM\Entity()
 * @ORM\Table(name="purchased_content")
 */
class PurchasedContent
{

    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="purchasedContent")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     *
     */
    protected $user;

    /**
     * var integer
     *
     * @ORM\Column(name="reference_id", type="integer", nullable=true)
     */
    protected $entityReference;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $amount = 0.00;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", nullable=false)
     */
    protected $entity;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
        $this->entityReference = $entityReference;
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

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}