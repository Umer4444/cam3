<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="info")
 * @ORM\Entity
 */
class Info
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
     * @var integer
     *
     * @ORM\Column(name="field", type="string", nullable=false)
     * @var mixed
     *
     */
    protected $field;

    /**
     * @ORM\Column(name="type", type="string",  nullable=true)
     *
     * @var mixed
     */
    protected $type;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\ModelInfo", inversedBy="field")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    protected $fieldValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_values", type="string", nullable=false)
     * @var mixed
     *
     */
    protected $defaultValues;

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
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

    /**
     * @param $defaultValues
     */
    public function setDefaultValues($defaultValues)
    {
        $this->defaultValues = $defaultValues;
    }

    /**
     * @return mixed
     */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }

    /**
     * @param $fieldValue
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
    }
}
