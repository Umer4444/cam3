<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CountryProvince
 *
 * @ORM\Table(name="country_province",indexes={@ORM\Index(name="cprovince_idx", columns={"country_province_code"})})
 * @ORM\Entity
 */
class CountryProvince
{

    /**
     * @ORM\Column(name="country_province_code", type="string", length=7, nullable=false)
     * @ORM\Id
     * @var mixed
     */
    protected $countryProvinceCode;

    /**
     * @ORM\Column(name="country_name", type="string", length=44, nullable=false)
     *
     * @var mixed
     */
    protected $countryName;

    /**
     * @ORM\Column(name="province_name", type="string", length=78, nullable=true)
     *
     * @var mixed
     */
    protected $provinceName;

    /**
     * @ORM\Column(name="province_alternate_names", type="string", length=178, nullable=true)
     *
     * @var mixed
     */
    protected $provinceAlternateNames;

    /**
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     *
     * @var mixed
     */
    protected $countryCode;

    /**
     * @return mixed
     */
    public function getCountryProvinceCode()
    {
        return $this->countryProvinceCode;
    }

    /**
     * @param $countryProvinceCode
     */
    public function setCountryProvinceCode($countryProvinceCode)
    {
        $this->countryProvinceCode = $countryProvinceCode;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }

    /**
     * @return mixed
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * @param $provinceName
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;
    }

    /**
     * @return mixed
     */
    public function getProvinceAlternateNames()
    {
        return $this->provinceAlternateNames;
    }

    /**
     * @param $provinceAlternateNames
     */
    public function setProvinceAlternateNames($provinceAlternateNames)
    {
        $this->provinceAlternateNames = $provinceAlternateNames;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

}