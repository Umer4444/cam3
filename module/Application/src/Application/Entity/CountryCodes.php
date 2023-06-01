<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country_codes",indexes={@ORM\Index(name="ccode_idx", columns={"country_code"})})
 * @ORM\Entity
 */
class CountryCodes
{

    /**
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     * @ORM\Id
     * @var mixed
     */
    protected $countryCode;

    /**
     * @ORM\Column(name="country", type="string", length=150, nullable=false)
     *
     * @var mixed
     */
    protected $country;

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function __toString()
    {
        return $this->getCountry();
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

}