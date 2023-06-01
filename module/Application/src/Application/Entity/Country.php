<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=2, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="in_location", type="integer", nullable=true)
     */
    private $inLocation;

    /**
     * @var float
     *
     * @ORM\Column(name="geo_lat", type="float", precision=18, scale=11, nullable=true)
     */
    private $geoLat;

    /**
     * @var float
     *
     * @ORM\Column(name="geo_lng", type="float", precision=18, scale=11, nullable=true)
     */
    private $geoLng;

    /**
     * @var string
     *
     * @ORM\Column(name="db_id", type="string", nullable=true)
     */
    private $dbId;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getInLocation()
    {
        return $this->inLocation;
    }

    /**
     * @param int $inLocation
     */
    public function setInLocation($inLocation)
    {
        $this->inLocation = $inLocation;
    }

    /**
     * @return float
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * @param float $geoLat
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;
    }

    /**
     * @return float
     */
    public function getGeoLng()
    {
        return $this->geoLng;
    }

    /**
     * @param float $geoLng
     */
    public function setGeoLng($geoLng)
    {
        $this->geoLng = $geoLng;
    }

    /**
     * @return string
     */
    public function getDbId()
    {
        return $this->dbId;
    }

    /**
     * @param string $dbId
     */
    public function setDbId($dbId)
    {
        $this->dbId = $dbId;
    }

}
