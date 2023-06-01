<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Mapper\Injector;
use PerfectWeb\Core\Interfaces\Routable;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\View\Helper\Object;

/**
 * Cart
 *
 * @ORM\Table(name="announcements")
 * @ORM\Entity(repositoryClass="Application\Repository\AnnouncementsRepository")
 */
class Announcements implements Routable
{

    use Traits\Routable;
    use Traits\User;

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
     * @ORM\Column(name="id_moderator", type="integer", nullable=false)
     * @var mixed
     *
     */
    protected $user;

    /**
     * @ORM\Column(name="text", type="string",  nullable=true)
     *
     * @var mixed
     */
    protected $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="go_live", type="datetime", nullable=false)
     * @var mixed
     *
     */
    protected $goLive;

    /**
     * @ORM\Column(name="section", type="string",  nullable=true)
     *
     * @var mixed
     */
    protected $section;


    public function __construct()
    {
        $this->goLive = new \DateTime();
    }

    public function __toString()
    {
        return $this->getText();
    }

    /**
     * @return mixed
     */
    public function getGoLive()
    {
        return $this->goLive;
    }

    /**
     * @param mixed $goLive
     */
    public function setGoLive($goLive)
    {
        $this->goLive = $goLive;
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
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    function getRoute($type = Object::ROUTE_TYPE_VIEW)
    {
        return 'news/view';
    }

    function getRouteParams()
    {
        return [
            Injector::NEWS => $this->getId()
        ];
    }

}
