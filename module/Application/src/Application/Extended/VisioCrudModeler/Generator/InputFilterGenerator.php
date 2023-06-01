<?php

namespace Application\Extended\VisioCrudModeler\Generator;

use VisioCrudModeler\Generator\InputFilterGenerator as VisioInputFilterGenerator;

use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;

class InputFilterGenerator extends VisioInputFilterGenerator
{

    /**
     * @inheritdoc
     */
    protected function generateFilter(DataSetDescriptorInterface $dataSet, $extends)
    {

        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Filter";
        $namespace = $this->params->getParam("moduleName") . "\\Filter";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        return $fullClassName;

    }

}