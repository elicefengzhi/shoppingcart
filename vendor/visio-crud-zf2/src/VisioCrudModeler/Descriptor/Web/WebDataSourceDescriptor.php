<?php

namespace VisioCrudModeler\Descriptor\Web;

use VisioCrudModeler\Descriptor\AbstractDataSourceDescriptor;
use VisioCrudModeler\Descriptor\ListGeneratorInterface;

/**
 * Data Source Descriptor for Web Modeler definition. It craetes defition based on modeleted params.
 * 
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class WebDataSourceDescriptor extends AbstractDataSourceDescriptor implements ListGeneratorInterface
{
    /**
     *
     * @var \VisioCrudModeler\Generator\ParamsInterface 
     */
    protected $params;

    /**
     * 
     * @param \VisioCrudModeler\Generator\ParamsInterface $params
     */
    public function __construct(\VisioCrudModeler\Generator\ParamsInterface $params)
    {
        $this->params = $params;
        $this->dataSetDescriptors = new \ArrayObject(array());
    }

    /*
     * (non-PHPdoc) @see \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor::describe()
     */

    protected function describe()
    {
        if (!$this->definitionResolved) {
            $this->describeTables();
            $this->describeColumns();
        }
        
        return $this;
    }

    /**
     * Describes tables based on modeleted params
     */
    protected function describeTables()
    {
        $modelerParams = $this->params->getParam('modeler');
        foreach ($modelerParams['tables'] as $param) {
            $this->definition[$param['name']] = array(
                'name' => $param['name'],
                'type' => 'table',
                'updateable' => 0,
                'fields' => array()
            );
        }
    }

    /**
     * Describes columns based on modeleted params
     */
    protected function describeColumns()
    {
        $modelerParams = $this->params->getParam('modeler');

        foreach ($modelerParams['elements'] as $element) {
            $fieldDescription = array(
                'name' => $element['name'],
                'type' => $element['type'],
                //'character_maximum_length' => $row['CHARACTER_MAXIMUM_LENGTH'],
                //'numeric_precision' => $row['NUMERIC_PRECISION'],
                //'numeric_scale' => $row['NUMERIC_SCALE'],
                //'null' => ($row['IS_NULLABLE'] == 'YES') ? true : false,
                //'default' => $row['COLUMN_DEFAULT'],
                'key' => null,
                'reference' => ($element['key'] == 'foreign') ? true : false,
                'label' => $element['label'],
                'validators' => $element['validators'],
                'filters' => $element['filters']
            );
            
            if($element['key'] == 'primary'){
                $this->definition[$element['table']]['primaryKey'] = $element['name'];
            }
            $this->definition[$element['table']]['fields'][$element['name']] = $fieldDescription;
        }
    }

    /**
     * returns DataSet descriptor
     *
     * @throws \VisioCrudModeler\Exception\DataSetNotFound
     * @return \VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor
     */
    public function getDataSetDescriptor($dataSetName)
    {
        $this->describe();
        if (!array_key_exists($dataSetName, $this->definition)) {
            throw new DataSetNotFound("dataSet '" . $dataSetName . "' don't exists in '" . $this->name . "'");
        }
        if (!$this->dataSetDescriptors->offsetExists($dataSetName)) {
            $this->dataSetDescriptors->offsetSet($dataSetName, new WebDataSetDescriptor($this, $this->definition[$dataSetName]));
        }
        return $this->dataSetDescriptors->offsetGet($dataSetName);
    }

}
