<?php

namespace PerfectWeb\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PerfectWeb\Core\Traits;

/**
 * ResourceValue
 *
 * @ORM\Table(name="resource_values")
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="Gedmo\Loggable\Entity\LogEntry")
 */
class ResourceValue
{

    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="referring_to", type="integer", nullable=true)
     */
    protected $referringTo;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="settings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Resource
     *
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="values")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $resource;

    /**
     * @var \Application\Entity\User
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update", field={"value"})
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    protected $updatedOn;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return \Application\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param \Application\Entity\User $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy(\Application\Entity\User $updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime $updatedOn
     *
     * @return $this
     */
    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @return \PerfectWeb\Core\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \PerfectWeb\Core\Entity\Resource $resource
     *
     * @return $this
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return int
     */
    public function getReferringTo()
    {
        return $this->referringTo;
    }

    /**
     * @param $referringTo
     *
     * @return $this
     */
    public function setReferringTo($referringTo)
    {
        $this->referringTo = $referringTo;
        return $this;
    }

}