<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelToCategories
 *
 * @ORM\Table(name="model_to_categories", uniqueConstraints={@ORM\UniqueConstraint(name="id_model", columns={"id_model", "id_category"})}, indexes={@ORM\Index(name="id_category", columns={"id_category"})})
 * @ORM\Entity
 */
class ModelToCategories
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_category", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idCategory;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sort", type="boolean", nullable=false)
     */
    private $sort = '0';


}
