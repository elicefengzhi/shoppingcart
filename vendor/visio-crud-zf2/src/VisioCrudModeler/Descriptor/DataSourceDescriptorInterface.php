<?php
namespace VisioCrudModeler\Descriptor;

/**
 * Interface for DataSource descriptor classes
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
interface DataSourceDescriptorInterface
{

    /**
     * sets name of DataSource to describe
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * returns DataSource name to which this decriptor is linked
     *
     * @return string
     */
    public function getName();

    /**
     * returns list of DataSet names in described DataSource
     *
     * @return string[]
     */
    public function listDataSets();

    /**
     * returns given DataSet descriptor
     *
     * @param string $dataSetName
     * @return \VisioCrudModeler\Descriptor\DataSetDescriptorInterface
     */
    public function getDataSetDescriptor($dataSetName);
}