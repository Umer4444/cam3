<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelInfo
 *
 * @ORM\Table(name="model_info")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 */
class ModelInfo
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
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     * @var mixed
     *
     */
    protected $userId;

    /**
     * @ORM\Column(name="id_field", type="integer",  nullable=true)
     *
     * @var mixed
     */
    protected $fieldId;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="string", nullable=false)
     * @var mixed
     *
     */
    protected $value;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Info", mappedBy="fieldValue")
     */
    protected $field;

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
     * @return ArrayCollection
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

}
