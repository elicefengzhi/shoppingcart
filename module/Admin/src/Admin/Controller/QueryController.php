<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class QueryController extends BaseController
{	
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $paginator = $this->serviceLocator->get('DbSql')->Query()->getPaginator($pageNum,10);
        $viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
        
        return array('viewHelper' => $viewHelper,'paginator' => $paginator,'pageNum' => $pageNum);
    }
    
    public function showAction()
    {
        $qId = $this->params('qId',false);
        if($qId === false) return $this->redirect()->toRoute('admin/query');
        
        $page = $this->serviceLocator->get('DbSql')->Query()->getQuery(array('q_id' => $qId),true);
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
        		$productType = $this->serviceLocator->get('DbSql')->Query();
        		$productType->beginTransaction();
        		foreach($postData['delete'] as $data) {
        			$return = $productType->del(array('q_id' => (int)$data));
        			if($return === false) {
        				$productType->rollback();
        				return $this->redirect()->toRoute('admin/query');
        			}
        		}
        		$productType->commit();
        	}
        }
         
        return $this->redirect()->toRoute('admin/query');
    }
}
