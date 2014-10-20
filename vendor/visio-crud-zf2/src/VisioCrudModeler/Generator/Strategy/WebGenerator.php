<?php
namespace VisioCrudModeler\Generator\Strategy;

/**
 * Web strategy to run all generators
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class WebGenerator extends AbstractGenerator
{
    
    /**
     * runs specified generators
     */
    public function generate()
    {
        $this->printParams();
        $this->params->setParam('di', $this->getDi());
        $this->params->setParam('descriptor', $this->getDescriptor());
        $runtimeConfiguration = $this->readRuntimeConfig();
        $this->params->setParam('runtimeConfiguration', $runtimeConfiguration);
        
        $generators = $this->params->getParam('config')->get('generators');
        
        foreach(array_keys($generators) as $generatorName){
            
            if($generatorName == 'all'){
                continue;
            }
            $result = $this->getGenerator($generatorName)->generate($this->params);
            $runtimeConfiguration->set($generatorName, (array) $result);
        }
        
        $this->writeRuntimeConfig($runtimeConfiguration);
    }
   
}