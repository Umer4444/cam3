<?php

namespace Application\Extended\VisioCrudModeler\Generator;

use VisioCrudModeler\Generator\FormGenerator as VisioFormGenerator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;

class FormGenerator extends VisioFormGenerator
{

    /**
     * @inheritdoc
     */
    protected function generateForm(DataSetDescriptorInterface $dataSet, $extends)
    {

        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Form";
        $namespace = $this->params->getParam("moduleName") . "\\Form";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        return $fullClassName;

    }

    /**
     * @inheritdoc
     */
    protected function codeLibrary()
    {
        return $this->params->getParam('di')->get('Application\Extended\VisioCrudModeler\Generator\CodeLibrary');
    }

    /**
     * @inheritdoc
     */
    protected function generateGrid(DataSetDescriptorInterface $dataSet, $extends)
    {

        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Grid";
        $namespace = $this->params->getParam("moduleName") . "\\Grid";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        return $fullClassName;

    }

    /**
     * @inheritdoc
     */
    protected function generateInitFiltersMehod(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $body = "";
        foreach ($dataSet->listGenerator() as $column) {
            $name = $column->getName();
            $type = $this->getFieldType($column);
            $body .= sprintf($this->codeLibrary()->get('grid.initFilters.' . $type), $name, $name);
        }

        $method = new MethodGenerator("initFilters");
        $method->setBody($body);
        $method->setFlags(\Zend\Code\Generator\MethodGenerator::FLAG_PROTECTED);

        $parameter = new \Zend\Code\Generator\ParameterGenerator("query");

        $method->setParameter($parameter);
        $class->addMethodFromGenerator($method);
    }

    /**
     * @inheritdoc
     */
    protected function generateInitMehod(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {

        $filter = new \Zend\Filter\Word\UnderscoreToSeparator('-');

        $controller = '/' . strtolower($this->params->getParam("moduleName")) . "/" .$filter->filter($dataSet->getName());
        $editLink = $controller . "/update/%s";
        $deleteLink = $controller . "/delete/%s";

        $body = sprintf($this->codeLibrary()->get('grid.init.body'), $editLink, $dataSet->getPrimaryKey(), $deleteLink, $dataSet->getPrimaryKey());

        $method = new MethodGenerator("init");
        $method->setBody($body);
        $method->setFlags(\Zend\Code\Generator\MethodGenerator::FLAG_PUBLIC);

        $class->addMethodFromGenerator($method);
    }

    /**
     * @inheritdoc
     */
    protected function generateConfigProperty(ClassGenerator $class)
    {
        $property = new \Zend\Code\Generator\PropertyGenerator("config");
        $property->setFlags(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED);
        $property->setDefaultValue(array(
            'name' => '',
            'showPagination' => true,
            'showQuickSearch' => false,
            'showItemPerPage' => true,
            'itemCountPerPage' => 10,
            'showColumnFilters' => false
        ));

        $class->addPropertyFromGenerator($property);
    }

}