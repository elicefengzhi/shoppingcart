<?php
namespace VisioCrudModeler\Generator;

use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Filter\Word\UnderscoreToCamelCase;
use VisioCrudModeler\Descriptor\DataSetDescriptorInterface;
use VisioCrudModeler\Descriptor\FieldDescriptorInterface;
use VisioCrudModeler\Generator\Config\Config;

/**
 * generator class for creating InputFilter classes
 *
 * @author Jacek Pawelec <jacek.pawelec@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class InputFilterGenerator implements GeneratorInterface
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
     * namespace of abstract filter for all other filters
     *
     * @var string
     */
    protected $baseFilterParent = "\\VisioCrudModeler\\Filter\\AbstractFilter";

    /**
     *
     * @var array
     */
    protected $baseFilterUses = array();

    /**
     * constructor
     */
    public function __construct()
    {
        $this->underscoreToCamelCase = new UnderscoreToCamelCase();
    }

    /**
     * (non-PHPdoc)
     *
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
            $runtime = (array) $params->getParam('runtimeConfiguration')->get('filter');
        }

        foreach ($descriptor->listGenerator() as $name => $dataSet) {
            if (! array_key_exists($name, $runtime)) {
                $runtime[$name] = array();
            }
            $runtime[$name]['baseFilter'] = $this->generateBaseFilter($dataSet);
            $runtime[$name]['filter'] = $this->generateFilter($dataSet, $runtime[$name]['baseFilter']);

            $this->console(sprintf('writing generated filter for "%s" table', $name));
        }

        return $runtime;
    }

    /**
     * generates file with target filter (if not exists yet)
     *
     * @param DataSetDescriptorInterface $dataSet
     * @param string $extends
     *            base class for filter
     * @return string full name of generated class
     */
    protected function generateFilter(DataSetDescriptorInterface $dataSet, $extends)
    {
        $name = $dataSet->getName();
        $className = $this->underscoreToCamelCase->filter($name) . "Filter";
        $namespace = $this->params->getParam("moduleName") . "\\Filter";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $filterClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Filter/" . $className . ".php";
        if (file_exists($filterClassFilePath)) {
            return $fullClassName;
        }

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($extends);

        $file = new FileGenerator();

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('filter.standardConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));

        $file->setClass($class)->setDocBlock($docBlock);

        file_put_contents($filterClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates code for base filter and saves in file (overrides file if exists)
     *
     * @param DataSetDescriptorInterface $dataSet
     */
    protected function generateBaseFilter(DataSetDescriptorInterface $dataSet)
    {
        $name = $dataSet->getName();
        $className = "Base" . $this->underscoreToCamelCase->filter($name) . "Filter";
        $namespace = $this->params->getParam("moduleName") . "\\Filter\\BaseFilter";
        $fullClassName = '\\' . $namespace . '\\' . $className;

        $class = new ClassGenerator();
        $class->setName($className);
        $class->setNamespaceName($namespace);
        $class->setExtendedClass($this->baseFilterParent);

        foreach ($this->baseFilterUses as $use) {
            $class->addUse($use);
        }

        $this->generateConstructor($class, $dataSet);

        $file = new FileGenerator();

        $docBlock = new \Zend\Code\Generator\DocblockGenerator(sprintf($this->codeLibrary()->get('filter.generatedConfigDescription'), $name));
        $docBlock->setTag(new GenericTag('author', $this->params->getParam('author')))
            ->setTag(new GenericTag('project', $this->params->getParam('project')))
            ->setTag(new GenericTag('license', $this->params->getParam('license')))
            ->setTag(new GenericTag('copyright', $this->params->getParam('copyright')));

        $file->setClass($class)->setDocBlock($docBlock);

        $modelClassFilePath = $this->moduleRoot() . "/src/" . $this->params->getParam("moduleName") . "/Filter/BaseFilter/" . $className . ".php";
        file_put_contents($modelClassFilePath, $file->generate());
        return $fullClassName;
    }

    /**
     * generates propertiy, getter, setter and other methods...
     *
     * @param \Zend\Code\Generator\ClassGenerator $class
     * @param \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor $dataSet
     */
    protected function generateConstructor(ClassGenerator $class, \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor $dataSet)
    {
        $constructor = new MethodGenerator("__construct");
        $constructor->addFlag(MethodGenerator::FLAG_PUBLIC);

        $methodBody = $this->codeLibrary()->get("filter.constructor.body.begin");

        foreach ($dataSet->listGenerator() as $column) {
            $methodBody .= $this->generateFilterForColumn($column);
        }

        $constructor->setBody($methodBody);

        $class->addMethodFromGenerator($constructor);
    }

    /**
     * generates filters and validators for column
     *
     * @param FieldDescriptorInterface $column
     * @return string code for columd data
     */
    protected function generateFilterForColumn(FieldDescriptorInterface $column)
    {
        $columnInfo = $column->info();

        $fieldFilter = $this->codeLibrary()->get("filter.constructor.body.input");

        $name = $column->getName();
        if (isset($columnInfo["validators"]) && $columnInfo["validators"] != null) {
            $required = "false";
            foreach ($columnInfo["validators"] as $validator) {
                if ($validator["type"] == "required") {
                    $required = (isset($column->info()['null']) && $column->info()['null']) ? 'false' : 'true';
                    break;
                }
            }
        } else {
            $required = (isset($column->info()['null']) && $column->info()['null']) ? 'false' : 'true';
        }

        $type = $this->getFieldType($column);
        if (isset($columnInfo["filters"]) && $columnInfo["filters"] != null) {
            $filters = "";
            foreach ($columnInfo["filters"] as $filter) {
                $filters .= sprintf("\n            array('name' => '%s'),", $this->underscoreToCamelCase->filter(preg_replace("/[^a-z0-9]/i", "_", $filter["type"])));
            }
            $filters .= "\n        ";
        } else {
            $filters = $this->codeLibrary()->get('filter.constructor.fieldFilter' . ucfirst($type));
        }

        $validators = $this->generateValidators($column);
        return sprintf($fieldFilter, $name, $required, $filters, $validators);
    }

    /**
     * generates validators array code
     *
     * @param FieldDescriptorInterface $column
     * @return string Code for validators
     */
    protected function generateValidators(FieldDescriptorInterface $column)
    {
        $columnInfo = $column->info();
        if (isset($columnInfo["validators"]) && $columnInfo["validators"] != null) {
            return sprintf($this->codeLibrary()->get("filter.constructor.validators"), $this->buildValidatorsFromInfo($columnInfo["validators"]));
        }

        $validators = "";
        switch ($this->getFieldType($column)) {
            case "int":
                $validators .= $this->codeLibrary()->get("filter.constructor.validators.digits");
                break;
            case "float":
                break;
            case "string":
                $validator = $this->codeLibrary()->get("filter.constructor.validators.stringLenght");
                $characterLength = (isset($columnInfo["character_maximum_length"])) ? (isset($columnInfo["character_maximum_length"])) : 256;
                $validator = sprintf($validator, ((isset($columnInfo["null"]) && $columnInfo["null"]) ? 0 : 1), $characterLength);
                $validators .= $validator;
                break;
        }

        return sprintf($this->codeLibrary()->get("filter.constructor.validators"), $validators);
    }

    /**
     * used to build validators from optionall field info
     *
     * @param array $incomeValidators
     * @return string
     */
    protected function buildValidatorsFromInfo($incomeValidators)
    {
        $outcomeValidators = "";
        foreach ($incomeValidators as $validator) {
            if ($validator["type"] == "required") {
                continue;
            }
            $outcomeValidators .= "
            array (
                'name' => '" . $this->underscoreToCamelCase->filter(preg_replace("/[^a-z0-9]/i", "_", $validator["type"])) . "',";
            if (isset($validator["options"])) {
                $outcomeValidators .= "
                'options' => array(";
                foreach ($validator["options"] as $key => $value) {
                    $outcomeValidators .= "
                    '$key' => '$value',";
                }
                $outcomeValidators .= "
                ),";
            }
            $outcomeValidators .= "
            ),";
        }

        return $outcomeValidators;
    }

    /**
     * gets field type for PHP
     *
     * @param FieldDescriptorInterface $column
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