<?php
/**
 * Web Filed Descriptor
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
namespace VisioCrudModeler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;

class IndexController extends AbstractActionController
{

    /**
     * default action redirecting to modeler interface
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('visio-crud-modeler', array(
            'action' => 'modeler'
        ));
    }

    /**
     * handles modeler interface
     */
    public function modelerAction()
    {
        $adapter = $this->getDbAdapter();
        $dataSourceDescriptor = new DbDataSourceDescriptor($adapter);

        return array(
            'tables' => $dataSourceDescriptor->listDataSets(),
            'dataSourceDescriptor' => $dataSourceDescriptor,
            'underscoreToCamelCase' => new \Zend\Filter\Word\UnderscoreToCamelCase(),
            'config' => $this->getServiceLocator()->get('config')['VisioCrudModeler']['params']
        );
    }

    /**
     *
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }
}
