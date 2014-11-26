<?php

namespace FormSubmit\InputFilter;

use Zend\InputFilter\Factory;

class InputFilter
{
    private $inputFilter;
    
    function __construct($inputFilter)
    {
    	$factory = new Factory();
    	$this->inputFilter = $factory->createInputFilter($inputFilter);
    }
    
	/**
	 * 返回inputFilter
	 * @return \Zend\InputFilter\InputFilterInterface
	 */
	public function getInputFilter()
	{
		return $this->inputFilter;
	}
}