<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class PhotoThumbHelper
 * @package Application\View\Helper
 */
class PhotoThumbHelper extends AbstractHelper
{

    /**
     * /...e63e860.jpg  --> ...e63e860_t.jpg
     * @param $filename
     * @param string $size
     * @return bool|string
     */
    public function __invoke($filename, $size = 't')
    {

        switch ($size) {
            case 't':
                return substr($filename, 0, -4) . '_t' . substr($filename, -4);
                break;

            case 'other':
                return substr($filename, 0, -4) . '_other' . substr($filename, -4);
                break;
        }
        return false;

    }

}
