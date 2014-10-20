<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;
use VisioCrudModeler\Descriptor\AbstractFieldDescriptor;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;
use VisioCrudModeler\Descriptor\FieldDescriptorInterface;
use VisioCrudModeler\Descriptor\ReferenceFieldInterface;
use VisioCrudModeler\Generator\Config\Config;

/**
 * generator class for creating Model classes and table gateways
 *
 * @author Jacek Pawelec <jacek.pawelec@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ModelGenerator implements GeneratorInterface
{

    /**
     * holds params instance
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface
     */
    protected $params = null;

    /**
     *
     * @var Zend\Filter\Word\UnderscoreToCamelCase
     */
    protected $underscoreToCamelCase;

    /**
     *
     * @var string
     */
    protected $baseModelParent = "\\VisioCrudModeler\\Model\\TableGateway\\Entity\\AbstractEntity";

    /**
     *
     * @var string
     */
    protected $baseTablegatewayParent = "\\VisioCrudModeler\\Model\\TableGateway\\AbstractTable";

    /**
     * holds list of "use" operator for base model
     *
     * @var array
     */
    protected $baseModelUses = array(
        "Zend\\EventManager\\EventManagerInterface"
    );

    /**
     * base model dummy names to use as action triggers
     *
     * @var array
     */
    protected $baseModelEventsMethods = array(
        'preInsert',
        'postInsert',
        'preUpdate',
        'postUpdate',
        'preDelete',
        'postDelete'
    );

    /**
     * constructor
     */
    public function __construct()
    {
        $this->underscoreToCamelCase = new UnderscoreToCamelCase();
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\GeneratorInterface::generate()
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;

        $descriptor = $this->params->getParam("descriptor");

        if (! ($descriptor instanceof \VisioCrudModeler\Descriptor\ListGeneratorInterface)) {
            return;
        }

        $runtime = array();
        if ($params->getParam('runtimeConfiguration') instanceof Config) {
            $runtime = (array) $params->getParam('runtimeConfiguration')->get('model');
        }

        foreach ($descriptor->listGenerator() as $name => $dataSet) {
            if (! array_key_exists($name, $runtime)) {
                $runtime[$name] = array();
            }
            $runtime[$name]['baseModel'] = $this->generateBaseModel($dataSet);
            $runtime[$name]['model'] = $this->generateModel($dataSet, $runtime[$name]['baseModel']);

            $runtime[$name]['baseTable'] = $this->generateBaseTableGateway($dataSet, $runtime[$name]['model']);
            $runtime[$name]['table'] = $this->generateTableGateway($dataSet, $runtime[$name]['baseTable']);

            $this->console(sprintf('writing generated model for "%s" table', $name));
        }

        return $runtime;
    }

    /**
     * generates code for base table gateway and saves in file (overrides file if exists)
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $entityPrototype
     * @return string name of generated class
     */
    protected function generateBaseTableGateway(DataSetDescriptorInterface $dataSet, $entityPrototype)
    {
        $name = $dataSet->getName();

        $camelName = $this->underscoreToCamelCase->filter($name);
        $className = "Base" . $camelName . "Table";
        $namespace = $this->params->getParam("moduleName") . "\\Table\\BaseTable";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setExtendedClass($this->baseTablegatewayParent);
        $class->setNamespaceName($namespace);

        $tableProperty = new \Zend\Code\Generator\PropertyGenerator();
        $tableProperty->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED)
            ->setName("table")
            ->setDefaultValue($name);
        $class->addPropertyFromGenerator($tableProperty);

        $arrayObjectPrototypeClassProperty = new \Zend\Code\Generator\PropertyGenerator();
        $arrayObjectPrototypeClassProperty->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED)
            ->setName("arrayObjectPrototypeClass")
            ->setDefaultValue($entityPrototype);
        $class->addPropertyFromGenerator($arrayObjectPrototypeClassProperty);

        $this->setupPrimaryKey($class, $dataSet);

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('table.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $file = new FileGenerator();
        $file->setClass($class);

        $tableClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Table/BaseTable/" . $className . ".php";
        file_put_contents($tableClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * setups primary key for tabel acording to dataset fields descriptor
     *
     * @param ClassGenerator $class
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function setupPrimaryKey(ClassGenerator $class, DataSetDescriptorInterface $dataSet)
    {
        foreach ($dataSet->listFields() as $name) {
            $field = $dataSet->getFieldDescriptor($name);
            if ($field instanceof AbstractFieldDescriptor && strpos($field->info(AbstractFieldDescriptor::KEY), 'PRI') !== false) {
                $keyNameProperty = new \Zend\Code\Generator\PropertyGenerator();
                $keyNameProperty->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED)->setName('keyName');
                $keyNameProperty->setDefaultValue($name);
                $class->addPropertyFromGenerator($keyNameProperty);
                break;
            }
        }
    }

    /**
     * generates file with target table gateway (if not exists yet)
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $extends
     *            base class for table gateway
     * @return string name of generated class
     */
    protected function generateTableGateway(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Table";
        $namespace = $this->params->getParam("moduleName") . "\\Table";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $tableClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Table/" . $className . ".php";
        if (file_exists($tableClassFilePath)) {
            return $fullClassName;
        }

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($extends);

        $file = new FileGenerator();

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('table.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $file->setClass($class);

        file_put_contents($tableClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates file with target model (if not exists yet)
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $extends
     *            base class for model
     * @return string name of generated class
     */
    protected function generateModel(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name);
        $namespace = $this->params->getParam("moduleName") . "\\Model";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Model/" . $className . ".php";
        if (file_exists($modelClassFilePath)) {
            return $fullClassName;
        }

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($extends);

        $file = new FileGenerator();

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $file->setClass($class);

        file_put_contents($modelClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates code for base model and saves in file (overrides file if exists)
     *
     * @param DataSetDescriptorInterface $dataSet
     * @return string name of generated class
     */
    protected function generateBaseModel(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();
        $className = "Base" . $this->underscoreToCamelCase->filter($name);
        $namespace = $this->params->getParam("moduleName") . "\\Model\\BaseModel";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($this->baseModelParent);

        foreach ($this->baseModelUses as $use) {
            $class->addUse($use);
        }

        $this->addEventsMethods($class);

        foreach ($dataSet->listFields() as $name) {
            $column = $dataSet->getFieldDescriptor($name);
            $this->generateColumnRelatedElements($class, $column);
        }

        $file = new FileGenerator();

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));
        $class->setDocBlock($docBlock);

        $file->setClass($class);

        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Model/BaseModel/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates propertiy, getter, setter and other methods...
     *
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param FieldDescriptorInterface $column
     */
    protected function generateColumnRelatedElements(ClassGenerator $class, FieldDescriptorInterface $column)
    {
        $name = preg_replace("/[^a-z0-9_]/i", "_", $column->getName());
        $name = $this->underscoreToCamelCase->filter($name);

        $property = $this->createProperty($column, $name);
        $class->addPropertyFromGenerator($property);

        $getMethod = $this->createGetMethod($column, $name);
        $class->addMethodFromGenerator($getMethod);

        $setMethod = $this->createSetMethod($column, $name);
        $class->addMethodFromGenerator($setMethod);
    }

    /**
     * generates empty methods for events to be overwriten in model
     *
     * @param \Zend\Code\Generator\ClassGenerator $class
     */
    protected function addEventsMethods(ClassGenerator $class)
    {
        foreach ($this->baseModelEventsMethods as $methodName) {
            $method = new MethodGenerator();
            $method->setName($methodName);

            $parameter = new \Zend\Code\Generator\ParameterGenerator();
            $parameter->setName("eventManager");
            $parameter->setType("EventManagerInterface");
            $method->setParameter($parameter);

            $docblock = new \Zend\Code\Generator\DocblockGenerator($this->codeLibrary()->get('model.' . $methodName . '.description'));
            $docblock->setTag(array(
                "name" => "param",
                "description" => "EventManagerInterface"
            ));
            $method->setDocBlock($docblock);

            $class->addMethodFromGenerator($method);
        }
    }

    /**
     * generates get function for field
     *
     * @param \VisioCrudModeler\Descriptor\FieldDescriptorInterface $column
     * @param string $name
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function createGetMethod(FieldDescriptorInterface $column, $name)
    {
        $propertyName = lcfirst($name);
        $method = new MethodGenerator();
        $type = $this->getFieldType($column);
        $method->setBody(sprintf($this->codeLibrary()
            ->get('model.getMethod' . ucfirst($type) . '.body'), $propertyName));
        $method->setName("get" . $name);

        $docblock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.getMethod.description'), $propertyName));
        $docblock->setTag(array(
            "name" => "return",
            "description" => $type
        ));
        $method->setDocBlock($docblock);

        return $method;
    }

    /**
     * creates setter method for given field
     *
     * @param FieldDescriptorInterface $column
     * @param string $name
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function createSetMethod(FieldDescriptorInterface $column, $name)
    {
        $propertyName = lcfirst($name);
        $parameter = new \Zend\Code\Generator\ParameterGenerator();
        $parameter->setName("value");
        $method = new MethodGenerator();
        $method->setParameter($parameter);
        $type = $this->getFieldType($column);
        $method->setBody(sprintf($this->codeLibrary()
            ->get('model.setMethod' . ucfirst($type) . '.body'), $propertyName));
        $method->setName("set" . $name);

        $docblock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('model.setMethod.description'), $propertyName));
        $docblock->setTag(array(
            "name" => "param",
            "description" => $type
        ));
        $method->setDocBlock($docblock);

        return $method;
    }

    /**
     * creates property for given field
     *
     * @param FieldDescriptorInterface $column
     * @param string $name
     * @return \Zend\Code\Generator\PropertyGenerator
     */
    protected function createProperty(FieldDescriptorInterface $column, $name)
    {
        $type = $this->getFieldType($column);
        $docblock = new \Zend\Code\Generator\DocblockGenerator('Column: ' . $column->getName());
        $docblock->setTag(array(
            "name" => "var",
            "description" => $type
        ));

        if ($column instanceof ReferenceFieldInterface) {
            if ($column->referencedFieldName()) {
                $docblock->setLongDescription("Reference to " . $column->referencedDataSetName() . "." . $column->referencedFieldName());
            }
        }

        $property = new \Zend\Code\Generator\PropertyGenerator();
        $property->addFlag(\Zend\Code\Generator\PropertyGenerator::FLAG_PUBLIC);
        $property->setName(lcfirst($name));
        $property->setDocBlock($docblock);

        return $property;
    }

    /**
     * gets field type for PHP
     *
     * @param \VisioCrudModeler\Descriptor\FieldDescriptorInterface $column
     * @return string
     */
    protected function getFieldType(FieldDescriptorInterface $column)
    {
        switch (strtolower($column->getType())) {
            case "int":
            case "integer":
            case "smallint":
            case "tinyint":
                return "int";
            case "number":
            case "double":
            case "real":
            case "float":
                return "float";
            case "bool":
            case "boolean":
                return "bool";
        }

        return "string";
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