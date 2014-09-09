<?php

namespace Admin\Model;

use Application\Model\BaseModel;
use Zend\I18n\Translator\Translator;

class NewsModel extends BaseModel
{	
	public function getData($data,$type = 'insert')
	{
		$dataArray = array();
		$dataArray['news_title'] = isset($data['news_title']) ? $data['news_title'] : null;
		$dataArray['news_file'] = isset($data['news_file']) ? $this->getUploadPath($data['news_file']) : null;
		$dataArray['news_body'] = isset($data['news_body']) ? $data['news_body'] : null;
		$type == 'insert' && $dataArray['create_time']= time();
		$dataArray['update_time'] = !isset($dataArray['create_time']) ? $dataArray['create_time'] : time();
		
		return $dataArray;
	}
	
	public function getNewsModel($type = 'insert',$existsWhere = null)
	{
		$translator = new Translator();
		
		return $this->getModel(array(
			array(
				'name' => 'news_title',
				'filters' => array(
					array('name' => 'stripTags'),
					array('name' => 'stringTrim'),
					array('name' => 'htmlEntities'),
					array('name' => 'stripNewLines')
				),
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'message' => $translator->translate('News title value is required and can\'t be empty')
						),
					),
					array(
						'name'    => 'StringLength',
						'options' => array(
							'max'     => 100,
							'message' => $translator->translate('The input is more than 100 characters long')
						),
					),
		            array(
		                'name'    => 'Db\NoRecordExists',
		                'options' => array(
		                    'table' => 'news',
		                    'field' => 'news_title',
	                    	'exclude' => is_null($existsWhere) ? 'delete_flg = 0' : $existsWhere,
		                    'adapter' => $this->dbAdapter,
		                    'message' => $translator->translate('A record matching the input was found')
		                ),
		           ),
				)
			),
			array(
				'name' => 'news_body',
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'options' => array(
							'message' => $translator->translate('News body value is required and can\'t be empty')
						),
					),
					array(
						'name'    => 'StringLength',
						'options' => array(
							'max'      => 65530,
							'message' => $translator->translate('The input is more than 65530 characters long')
						),
					),
				)
		   ),
		   array(
		   		'name' => 'news_file',
		   		'validators' => array(
	   				array(
   						'name' => 'File\Size',
   						'options' => array(
   								'max' => 1,
   								'message' => 'dgdg'
   						)
	   				),
		   			array(
						'name' => 'File\MimeType',
		   				'options' => array(
							'mimeType' => 'image/gif,image/jpg',
		   					'message' => 'sg'
						)
					)
		   		),
				'filters' => array(
					array(
						'name' => 'filerenameupload',
						'options' => array(
							'target'    => $GLOBALS['UPLOADPATH'].$this->createRandFileName(),
							'overwrite' => true,
							'use_upload_extension' => true
						)
					)
				)
		   )
		));
	}
}