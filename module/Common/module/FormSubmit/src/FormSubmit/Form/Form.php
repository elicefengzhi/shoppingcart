<?php

namespace FormSubmit\Form;

use Zend\Form\Form;
use Zend\Form\Factory;

class Form
{
	private $form;
	
	public function createForm(Array $spec)
	{
		$factory = new Factory();
		$factory->createForm($spec);
		$form = new Form();
		$form->setFormFactory($factory);
		$this->form = $form;
	}
	
	public function setData($data)
	{
		$this->form->setData($data);
	}
	
	public function getData($flag = null)
	{
		return is_int($flag) ? $this->form->getData($flag) : $this->form->getData();
	}
	
	public function isVal()
	{
		return $this->form->isValid();
	}
	
	public function getForm()
	{
		return $this->form;
	}
}