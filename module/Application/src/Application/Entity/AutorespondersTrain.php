<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutorespondersTrain
 *
 * @ORM\Table(name="autoresponders_train", indexes={@ORM\Index(name="id_model", columns={"id_model"})})
 * @ORM\Entity
 */
class AutorespondersTrain
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_model", type="integer", nullable=false)
     */
    private $idModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_question", type="integer", nullable=false)
     */
    private $idQuestion;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_answer", type="integer", nullable=false)
     */
    private $idAnswer;


}
