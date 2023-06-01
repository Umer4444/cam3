<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatesLimits
 *
 * @ORM\Table(name="rates_limits")
 * @ORM\Entity
 */
class RatesLimits
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
     * @ORM\Column(name="id_rate", type="integer", nullable=false)
     */
    private $idRate;

    /**
     * @var string
     *
     * @ORM\Column(name="limit_type", type="string", nullable=false)
     */
    private $limitType = 'min';

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $value = '2.00';


}
