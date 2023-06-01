<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelNotes
 *
 * @ORM\Table(name="model_notes")
 * @ORM\Entity
 *
 */
class ModelNotes
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
    protected $modelId;

    /**
     * @var integer
     *
     * @ORM\Column(name="notes", type="string", nullable=false)
     * @var mixed
     *
     */
    protected $notes;

    /**
     * @var integer
     *
     * @ORM\Column(name="added", type="datetime", nullable=false)
     * @var mixed
     *
     */
    protected $added;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="modelNotes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $model;

    public function __construct()
    {
        $this->added = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param mixed $added
     */
    public function setAdded($added)
    {
        $this->added = $added;
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
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param mixed $modelId
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

}
