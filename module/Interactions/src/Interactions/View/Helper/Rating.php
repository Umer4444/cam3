<?php

namespace Interactions\View\Helper;

class Rating extends Interaction
{

    const SIZE_SMALL = 'small';
    const SIZE_BIG = 'big';

    protected $options = array(
        'size' => self::SIZE_BIG
    );

    protected $template = 'rating';

    function small()
    {
        $this->options['size'] = self::SIZE_SMALL;
        return $this;
    }

    function big()
    {
        $this->options['size'] = self::SIZE_BIG;
        return $this;
    }

}