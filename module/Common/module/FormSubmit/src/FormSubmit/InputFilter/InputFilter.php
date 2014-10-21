<?php

namespace FormSubmit\InputFilter;

use Zend\InputFilter\Factory;

class InputFilter
{
    private $inputFilter;
    
    /**
     * 返回处理过的requestData
     * @return array
     */
    public function getValues()
    {
    	return $this->inputFilter->getValues();
    }
    
    /**
     * 返回原始requestData
     * @return array
     */
    public function getRawValues()
    {
    	return $this->inputFilter->getRawValues();
    }
    
    /**
     * 返回错误信息
     * @return array
     */
    public function getMessage()
    {
    	return $this->inputFilter->getMessages();
    }
    
    /**
     * 是否通过验证
     * @param array $requestData
     * @param array $inputFilter
     * @return boolean
     */
	public function isVal($requestData,Array $inputFilter)
	{
	    $factory = new Factory();
	    $this->inputFilter = $factory->createInputFilter($inputFilter);
	    $this->inputFilter->setData($requestData);
	    return $this->inputFilter->isValid();
	}
}