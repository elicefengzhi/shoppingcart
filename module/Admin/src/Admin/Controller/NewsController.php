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
//     	$errorMessage = '';
//     	$page = $this->serviceLocator->get('FormSubmit')->Insert();
//     	if($page !== false) {
//     		$time = time();
//     		$return = $page->insert()->table('news')->addField(array('create_time' => $time,'update_time' => $time))->existsFields(array('news_title'))->existsWhere(array('delete_flg' => 1))->customFilter(array('editorValue' => null,'news_body' => 0))->validate($this->serviceLocator->get('Validate')->AdminNews())->submit();
//     		$return === false && $page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
//     		$return === false && $page->isExists() === true && $errorMessage[][] = 'タイトルは既に登録されております';
//     		if($return !== false) return $this->redirect()->toRoute('admin/news');
//     	}
//     	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
//     	$viewHelper->setSourceData($errorMessage,'errorMessage');
//     	$page !== false && $return === false && $viewHelper->setSourceData($page->getSourceData());
//     	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/news/add'));

    	$form = new NewsForm();
    	$form->setAttribute('action',$this->url()->fromRoute('admin/news/add'));
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$news = new NewsModel();
    		$form->setInputFilter($news->getInputFilter());
    		$form->setData($request->getPost());

    		if ($form->isValid()) {
    			$news->exchangeArray($form->getData(),'insert');
    			$this->serviceLocator->get('DbSql')->News()->add(get_object_vars($news));
    	
    			return $this->redirect()->toRoute('admin/news');
    		}
    	}
    	return array('form' => $form);
    }
    
    public function editAction()
    {
    	$nId = $this->params('nId',false);
    	if($nId === false) return $this->redirect()->toRoute('admin/news');
    	$errorMessage = '';
    	$pageList = false;
    	 
//     	$page = $this->serviceLocator->get('FormSubmit')->Update();
//     	if($page !== false) {
//     		$return = $page->update()->table('news')->addField(array('update_time' => time()))->where(array('news_id' => $nId))->existsFields(array('news_title'))->existsWhere(array('delete_flg' => 1))->customFilter(array('editorValue' => null,'news_body' => 0))->validate($this->serviceLocator->get('Validate')->AdminNews())->submit();
//     		if($return !== false) {
//     			return $this->redirect()->toRoute('admin/news');
//     		}
//     		$page->isVal() === false && $errorMessage = $page->getValidateErrorMessage();
//     		$page->isExists() === true && $errorMessage[][] = 'タイトルは既に登録されております';
//     		$pageList = $page->getSourceData();
//     	}
    	 
//     	$pageOne = $this->serviceLocator->get('DbSql')->News();
//     	$pageList === false && $pageList = $pageOne->getNews(array('news_id' => $nId),true);
    	 
//     	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
//     	$viewHelper->setSourceData($pageList);
//     	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	 
//     	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/news/edit',array('nId' => $nId))));
//     	$viewModel->setTemplate('admin/news/add');
//     	return $viewModel;

    	$DbNews = $this->serviceLocator->get('DbSql')->News();
    	$result = $DbNews->getNews(array('news_id' => $nId),true);
    	$form = new NewsForm();
    	$form->setAttribute('action',$this->url()->fromRoute('admin/news/edit',array('nId' => $nId)));
    	$form->bind($result);
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			$news = new NewsModel();
    			$news->exchangeArray($form->getData(),'update');
    			$this->serviceLocator->get('DbSql')->News()->edit(get_object_vars($news),array('news_id' => $nId));
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
    				$return = $news->edit(array('delete_flg' => 1),array('news_id' => (int)$data));
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
