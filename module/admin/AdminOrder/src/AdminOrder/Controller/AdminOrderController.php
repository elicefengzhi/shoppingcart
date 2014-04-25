<?php

namespace AdminOrder\Controller;

use Application\Controller\Admin\BaseController;

class AdminOrderController extends BaseController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
    	$count = $this->serviceLocator->get('DbSql')->dispatch('Order')->getOrderAllCount();
    	$orderList = false;
    	$paging = false;
    	if($count > 0) {
    		$paging = $this->serviceLocator->get('Paging');
    		$paging->paginate($count,10,$pageNum,2);
    		$offset = $paging->getOffset();
    		$rowsperpage = $paging->getRowsPerPage();
    		$orderList = $this->serviceLocator->get('DbSql')->dispatch('Order')->getOrderAll($offset,$rowsperpage);
    	}
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($orderList);
    	return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
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
    			$order = $this->serviceLocator->get('DbSql')->dispatch('Order');
    			$order->beginTransaction();
    			foreach($postData['order'] as $orderId) {
    				$return = $this->serviceLocator->get('DbSql')->dispatch('Order')->edit($data,array('order_id' => $orderId));
    				if($return === false) {
    					$order->rollback();
    					return $this->redirect()->toRoute('admin-order/index',array('pageNum' => $pageNum));
    				}		
    			}
    			$order->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin-order/index',array('pageNum' => $pageNum));
    }
}
