<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;

/**
 * generator class for building module structure
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ModuleGenerator implements GeneratorInterface
{

    /**
     * holds directory relative Paths
     *
     * @var array
     */
    protected $directories = array(
        'config',
        'language',
        'src',
        'src/%module%',
        'src/%module%/Controller',
        'src/%module%/Controller/Base',
        'src/%module%/Exception',
        'src/%module%/Filter',
        'src/%module%/Filter/BaseFilter',
        'src/%module%/Form',
        'src/%module%/Form/BaseForm',
        'src/%module%/Hydrator',
        'src/%module%/Hydrator/Strategy',
        'src/%module%/Model',
        'src/%module%/Model/BaseModel',
        'src/%module%/Table',
        'src/%module%/Table/BaseTable',
        'src/%module%/Grid',
        'src/%module%/Grid/BaseGrid'
    );

    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        $this->createDirectories();
        $this->createModuleClass();
        $config = $this->createModuleConfig();
        return array(
            'generatedConfigPath' => $config
        );
    }

    /**
     * creates directory structure
     */
    protected function createDirectories()
    {
        $root = $this->moduleRoot();
        $this->createDir($root);
        foreach ($this->directories as $dir) {
            $this->createDir($root . DIRECTORY_SEPARATOR . $dir);
        }
    }

    /**
     * creates directory
     *
     * @param string $dir
     * @throws \RuntimeException
     */
    protected function createDir($dir)
    {
        $dir = str_replace('%module%', $this->params->getParam('moduleName'), $dir);
        if (! file_exists($dir)) {
            $this->console('creating directory: ' . $dir);
            if (! mkdir($dir, 0777, true)) {
                throw new \RuntimeException('Could not create directory "' . $dir . '", please check that you have write permisions to create this path');
            }
        }
    }

    /**
     * creates module config files
     *
     * @return string returns config path
     */
    protected function createModuleConfig()
    {
        $config = array();
        $configPath = $this->moduleRoot() . '/config/module.config.php';
        if (file_exists($configPath)) {
            $config = require $configPath;
        }
        $moduleConfig = new FileGenerator();
        $moduleConfig->setDocBlock($this->getFileDocBlock());
        $moduleConfig->getDocBlock()->setShortDescription(sprintf($this->codeLibrary()
            ->get('module.standardConfigDescription'), $this->params->getParam('moduleName')));
        $moduleConfig->setBody('return ' . var_export($config, true) . ';');
        $this->console('writing module config file');
        file_put_contents($this->moduleRoot() . '/config/module.config.php', $moduleConfig->generate());
        return $configPath;
    }

    /**
     * creates Module class
     */
    protected function createModuleClass()
    {
        $moduleClassFilePath = $this->moduleRoot() . '/Module.php';
        if (! file_exists($moduleClassFilePath)) {
            $this->console('creating Module Class');
            $moduleClassFile = new FileGenerator();
            $moduleClassFile->setNamespace($this->params->getParam('moduleName'));
            $moduleClass = new ClassGenerator();
            $moduleClass->setDocBlock($this->getFileDocBlock())
                ->getDocBlock()
                ->setShortDescription($this->codeLibrary()
                ->get('module.moduleClassDescription'));
            $moduleClass->setName('Module');
            // onbootstrap
            $onBootstrapMethod = new MethodGenerator();
            $onBootstrapMethod->setName('onBootstrap')->setBody($this->codeLibrary()
                ->get('module.onBootstrap.body'));
            $onBootstrapMethod->setDocBlock(new DocBlockGenerator($this->codeLibrary()
                ->get('module.onBootstrap.shortdescription'), $this->codeLibrary()
                ->get('module.onBootstrap.longdescription'), array(
                new ParamTag('e', '\Zend\Mvc\MvcEvent')
            )));
            $eventParam = new ParameterGenerator('e', '\Zend\Mvc\MvcEvent');
            $onBootstrapMethod->setParameter($eventParam);
            $moduleClass->addMethodFromGenerator($onBootstrapMethod);
            // config
            $configMethod = new MethodGenerator('getConfig', array(), MethodGenerator::FLAG_PUBLIC, $this->codeLibrary()->get('module.config.body'), new DocBlockGenerator($this->codeLibrary()->get('module.config.shortdescription'), $this->codeLibrary()->get('module.config.longdescription'), array()));
            $moduleClass->addMethodFromGenerator($configMethod);
            // getAutoloaderConfig
            $getAutoloaderConfigMethod = new MethodGenerator('getAutoloaderConfig', array(), MethodGenerator::FLAG_PUBLIC, $this->codeLibrary()->get('module.getAutoloaderConfig.body'), new DocBlockGenerator($this->codeLibrary()->get('module.getAutoloaderConfig.shortdescription'), $this->codeLibrary()->get('module.getAutoloaderConfig.longdescription'), array()));
            $moduleClass->addMethodFromGenerator($getAutoloaderConfigMethod);
            // adding class method and generating file
            $moduleClassFile->setClass($moduleClass);
            file_put_contents($moduleClassFilePath, $moduleClassFile->generate());
        }
    }

    /**
     * returns filled doc block for file
     *
     * @return \Zend\Code\Generator\DocBlockGenerator
     */
    protected function getFileDocBlock()
    {
        $docBlock = new DocBlockGenerator();
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')));
        $docBlock->setTag(new GenericTag('project', $this->params->getParam('project')));
        $docBlock->setTag(new GenericTag('license', $this->params->getParam('license')));
        $docBlock->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        return $docBlock;
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
}
