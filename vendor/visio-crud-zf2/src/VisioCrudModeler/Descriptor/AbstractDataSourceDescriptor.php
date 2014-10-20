<?php
namespace VisioCrudModeler\Descriptor;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * abstract DataSource Descriptor
 * 
 * contains commonly used methods in DataSource descriptors
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
abstract class AbstractDataSourceDescriptor implements DataSourceDescriptorInterface, EventManagerAwareInterface, ServiceLocatorAwareInterface
{

    /**
     * holds event manager instance
     *
     * @var EventManagerInterface
     */
    protected $eventManager = null;

    /**
     * holds service locator instance
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * holds definition of availbale data sets
     *
     * @var array
     */
    protected $definition = array();

    /**
     * holds flag for status of definition resolve
     *
     * @var boolean
     */
    protected $definitionResolved = false;

    /**
     * holds used adapter
     *
     * @var mixed
     */
    protected $adapter = null;

    /**
     * holds name of data source
     *
     * @var string
     */
    protected $name = null;

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::setName()
     */
    public function setName($name)
    {
        if ($name != $this->name) {
            $this->resetDescribedData();
        }
        $this->name = $name;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets adapter
     *
     * @param mixed $adapter            
     * @return AbstractDataSourceDescriptor
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * gets adapter
     *
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \VisioCrudModeler\DataSource\Descriptor\DataSourceDescriptorInterface::listDataSets()
     */
    public function listDataSets()
    {
        if (! $this->definitionResolved) {
            $this->describe();
        }
        return array_keys($this->definition);
    }

    /**
     * resolves data set definitions
     */
    abstract protected function describe();

    /**
     * resets resolved DataSource description data
     *
     * @return \VisioCrudModeler\DataSource\Descriptor\AbstractDataSourceDescriptor
     */
    protected function resetDescribedData()
    {
        $this->definition = array();
        $this->definitionResolved = false;
        return $this;
    }

    /**
     * sets event manager instance
     *
     * @param EventManagerInterface $eventManager            
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * gets event manager instance, lazy-loads if none was set
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /**
     * list generator
     *
     * keys are DataSet names, value is DbDataSetDescriptor objects
     *
     * @return Generator
     */
    public function listGenerator()
    {
        foreach ($this->listDataSets() as $dataSetName) {
            yield $dataSetName => $this->getDataSetDescriptor($dataSetName);
        }
    }
    
}