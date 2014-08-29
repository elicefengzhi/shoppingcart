<?php

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class NewsModel implements InputFilterAwareInterface
{
	protected $inputFilter;
	public $news_title;
	public $news_body;
	public $create_time;
	public $update_time;
	
	public function exchangeArray($data,$type)
	{
		$this->news_title = isset($data['news_title']) ? $data['news_title'] : null;
		$this->news_body = isset($data['news_body']) ? $data['news_body'] : null;
		if($type == 'insert') {
			$this->create_time = time();
		}
		else {
			unset($this->create_time);
		}
		$this->update_time = !is_null($this->create_time) ? $this->create_time : time();
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter) {}
	
	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			
			$inputFilter->add(array(
				'name' => 'news_title',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max'      => 100,
						),
					),
				)
			));
			
			$inputFilter->add(array(
				'name' => 'news_body',
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max'      => 65530,
						),
					),
				)				
			));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}