<?php

namespace AdminNews\Controller;

use Application\Controller\Admin\BaseController;

class AdminNewsController extends BaseController
{
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $newsList = false;
        $count = $this->serviceLocator->get('DbSql')->dispatch('News')->getNewsAllCount();
        $paging = false;
        if($count > 0) {
        	$paging = $this->serviceLocator->get('Paging');
        	$paging->paginate($count,10,$pageNum,2);
        	$newsList = $this->serviceLocator->get('DbSql')->dispatch('News')->getNewsAll($paging->getOffset(),$paging->getRowsPerPage());
        }
        $viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
        $viewHelper->setSourceData($newsList);
        return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
    	$errorMessage = '';
    	$page = $this->serviceLocator->get('FormSubmit')->dispatch('Insert');
    	if($page !== false) {
    		$return = $page->insert(false,array('news_title'),'News','AdminNews');
    		$return === false && $page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
    		$return === false && $page->isExists() === true && $errorMessage[][] = 'タイトルは既に登録されております';
    		if($return !== false) return $this->redirect()->toRoute('admin-news');
    	}
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$page !== false && $return === false && $viewHelper->setSourceData($page->getSourceData());
    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin-news/add'));
    }
    
    public function editAction()
    {
    	$nId = $this->params('nId',false);
    	if($nId === false) return $this->redirect()->toRoute('admin-news');
    	$errorMessage = '';
    	$pageList = false;
    	 
    	$page = $this->serviceLocator->get('FormSubmit')->dispatch('Update');
    	if($page !== false) {
    		$return = $page->update(false,array('news_id' => $nId),array('news_title'),'News','AdminNews');
    		if($return !== false) {
    			return $this->redirect()->toRoute('admin-news');
    		}
    		$page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
    		$page->isExists() === true && $errorMessage = 'タイトルは既に登録されております';
    		$pageList = $page->getSourceData();
    	}
    	 
    	$pageOne = $this->serviceLocator->get('DbSql')->dispatch('News');
    	$pageList === false && $pageList = $pageOne->getNews(array('news_id' => $nId),true);
    	 
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($pageList);
    	 
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin-news/edit',array('nId' => $nId))));
    	$viewModel->setTemplate('admin-news/admin-news/add');
    	return $viewModel;
    }
    
    public function showAction()
    {
    	$nId = $this->params('nId',false);
    	if($nId === false) return $this->redirect()->toRoute('admin-news');
    
    	$page = $this->serviceLocator->get('DbSql')->dispatch('News')->getNews(array('news_id' => $nId),true);
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
    			$news = $this->serviceLocator->get('DbSql')->dispatch('News');
    			$news->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $news->edit(array('delete_flg' => 1),array('news_id' => (int)$data));
    				if($return === false) {
    					$news->rollback();
    					return $this->redirect()->toRoute('admin-news');
    				}
    			}
    			$news->commit();
    		}
    	}
    	 
    	return $this->redirect()->toRoute('admin-news');
    }
}