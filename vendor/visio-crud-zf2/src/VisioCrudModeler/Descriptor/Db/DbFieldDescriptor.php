<?php
namespace VisioCrudModeler\Descriptor\Db;


use VisioCrudModeler\Descriptor\ReferenceFieldInterface;
use VisioCrudModeler\Descriptor\AbstractFieldDescriptor;

/**
 * DataSet Field descriptor
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
class DbFieldDescriptor extends AbstractFieldDescriptor implements ReferenceFieldInterface
{

    /**
     * returns referenced field descriptor
     *
     * @return \VisioCrudModeler\Descriptor\Db\DbFieldDescriptor
     */
    public function getReferencedField()
    {
        if ($this->isReference()) {
            if ($this->referencedDataSetName() == $this->getDataSetDescriptor()->getName()) {
                return $this->getDataSetDescriptor()->getFieldDescriptor($this->referencedFieldName());
            } else {
                return $this->getDataSetDescriptor()
                    ->getDataSourceDescriptor()
                    ->getDataSetDescriptor($this->referencedDataSetName())
                    ->getFieldDescriptor($this->referencedFieldName());
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\FieldDescriptorInterface::isReference()
     */
    public function isReference()
    {
        return ($this->definition['reference'] !== false);
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\ReferenceFieldInterface::referencedDataSetName()
     */
    public function referencedDataSetName()
    {
        return $this->definition['reference']['dataset'];
    }
    
    /*
     * (non-PHPdoc) @see \VisioCrudModeler\Descriptor\ReferenceFieldInterface::referencedFieldName()
     */
    public function referencedFieldName()
    {
        return $this->definition['reference']['field'];
    }

   
}