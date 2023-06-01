<?php

namespace PerfectWeb\Core\Traits;

trait Tags
{

    /**
     * @var string|array
     */
    protected $tags;

    /**
     * @return array|string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

}