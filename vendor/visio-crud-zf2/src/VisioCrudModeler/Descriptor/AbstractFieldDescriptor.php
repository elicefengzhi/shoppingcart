<?php
namespace VisioCrudModeler\Descriptor;

use VisioCrudModeler\Descriptor\FieldDescriptorInterface;
use VisioCrudModeler\Exception\InformationNotFound;
use \VisioCrudModeler\Descriptor\AbstractDataSetDescriptor;

/**
 * Data Source Descriptor for Web Modeler definition. It craetes defition based on modeleted params.
 * 
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class AbstractFieldDescriptor implements FieldDescriptorInterface
{
    const NOT_NULL = 'null';

    const KEY = 'key';

    const DEFAULT_VALUE = 'default';

    const NUMERIC_SCALE = 'numeric_scale';

    const NUMERIC_PRECISION = 'numeric_precision';

    const CHARACTER_MAXIMUM_LENGTH = 'character_maximum_length';

    /**
     * holds field definition
     *
     * @var array
     */
    protected $definition = array();

    /**
     * holds DataSet Descriptor
     *
     * @var DbDataSetDescriptor
     */
    protected $dataSetdescriptor = null;
    
    
     /**
     * constructor, takes DataSet descriptor and field definition
     *
     * @param AbstractDataSetDescriptor $descriptor            
     * @param array $definition            
     */
    public function __construct(AbstractDataSetDescriptor $descriptor, array $definition)
    {
        $this->setDataSetDescriptor($descriptor);
        $this->definition = $definition;
    }

    /**
     * sets DataSet descriptor
     *
     * @param DbDataSetDescriptor $descriptor            
     * @return DbFieldDescriptor
     */
    public function setDataSetDescriptor($descriptor)
    {
        $this->dataSetdescriptor = $descriptor;
        return $this;
    }

    /**
     * gets DataSet descriptor
     *
     * @return DbDataSetDescriptor
     */
    public function getDataSetDescriptor()
    {
        return $this->dataSetdescriptor;
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::getName()
     */
    public function getName()
    {
        return $this->definition['name'];
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::getType()
     */
    public function getType()
    {
        return $this->definition['type'];
    }


    /**
     * returns additional info about field
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
            case self::NOT_NULL:
                return ! $this->definition[self::NOT_NULL];
                break;
            default:
                return $this->definition[$name];
                break;
        }
    }
}
