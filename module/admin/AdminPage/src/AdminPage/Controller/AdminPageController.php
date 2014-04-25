<?php

namespace AdminPage\Controller;

use Application\Controller\Admin\BaseController;

class AdminPageController extends BaseController
{
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $pageList = false;
        $count = $this->serviceLocator->get('DbSql')->dispatch('Page')->getPageAllCount();
        $paging = false;
        if($count > 0) {
        	$paging = $this->serviceLocator->get('Paging');
        	$paging->paginate($count,10,$pageNum,2);
        	$pageList = $this->serviceLocator->get('DbSql')->dispatch('Page')->getPageAll($paging->getOffset(),$paging->getRowsPerPage());
        }
        $viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
        $viewHelper->setSourceData($pageList);
        return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
        $errorMessage = '';
        $page = $this->serviceLocator->get('FormSubmit')->dispatch('Insert');
        if($page !== false) {
            $return = $page->insert(false,array('page_title'),'Page','AdminPage');
            $return === false && $page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
            $return === false && $page->isExists() === true && $errorMessage[][] = 'タイトルは既に登録されております';
            if($return !== false) return $this->redirect()->toRoute('admin-page');
        }
        $viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
        $viewHelper->setSourceData($errorMessage,'errorMessage');
        $page !== false && $return === false && $viewHelper->setSourceData($page->getSourceData());
    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin-page/add'));
    }
    
    public function editAction()
    {
    	$pId = $this->params('pId',false);
    	if($pId === false) return $this->redirect()->toRoute('admin-page');
    	$errorMessage = '';
    	$pageList = false;
    	
    	$page = $this->serviceLocator->get('FormSubmit')->dispatch('Update');
    	if($page !== false) {
    		$return = $page->update(false,array('page_id' => $pId),array('page_title'),'Page','AdminPage');
    		if($return !== false) {
    			return $this->redirect()->toRoute('admin-page');
    		}
    		$page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
    		$page->isExists() === true && $errorMessage = 'タイトルは既に登録されております';
    		$pageList = $page->getSourceData();
    	}
    	
    	$pageOne = $this->serviceLocator->get('DbSql')->dispatch('Page');
    	$pageList === false && $pageList = $pageOne->getPage(array('page_id' => $pId),true);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($pageList);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin-page/edit',array('pId' => $pId))));
    	$viewModel->setTemplate('admin-page/admin-page/add');
    	return $viewModel;
    }
    
    public function showAction()
    {
        $pId = $this->params('pId',false);
        if($pId === false) return $this->redirect()->toRoute('admin-page');
        
        $page = $this->serviceLocator->get('DbSql')->dispatch('Page')->getPage(array('page_id' => $pId),true);
        $viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
        $viewHelper->setSourceData($page);
        return array('viewHelper' => $viewHelper);
    }
    
    public function deleteAction()
    {
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$page = $this->serviceLocator->get('DbSql')->dispatch('Page');
    			$page->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $page->del(array('page_id' => (int)$data));
    				if($return === false) {
    					$page->rollback();
    					return $this->redirect()->toRoute('admin-page');
    				}
    			}
    			$page->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin-page');
    }
}
