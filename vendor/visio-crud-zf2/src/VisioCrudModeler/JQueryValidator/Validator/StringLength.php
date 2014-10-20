<?php

namespace VisioCrudModeler\JQueryValidator\Validator;

use VisioCrudModeler\JQueryValidator\AbstractValidator;


/**
 * Implementaion of string length Validator
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class StringLength extends AbstractValidator
{
    
    /**
     * (non-PHPdoc) @see \VisioCrudModeler\JQueryValidator\AbstractValidator::getRule()
     */
    public function getRule()
    {
        $ret = '';
        $validator = $this->getZendValidator();
        
        if($validator->getMin()){
            $ret .= 'minlength: ' . $validator->getMin() . ', ' . PHP_EOL;
        }
        if($validator->getMax()){
            $ret .= 'maxlength: ' . $validator->getMax() . ', ' . PHP_EOL;
        }
        
        return $ret;
        
    }    
}
