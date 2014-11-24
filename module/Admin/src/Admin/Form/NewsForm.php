<?php

namespace Admin\Form;

use Zend\Form\Form;

class NewsForm extends Form
{
	function __construct()
	{
		parent::__construct('news');
		
		$this->setAttributes(array(
			'id' => 'news-form',
			'method' => 'post'
		));

		$this->add(array(
			'type' => 'Text',
			'name' => 'news_title',
		));
		
		$this->add(array(
			'name' => 'news_body'
		));
		
		$this->add(array(
			'type' => 'button',
			'name' => 'news_botton',
			'options' => array('label' => ''),
			'attributes' => array(
				'id' => 'news-submit'
		    )
		));
	}
	
}