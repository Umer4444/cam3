<?php

namespace Images\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AlbumCoverImage
 * @ORM\Entity
 */
class AlbumCoverImage extends Image
{

    function __toString()
    {
        return $this->filename;
    }

}