<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class PageController extends BaseController
{
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $paginator = $this->serviceLocator->get('DbSql')->Page()->getPaginator($pageNum,10);
        $viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
        return array('viewHelper' => $viewHelper,'paginator' => $paginator,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
        $errorMessage = '';
        $page = $this->serviceLocator->get('FormSubmit')->Insert();
        if($page !== false) {
        	$time = time();
        	$return = $page->table('page')->addField(array('create_time' => $time,'update_time' => $time))->existsFields(array('page_title'))->customFilter(array('editorValue' => null,'page_body' => ''))
        			  	->inputFilter(
        			  		array(
        			  			'page_title' => array(
        			  				'name'       => 'page_title',
        			  				'required'   => true,
        			  				'validators' => array(
        			  					array(
        			  						'name' => 'not_empty',
        			  						'options' => array(
        			  							'message' => 'タイトルを入力してください'
        			  						)	
        			  					)
        			  				),
        			  			),
        			  			'page_body' => array(
        			  				'name' => 'page_body',
        			  				'required'   => true,
        			  				'filters'    => array(array('name' => 'stringTrim')),
        			  				'validators' => array(
        			  					array(
        			  						'name' => 'not_empty',
        			  						'options' => array(
        			  							'message' => '内容を入力してください'
        			  						)
        			  					)
        			  				),
        						)
        			  		)
        				)
        				->submit();
            if($return === false && ($page->isVal() === false || $page->isExists() === true)) {
    			$errorMessage = $page->getValidateErrorMessage();
    		}
            if($return !== false) return $this->redirect()->toRoute('admin/page');
        }
        $viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
        $viewHelper->setSourceData($errorMessage,'errorMessage');
        $page !== false && $return === false && $viewHelper->setSourceData($page->getSourceData());
    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/page/add'));
    }
    
    public function editAction()
    {
    	$pId = $this->params('pId',false);
    	if($pId === false) return $this->redirect()->toRoute('admin/page');
    	$errorMessage = '';
    	$pageList = false;
    	
    	$page = $this->serviceLocator->get('FormSubmit')->Update();
    	if($page !== false) {
    		$return = $page->table('page')->addField(array('update_time' => time()))->where(array('page_id' => $pId))->existsFields(array('page_title'))->customFilter(array('editorValue' => null,'page_body' => ''))->validate($this->serviceLocator->get('Validate')->AdminPage())->submit();
    		if($return !== false) {
    			return $this->redirect()->toRoute('admin/page');
    		}
    		$page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
    		$page->isExists() === true && $errorMessage = 'タイトルは既に登録されております';
    		$pageList = $page->getSourceData();
    	}
    	
    	$pageOne = $this->serviceLocator->get('DbSql')->Page();
    	$pageList === false && $pageList = $pageOne->getPage(array('page_id' => $pId),true);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($pageList);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin/page/edit',array('pId' => $pId))));
    	$viewModel->setTemplate('admin/page/add');
    	return $viewModel;
    }
    
    public function showAction()
    {
        $pId = $this->params('pId',false);
        if($pId === false) return $this->redirect()->toRoute('admin/page');
        
        $page = $this->serviceLocator->get('DbSql')->Page()->getPage(array('page_id' => $pId),true);
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
    			$page = $this->serviceLocator->get('DbSql')->Page();
    			$page->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $page->del(array('page_id' => (int)$data));
    				if($return === false) {
    					$page->rollback();
    					return $this->redirect()->toRoute('admin/page');
    				}
    			}
    			$page->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin/page');
    }
}
