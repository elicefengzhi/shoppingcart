<?php
namespace VisioCrudModeler\Descriptor\Web;

use VisioCrudModeler\Descriptor\AbstractDataSetDescriptor;
use VisioCrudModeler\Exception\FieldNotFound;
use VisioCrudModeler\Exception\InformationNotFound;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;


/**
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class WebDataSetDescriptor extends AbstractDataSetDescriptor implements ListGeneratorInterface
{

    /**
     * holds intances of dataset field descriptors
     *
     * @var \ArrayObject
     */
    protected $fieldDescriptors = null;

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::getName()
     */
    public function getName()
    {
        return $this->definition['name'];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::getType()
     */
    public function getType()
    {
        return $this->definition['type'];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::getPrimaryKey()
     */
    public function getPrimaryKey()
    {
        return (isset($this->definition['primaryKey'])) ? $this->definition['primaryKey'] : 'id';
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor::listFields()
     */
    public function listFields()
    {
        return array_keys($this->definition['fields']);
    }

     /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\Descriptor\DataSetDescriptorInterface::getFields()
     */
    public function getFields()
    {
        return $this->definition['fields'];
    }
    
    /**
     * returns field descriptor
     *
     * @throws \VisioCrudModeler\Exception\FieldNotFound
     * @return DbFieldDescriptor
     */
    public function getFieldDescriptor($fieldName)
    {
        if (! array_key_exists($fieldName, $this->definition['fields'])) {
            throw new FieldNotFound("field '" . $fieldName . "' not found in '" . $this->getName() . "'");
        }
        if (! $this->fieldDescriptors->offsetExists($fieldName)) {
            $this->fieldDescriptors->offsetSet($fieldName, new WebFieldDescriptor($this, $this->definition['fields'][$fieldName]));
        }
        return $this->fieldDescriptors->offsetGet($fieldName);
    }

    /**
     * returns additional info about dataset
     *
     * if name is not provided the returns whole definition
     *
     * @param string $name            
     * @return boolean
     */
    public function info($name = null)
    {
        if (is_null($name)) {
            return $this->definition;
        }
        if (! array_key_exists($name, $this->definition)) {
            throw new InformationNotFound("definition information part '" . $name . "' not found");
        }
        switch ($name) {
            default:
                return $this->definition[$name];
                break;
        }
    }

    /**
     * list generator
     *
     * key are Field names, value is DbFieldDescriptor objects
     *
     * @return Generator
     */
    public function listGenerator()
    {
        foreach ($this->listFields() as $fieldName) {
            yield $fieldName => $this->getFieldDescriptor($fieldName);
        }
    }
}