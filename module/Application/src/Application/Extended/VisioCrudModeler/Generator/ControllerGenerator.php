<?php

namespace Application\Extended\VisioCrudModeler\Generator;

use VisioCrudModeler\Generator\ControllerGenerator as VisioControllerGenerator;

use VisioCrudModeler\Generator\Config\Config;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;

class ControllerGenerator extends VisioControllerGenerator
{

    /**
     * @inheritdoc
     */
    protected $extendedController = 'Crud\Controller\ActionController';

    /**
     * @inheritdoc
     */
    protected function prepareTemplateSubstitutionData(DataSetDescriptorInterface $dataSet)
    {
        $data = parent::prepareTemplateSubstitutionData($dataSet);
        $data['%entity%'] = '\Application\Entity\\'.$this->underscoreToCamelCase->filter($dataSet->getName());

        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function addControllerMethods(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {

        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'createAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'listAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'ajaxListAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'updateAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'deleteAction'));

        $htmlResponse = $this->generateMethod($dataSet, 'htmlResponse');
        $htmlResponse->setParameter('html');

        $class->addMethodFromGenerator($htmlResponse);

    }

    /**
     * @inheritdoc
     */
    protected function updateModuleConfiguration(array $runtime)
    {

        if ($this->params->getParam('runtimeConfiguration') instanceof Config) {
            $generatedConfigPath = (string) $this->params->getParam('runtimeConfiguration')->get('module')['generatedConfigPath'];

            if (file_exists($generatedConfigPath)) {
                $config = require $generatedConfigPath;
                $routeBase = strtolower(preg_replace('@[^a-z]*@i', '', $this->params->getParam('moduleName')));
                if (! isset($config['router']['routes']['zfcadmin']['child_routes'][$routeBase])) {
                    $config['router']['routes']['zfcadmin']['child_routes'][$routeBase] = array();
                }
                $config['router']['routes']['zfcadmin']['child_routes'][$routeBase] = array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/' . $routeBase,
                        'defaults' => array(
                            '__NAMESPACE__' => $this->params->getParam('moduleName') . '\Controller',
                            'controller' => 'Index',
                            'action' => 'list'
                        )
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' => '/[:controller[/:action]][/:id]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                                ),
                                'defaults' => array()
                            )
                        )
                    )
                );

                $config['controllers']['abstract_factories'][0] = 'Crud\Controller\CrudControllerFactory';
                $config['service_manager']['abstract_factories'] = [
                    'Crud\Form\CrudFormFactory',
                    'Crud\Grid\CrudGridFactory',
                    'Crud\Filter\CrudFilterFactory',
                    'Crud\View\CrudViewTemplateFactory'
                ];
                $config['view_manager']['template_map']['crud/default/list'] = "/crud/default/list.phtml";
                $config['view_manager']['template_map']['crud/default/create'] = '/crud/default/create.phtml';
                $config['view_manager']['template_map']['crud/default/update'] = '/crud/default/update.phtml';
                $config['view_manager']['template_map']['crud/default/ajax-list'] = '/crud/default/ajax-list.phtml';

                foreach ($runtime as $name => $controllerClasses) {

                    $invokableControllerName = preg_replace('@^\\\\@', '', $controllerClasses['controller']);

                    if (in_array($invokableControllerName, $config['controllers']['invokables'])) {
                        continue;
                    }

                    $config['controllers']['invokables'][] = $invokableControllerName;

                }
                $config['view_manager']['template_path_stack'] = array(
                    '/../view'
                );

                $this->console('writing controller routes...');
                $this->writeModuleConfig($config, $generatedConfigPath);
                $this->console('routes written to: ' . $generatedConfigPath);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function writeModuleConfig(array $config, $path)
    {

        $configString = var_export($config, true);
        $configString = str_replace(
            ["'/../view'", '\'/crud/default'],
            ['__DIR__ . \'/../view\'', '__DIR__ . \'/../view/crud/default'], $configString
        );

        $generatorConfig = new FileGenerator();
        $generatorConfig->setBody('return ' . $configString . ';');

        file_put_contents($path, $generatorConfig->generate());

    }

    /**
     * @inheritdoc
     */
    protected function generateBaseController(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();

        $className = 'Base' . $this->underscoreToCamelCase->filter($name) . 'Controller';
        $namespace = $this->params->getParam("moduleName") . '\Controller\Base';
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $fileBase = new FileGenerator();
        $fileBase->setFilename($className);
        $fileBase->setNamespace($namespace);

        $class = new ClassGenerator();
        $class->setName($className)->setExtendedClass('\\' . $this->extendedController);
        $class->addProperty('entity', '\Application\Entity\\'.$this->underscoreToCamelCase->filter($name));

        $this->addControllerMethods($class, $dataSet);

        $fileBase->setClass($class);

        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Controller/Base/" . $className . ".php";
        file_put_contents($modelClassFilePath, $fileBase->generate());
        return $fullClassName;
    }

    /**
     * @inheritdoc
     */
    protected function generateController(DataSetDescriptorInterface $dataSet, $extends)
    {

        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . 'Controller';
        $namespace = $this->params->getParam("moduleName") . "\\Controller";
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

}