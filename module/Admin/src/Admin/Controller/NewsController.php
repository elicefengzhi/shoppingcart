<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Form\NewsForm;
use Admin\Model\NewsModel;

class NewsController extends BaseController
{
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $paginator = $this->serviceLocator->get('DbSql')->News()->getPaginator($pageNum,10);
        $viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();

        return array('viewHelper' => $viewHelper,'paginator' => $paginator,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
    	$translator = new \Zend\I18n\Translator\Translator();
    		$formSpec = [
    			'hydrator' => 'Zend\Stdlib\Hydrator\ArraySerializable',
    			'elements' => [
        			[
        				'spec' => [
        					'name' => 'news_title',
        					'type' => 'Text'
    					],
    				],
    				[
        				'spec' => [
        					'name' => 'news_body'
    					],
    				],
    				[
        				'spec' => [
        					'name' => 'news_botton',
        					'type' => 'button',
        					'options' => ['label' => ''],
        					'attributes' => [
        						'id' => 'news-submit'
        					]
    					],
    				],
    			],
        		'input_filter' => [
	        		[
        				'name' => 'news_title',
        				'validators' => [
        					[
        						'name'    => 'NotEmpty',
        						'options' => [
        							'message' => $translator->translate('News title value is required and can\'t be empty')
        						],
        					],
        					[
        						'name'    => 'StringLength',
        						'options' => [
        							'max'     => 100,
        							'message' => $translator->translate('The input is more than 100 characters long')
        						],
        					],
        					[
        						'name'    => 'Db\NoRecordExists',
        						'options' => [
        							'table' => 'news',
        							'field' => 'news_title',
        							'exclude' => 'delete_flg = 0',
        							'adapter' => $this->serviceLocator->get('Zend\Db\Adapter\Adapter'),
        							'message' => $translator->translate('A record matching the input was found')
        						],
        					],
        				]
	        		],
	        		[
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
	        		],
    			],
    		];
        	$news = $this->serviceLocator->get('FormSubmit')->IsRequestReturnFalse(false)->Insert()->table('news')->form($formSpec,array(
    			'id' => 'news-form',
    			'method' => 'post',
    			'action' => $this->url()->fromRoute('admin/news/add')
    		));
    		$form = $news->getForm();
    		$request = $this->request;
    		if($request->isPost()) {
    			$time = time();
    			$return = $news->addField(array('create_time' => $time,'update_time' => $time))->customFilter(array('news_botton' => null,'editorValue' => null,'news_body' => ''))->submit();
    			if($return === false && ($news->isVal() === false || $news->isExists() === true)) {
    				$errorMessage = $news->getValidateErrorMessage();
    			}
    			if($return !== false) return $this->redirect()->toRoute('admin/news');
    		}
    	
    	return array('form' => $form);
    }
    
    public function editAction()
    {
    	$nId = $this->params('nId',false);
    	if($nId === false) return $this->redirect()->toRoute('admin/news');
    	$errorMessage = '';
    	$pageList = false;

    	$DbNews = $this->serviceLocator->get('DbSql')->News();
    	$result = $DbNews->getNews(array('news_id' => $nId),true);
    	$form = new NewsForm();
    	$form->setAttribute('action',$this->url()->fromRoute('admin/news/edit',array('nId' => $nId)));
    	$form->bind($result);
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$news = new NewsModel();
    		$news->setDbAdapter($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));
    		
    		$form->setData($request->getPost());
    		$form->setInputFilter($news->getNewsModel('update',"news_id <> $nId and delete_flg = 0"));
    		if ($form->isValid()) {
    			$this->serviceLocator->get('DbSql')->News()->edit($news->getData($form->getData(),'update'),array('news_id' => $nId));
    			return $this->redirect()->toRoute('admin/news');
    		}
    	}

    	$viewModel = new \Zend\View\Model\ViewModel(array('form' => $form));
    	$viewModel->setTemplate('admin/news/add');
    	return $viewModel;
    }
    
    public function showAction()
    {
    	$nId = $this->params('nId',false);
    	if($nId === false) return $this->redirect()->toRoute('admin/news');
    
    	$page = $this->serviceLocator->get('DbSql')->News()->getNews(array('news_id' => $nId),true);
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($page);
    	return array('viewHelper' => $viewHelper);
    }
    
    public function deleteAction()
    {
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$news = $this->serviceLocator->get('DbSql')->News();
    			$news->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $news->edit(array('delete_flg' => 0),array('news_id' => (int)$data));
    				if($return === false) {
    					$news->rollback();
    					return $this->redirect()->toRoute('admin/news');
    				}
    			}
    			$news->commit();
    		}
    	}
    	 
    	return $this->redirect()->toRoute('admin/news');
    }
}
