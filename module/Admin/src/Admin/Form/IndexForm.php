<?php

namespace Admin\Form;

use Zend\Form\Form;

class IndexForm extends Form
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->setAttributes(array(
			'id' => 'login-form',
			'method' => 'post'
		));
		
		$this->add(array(
			'type' => 'Text',
			'name' => 'uname',
			'id' => 'uname'
		));
		
		$this->add(array(
			'type' => 'Password',
			'name' => 'pwd',
			'id' => 'pwd'
		));
		
		$this->add(new \Zend\Form\Element\Csrf('security'));
	}
}