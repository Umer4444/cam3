<?php

namespace PerfectWeb\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity
 */
class Resource
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @var array
     *
     * @ORM\Column(name="option_config", type="array", nullable=false)
     */
    protected $optionConfig = ['input_type' => 'text'];

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="text", nullable=true)
     */
    protected $entity;

    /**
     * @var ResourceValue[]
     *
     * @ORM\OneToMany(targetEntity="ResourceValue", mappedBy="resource", cascade={"all"})
     */
    protected $values;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string", nullable=false)
     */
    protected $group;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", nullable=true)
     */
    protected $label;

    /**
     * @ORM\Column(name="context", type="string", nullable=true)
     */
    protected $context;

    /**
     * @ORM\Column(name="frontend", type="boolean", nullable=false, options={"default" = 1})
     */
    protected $frontend = true;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * to string function
     *
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return ResourceValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param ResourceValue[] $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @param \PerfectWeb\Core\Entity\ResourceValue $value
     *
     * @return $this
     */
    public function addValue(ResourceValue $value)
    {

        if (!$this->values->contains($value)) {
            $value->setResource($this);
            $this->values->add($value);
        }

        return $this;

    }

    /**
     * @param \PerfectWeb\Core\Entity\ResourceValue $value
     *
     * @return $this
     */
    public function removeValue(ResourceValue $value)
    {

        if($this->values->contains($value)) {
            $this->values->remove($value);
        }

        return $this;

    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptionConfig()
    {
        return $this->optionConfig;
    }

    /**
     * @param array $optionConfig
     */
    public function setOptionConfig($optionConfig)
    {
        $this->optionConfig = $optionConfig;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrontend()
    {
        return $this->frontend;
    }

    /**
     * @param $frontend
     *
     * @return $this
     */
    public function setFrontend($frontend)
    {
        $this->frontend = $frontend;
        return $this;
    }

}