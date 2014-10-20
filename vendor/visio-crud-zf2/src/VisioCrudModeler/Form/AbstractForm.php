<?php

namespace VisioCrudModeler\Form;

use Zend\Form\Form;
use VisioCrudModeler\JQueryValidator\ValidatorManager;
use VisioCrudModeler\JQueryValidator\ValidatorManagerAwareInterface; 


/**
 * Extends of Default From class allows to manage JQuery Validators
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
abstract class AbstractForm extends Form implements ValidatorManagerAwareInterface
{
    
    /**
     *
     * @var ValidatorManager 
     */
    protected $validatorManager;
    
    
    /**
     * Get validator  manager
     *
     * @param ValidatorManager $validatorManager
     */
    public function getValidatorManager()
    {
        
        if(!$this->validatorManager){
            $this->validatorManager = new \VisioCrudModeler\JQueryValidator\ValidatorManager();
            $this->validatorManager->setInputFilter($this->getInputFilter());
        }
        return $this->validatorManager;
    }

    /**
     * Set validator  manager
     *
     * @param ValidatorManager $validatorManager
     */
    public function setValidatorManager(\VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager)
    {
        $this->validatorManager = $validatorManager;
    }
    
    /**
     * Return prepeared jquery validate plugin script
     * 
     * @return string
     */
    public function getJqueryValidateScript()
    {
        return $this->getValidatorManager()->getScript('customer');
    }
    
}
