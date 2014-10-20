<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;
use VisioCrudModeler\Descriptor\DataSourceDescriptorInterface;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;
use VisioCrudModeler\Generator\Config\Config;
use Zend\Filter\Word\CamelCaseToSeparator;

/**
 * generator class for creating Controller classes
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>, robertbodych
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ControllerGenerator implements GeneratorInterface
{

    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;

    /**
     *
     * @var \Zend\Filter\Word\UnderscoreToCamelCase
     */
    protected $underscoreToCamelCase;

    /**
     *
     * @var \Zend\Filter\Word\CamelCaseToSeparator
     */
    protected $camelCasToSeparator;

    /**
     *
     * @var string
     */
    protected $extendedController = 'Zend\Mvc\Controller\AbstractActionController';

    /**
     * constructor
     */
    public function __construct()
    {
        $this->underscoreToCamelCase = new UnderscoreToCamelCase();
        $this->camelCasToSeparator = new CamelCaseToSeparator('-');
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        $descriptor = $this->params->getParam("descriptor");
        if (! ($descriptor instanceof DataSourceDescriptorInterface)) {
            return;
        }

        $runtime = array();
        if ($params->getParam('runtimeConfiguration') instanceof Config) {
            $runtime = (array) $params->getParam('runtimeConfiguration')->get('controller');
        }

        foreach ($descriptor->listDataSets() as $name) {
            $dataSet = $descriptor->getDataSetDescriptor($name);
            if (! array_key_exists($name, $runtime)) {
                $runtime[$name] = array();
            }
            $runtime[$name]['baseController'] = $this->generateBaseController($dataSet);
            $runtime[$name]['controller'] = $this->generateController($dataSet, $runtime[$name]['baseController']);
            $this->console(sprintf('writing generated controllers for "%s" table', $name));
        }

        $this->updateModuleConfiguration($runtime);

        return $runtime;
    }

    /**
     * modifies module configuration to enable acces to generated controllers
     *
     * @param array $runtime
     */
    protected function updateModuleConfiguration(array $runtime)
    {
        if ($this->params->getParam('runtimeConfiguration') instanceof Config) {
            $generatedConfigPath = (string) $this->params->getParam('runtimeConfiguration')->get('module')['generatedConfigPath'];
            if (file_exists($generatedConfigPath)) {
                $config = require $generatedConfigPath;
                $routeBase = strtolower(preg_replace('@[^a-z]*@i', '', $this->params->getParam('moduleName')));
                if (! isset($config['router']['routes'][$routeBase])) {
                    $config['router']['routes'][$routeBase] = array();
                }
                $config['router']['routes'][$routeBase] = array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/' . $routeBase,
                        'defaults' => array(
                            '__NAMESPACE__' => $this->params->getParam('moduleName') . '\Controller',
                            'controller' => 'Index',
                            'action' => 'index'
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
                foreach ($runtime as $name => $controllerClasses) {
                    $invokableControllerName = preg_replace('@^\\\\@', '', $controllerClasses['controller']);
                    $invokableControllerKey = preg_replace('@Controller$@', '', $invokableControllerName);
                    $config['controllers']['invokables'][$invokableControllerKey] = $invokableControllerName;
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
     * writes module config
     *
     * @param array $config
     * @param string $path
     */
    protected function writeModuleConfig(array $config, $path)
    {
        $moduleName = $this->params->getParam('moduleName');
        $generatorConfig = new FileGenerator();
        $docBlock = new DocBlockGenerator();
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')));
        $docBlock->setTag(new GenericTag('project', $this->params->getParam('project')));
        $docBlock->setTag(new GenericTag('license', $this->params->getParam('license')));
        $docBlock->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $docBlock->setShortDescription(sprintf($this->codeLibrary()
            ->get('module.generatedConfigDescription'), $moduleName));
        $generatorConfig->setDocBlock($docBlock);
        $configString = var_export($config, true);
        $configString = str_replace("'/../view'", '__DIR__ . \'/../view\'', $configString);
        $generatorConfig->setBody('return ' . $configString . ';');
        file_put_contents($path, $generatorConfig->generate());
    }

    /**
     * handles generating main controller class, if don't exists
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $extends
     * @return string generated controller full class name
     */
    protected function generateController(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . 'Controller';
        $namespace = $this->params->getParam("moduleName") . "\\Controller";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $controllerFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Controller/" . $className . ".php";
        if (file_exists($controllerFilePath)) {
            return $fullClassName;
        }
        $moduleName = $this->params->getParam("moduleName");

        $file = new FileGenerator();
        $file->setFilename($className);
        $file->setNamespace($namespace);

        $class = new ClassGenerator();
        $class->setName($className)->setExtendedClass($extends);

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('controller.standardControllerDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $file->setClass($class);

        file_put_contents($controllerFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates base controller for given dataSet
     *
     * @param DataSetDescriptorInterface $dataSet
     * @return string generated controller full class name
     */
    protected function generateBaseController(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();

        $className = 'Base' . $this->underscoreToCamelCase->filter($name) . 'Controller';
        $namespace = $this->params->getParam("moduleName") . '\Controller\Base';
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $moduleName = $this->params->getParam("moduleName");

        $fileBase = new FileGenerator();
        $fileBase->setFilename($className);
        $fileBase->setNamespace($namespace);

        $class = new ClassGenerator();
        $class->setName($className)->setExtendedClass('\\' . $this->extendedController);

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('controller.standardBaseControllerDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $this->addControllerMethods($class, $dataSet);

        $fileBase->setClass($class);

        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Controller/Base/" . $className . ".php";
        file_put_contents($modelClassFilePath, $fileBase->generate());
        return $fullClassName;
    }

    /**
     * adds standard CRUD methods to given class
     *
     * @param ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function addControllerMethods(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'createAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'listAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'ajaxListAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'updateAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'deleteAction'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'getAdapter'));
        $class->addMethodFromGenerator($this->generateMethod($dataSet, 'getTable'));

        $htmlResponse = $this->generateMethod($dataSet, 'htmlResponse');
        $htmlResponse->setParameter('html');

        $class->addMethodFromGenerator($htmlResponse);
    }

    /**
     * returns generated method
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $methodName
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function generateMethod(DataSetDescriptorInterface $dataSet, $methodName)
    {
        $method = new MethodGenerator();
        $method->setName($methodName);

        $docBlock = new DocBlockGenerator($this->codeLibrary()->get('controller.' . $methodName . '.description'));
        $method->setDocBlock($docBlock);
        $substitutionData = $this->prepareTemplateSubstitutionData($dataSet);
        $method->setBody(strtr((string) $this->codeLibrary()
            ->get('controller.' . $methodName . '.body'), $substitutionData));
        return $method;
    }

    /**
     * returns values map for template according to given dataSet
     *
     * @param DataSetDescriptorInterface $dataSet
     * @return array
     */
    protected function prepareTemplateSubstitutionData(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();
        $data = array();
        if ($this->params->getParam('runtimeConfiguration') instanceof Config) {
            $runtime = (array) $this->params->getParam('runtimeConfiguration')->toArray();
            $data['%table%'] = $runtime['model'][$name]['table'];
            $data['%model%'] = $runtime['model'][$name]['model'];
            $data['%form%'] = $runtime['form'][$name]['form'];
            $data['%filter%'] = $runtime['inputFilter'][$name]['filter'];
            $data['%grid%'] = $runtime['form'][$name]['grid'];
            $data['%filteredModule%'] = strtolower($this->params->getParam('moduleName'));
            $data['%filteredController%'] = strtolower($this->camelCasToSeparator->filter($this->underscoreToCamelCase->filter($dataSet->getName())));
            $data['%adapterKey%'] = $this->params->getParam('adapterServiceKey');
        }
        return $data;
    }

    /**
     * returns code library
     *
     * @return CodeLibrary
     */
    protected function codeLibrary()
    {
        return $this->params->getParam('di')->get('VisioCrudModeler\Generator\CodeLibrary');
    }

    /**
     * proxy method for writing to console
     *
     * @param string $message
     */
    protected function console($message)
    {
        if ($this->params->getParam('console') instanceof \Zend\Console\Adapter\AdapterInterface) {
            $this->params->getParam('console')->writeLine($message);
        }
    }

    /**
     * returns absolute path to module directory
     *
     * @return string
     */
    protected function moduleRoot()
    {
        $modulesDirectory = $this->params->getParam('modulesDirectory');
        $moduleName = $this->params->getParam('moduleName');
        return $modulesDirectory . DIRECTORY_SEPARATOR . $moduleName;
    }
}
