<?php
namespace VisioCrudModeler\Descriptor;

/**
 * interface for DataSet descriptors
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
interface DataSetDescriptorInterface
{

    /**
     * returns name of DataSet
     *
     * @return string
     */
    public function getName();

    /**
     * returns type of DataSet
     *
     * @return string
     */
    public function getType();

    /**
     * returns list of field names for DataSet
     */
    public function listFields();

    /**
     * returns field Descriptor
     *
     * @param string $fieldName
     * @return \VisioCrudModeler\Descriptor\FieldDescriptorInterface
     */
    public function getFieldDescriptor($fieldName);

    /**
     * return all fields
     *
     * @return array
     */
    public function getFields();

    /**
     * return primary key name
     *
     * @return array
     */
    public function getPrimaryKey();
}