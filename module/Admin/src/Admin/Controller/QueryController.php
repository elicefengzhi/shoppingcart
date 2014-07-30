<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class QueryController extends BaseController
{	
    public function indexAction()
    {
        $pageNum = $this->params('pageNum',1);
        $pageList = false;
        $count = $this->serviceLocator->get('DbSql')->Query()->getQueryAllCount();
        $paging = false;
        if($count > 0) {
        	$paging = $this->serviceLocator->get('Paging');
        	$paging->paginate($count,10,$pageNum,2);
        	$pageList = $this->serviceLocator->get('DbSql')->Query()->getQueryAll(array('q_id','q_title','create_time'),$paging->getOffset(),$paging->getRowsPerPage());
        }
        $viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
        $viewHelper->setSourceData($pageList);
        return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
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
