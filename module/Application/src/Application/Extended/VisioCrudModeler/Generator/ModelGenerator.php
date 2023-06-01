<?php

namespace Application\Extended\VisioCrudModeler\Generator;

use VisioCrudModeler\Generator\ModelGenerator as VisioModelGenerator;

use VisioCrudModeler\Generator\ParamsInterface;

class ModelGenerator extends VisioModelGenerator
{

    /**
     * @inheritdoc
     */
    public function generate(ParamsInterface $params)
    {
        return false;
    }

}