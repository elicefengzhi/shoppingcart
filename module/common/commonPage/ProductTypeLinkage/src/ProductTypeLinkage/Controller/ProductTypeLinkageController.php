<?php

namespace ProductTypeLinkage\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ProductTypeLinkageController extends AbstractActionController
{
    public function indexAction()
    {
    	$parentId = $this->params('parentId',0);

    	$typeList = $this->serviceLocator->get('DbSql')->ProductType()->getType(array('parent_id' => $parentId));
    	
    	if($parentId != 0) {
    		$typeList === false ? print 'false' : print json_encode($typeList);
    		exit;
    	}
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Front();
    	$viewHelper->setSourceData($typeList);
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper));
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
}
