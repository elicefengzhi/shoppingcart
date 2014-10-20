<?php
namespace VisioCrudModeler\Generator\Strategy;

/**
 * strategy class for running generators according to passed params
 *
 * default console strategy to run generators with all their dependendant generators according to passed params
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class ExecuteGenerator extends AbstractGenerator
{

    /**
     * runs specified generators
     */
    public function generate()
    {
        // setting up params
        $this->printParams();
        $this->params->setParam('di', $this->getDi());
        $this->params->setParam('descriptor', $this->getDescriptor());
        $runtimeConfiguration = $this->readRuntimeConfig();
        $this->params->setParam('runtimeConfiguration', $runtimeConfiguration);
        
        $dependency = $this->dependency();
        foreach ($dependency->dependencyListFor($this->params->getParam('generator')) as $name) {
            
            $this->console("\n" . 'Running generator: ' . $name);
            
            $result = $this->getGenerator($name)->generate($this->params);
            $runtimeConfiguration->set($name, (array) $result);
        }
        $this->writeRuntimeConfig($runtimeConfiguration);
    }
}