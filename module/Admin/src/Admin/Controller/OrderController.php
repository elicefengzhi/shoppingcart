<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class OrderController extends BaseController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
		$paginator = $this->serviceLocator->get('DbSql')->Order()->getPaginator($pageNum,10);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	return array('viewHelper' => $viewHelper,'paginator' => $paginator,'pageNum' => $pageNum);
    }
    
    public function statusDeleteAction()
    {
    	$request = $this->request;
    	$pageNum = $this->params('pageNum',1);
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		$statusDelete = $postData['statusDelete'];
    		$data = false;
    		switch ($statusDelete) {
    			case 1: 
    				$data = array('status' => 1);
    				break;
    			case 2:
    				$data = array('status' => 0);
    				break;
    			case 3:
    				$data = array('delete_flg' => 1);
    		}
    		
    		if($data !== false && is_array($postData['order']) && count($postData['order']) > 0) {
    			$data = array_merge($data,array('update_time' => time()));
    			$order = $this->serviceLocator->get('DbSql')->Order();
    			$order->beginTransaction();
    			foreach($postData['order'] as $orderId) {
    				$return = $this->serviceLocator->get('DbSql')->Order()->edit($data,array('order_id' => $orderId));
    				if($return === false) {
    					$order->rollback();
    					return $this->redirect()->toRoute('admin/order/index',array('pageNum' => $pageNum));
    				}		
    			}
    			$order->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin/order/index',array('pageNum' => $pageNum));
    }
}
