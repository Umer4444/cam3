<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CountryCity
 *
 * @ORM\Table(name="country_city",indexes={@ORM\Index(name="citycode_idx", columns={"city_code"})})
 * @ORM\Entity
 */
class CountryCity
{

    /**
     * @ORM\Column(name="city_code", type="string", length=100, nullable=true)
     * @ORM\Id
     */
    protected $cityCode;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;


    /**
     * @ORM\Column(name="geo_lat", type="string", length=20, nullable=false)
     */
    protected $geoLat;

    /**
     * @ORM\Column(name="geo_lng", type="string", length=20, nullable=false)
     */
    protected $geoLng;

    /**
     * @ORM\Column(name="province_code", type="string", length=50, nullable=false)
     */
    protected $provinceCode;

    /**
     * @ORM\Column(name="country_code", type="string", length=50, nullable=false)
     */
    protected $countryCode;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param mixed $cityCode
     */
    public function setCityCode($cityCode)
    {
        $this->cityCode = $cityCode;
    }

    /**
     * @return mixed
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $geoLat
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;
    }

    /**
     * @return mixed
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * @param mixed $geoLng
     */
    public function setGeoLng($geoLng)
    {
        $this->geoLng = $geoLng;
    }

    /**
     * @return mixed
     */
    public function getGeoLng()
    {
        return $this->geoLng;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $provinceCode
     */
    public function setProvinceCode($provinceCode)
    {
        $this->provinceCode = $provinceCode;
    }

    /**
     * @return mixed
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }


} 