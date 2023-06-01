<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Payment\AmountTrait;

/**
 * Funder
 *
 * @ORM\Table(name="funders")
 * @ORM\Entity(repositoryClass="Application\Repository\PledgeFunderRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity", type="string")
 * @ORM\DiscriminatorMap({
 *      "Application\Entity\Funder" = "Application\Entity\Funder",
 *      "Application\Entity\PledgeFunder" = "Application\Entity\PledgeFunder"
 * })
 */
class Funder
{
    use AmountTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="reference_id", type="integer", nullable=true)
     */
    protected $referenceId;

    /**
     * @ORM\Column(name="id_perk", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $perkId;

    /**
     * @ORM\Column(name="amount", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $amount;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
     *
     * @var mixed
     */
    protected $date;

    /**
     * @ORM\Column(name="anonymous", type="integer", nullable=false)
     *
     * @var mixed
     */
    protected $anonymous = 0;

    /*
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="pledgeFunder")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Funder
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param mixed $referenceId
     * @return Funder
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerkId()
    {
        return $this->perkId;
    }

    /**
     * @param mixed $perkId
     * @return Funder
     */
    public function setPerkId($perkId)
    {
        $this->perkId = $perkId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Funder
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnonymous()
    {
        return $this->anonymous;
    }

    /**
     * @param mixed $anonymous
     * @return Funder
     */
    public function setAnonymous($anonymous)
    {
        $this->anonymous = $anonymous;
        return $this;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return Funder
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }




}