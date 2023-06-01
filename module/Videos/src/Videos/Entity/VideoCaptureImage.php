<?php

namespace Videos\Entity;

use Doctrine\ORM\Mapping as ORM;
use Images\Entity\Photo;

/**
 * @ORM\Entity
 */
class VideoCaptureImage extends Photo
{

    /**
     * @ORM\ManyToOne(targetEntity="Video", inversedBy="captures")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $video;

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param $video
     *
     * @return $this
     */
    public function setVideo($video)
    {
        $this->video = $video;
        return $this;
    }

}