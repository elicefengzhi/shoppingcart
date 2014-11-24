<?php

namespace FormSubmit\Form;

use Zend\Form\Factory;

class FormSubmitForm
{
	private $form;
	
	function __construct(Array $spec,Array $attrs)
	{
		$factory = new Factory();
		$this->form = $factory->createForm($spec);
		count($attrs) > 0 && $this->form->setAttributes($attrs);
	}
	
	public function getForm()
	{
		return $this->form;
	}
}