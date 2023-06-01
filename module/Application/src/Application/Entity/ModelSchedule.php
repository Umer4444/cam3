<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * ModelSchedule
 *
 * @ORM\Table(name="model_schedule")
 * @ORM\Entity(repositoryClass="Application\Repository\ScheduleRepository")
 */
class ModelSchedule
{
    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(name="id_model", type="integer",  nullable=false)
     *
     * @var mixed
     */
    protected $id_model;

    /**
     * @ORM\Column(name="id_show", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $id_show;

    /**
     * @ORM\Column(name="start_hour", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $startHour;

    /**
     * @ORM\Column(name="end_hour", type="integer", nullable=true)
     *
     * @var mixed
     */
    protected $endHour;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=true)
     *
     * @var mixed
     */
    protected $date;

    /**
     * @ORM\Column(name="`repeat`", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $repeat;

    /**
     * @ORM\Column(name="`thumb`", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $thumb;

    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $type;

    /**
     * @ORM\Column(name="to_date", type="datetime", nullable=false)
     *
     * @var mixed
     */
    protected $toDate;

    /**
     * @ORM\Column(name="title", type="string", nullable=false)
     *
     * @var mixed
     */
    protected $title;

    /**
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     * @var mixed
     */
    protected $description;
    /**
     *
     * @ORM\Column(name="url", type="string", nullable=true)
     * @var mixed
     */
    protected $url;
    /**
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @var mixed
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSchedule")
     * @ORM\JoinColumn(name="id_model", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * @param mixed $endHour
     */
    public function setEndHour($endHour)
    {
        $this->endHour = $endHour;
    }

    /**
     * @return mixed
     */
    public function getIdModel()
    {
        return $this->id_model;
    }

    /**
     * @param mixed $id_model
     */
    public function setIdModel($id_model)
    {
        $this->id_model = $id_model;
    }

    /**
     * @return mixed
     */
    public function getIdShow()
    {
        return $this->id_show;
    }

    /**
     * @param mixed $id_show
     */
    public function setIdShow($id_show)
    {
        $this->id_show = $id_show;
    }

    /**
     * @return mixed
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * @param mixed $repeat
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;
    }

    /**
     * @return mixed
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @param mixed $startHour
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }

}
