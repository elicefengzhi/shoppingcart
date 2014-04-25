<?php

namespace Product\Controller;

use Application\Controller\Front\BaseController;

class ProductController extends BaseController
{
    public function indexAction()
    {
    	$product = $this->serviceLocator->get('front/product/logic')->getProductList(1);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Front');
    	$viewHelper->setSourceData($product['productList']);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'paging' => $product['paging']));
    	$viewModel->setTerminal(true);
    	
        return $viewModel;
    }
    
    public function ajaxAction()
    {
    	$type = $this->params('type','productList');
    	if($type == 'productList') {
    		$pageNum = $this->params()->fromQuery('pageNum',1);
    		$product = $this->serviceLocator->get('product/logic/product')->getProductList($pageNum);

    		$jsonModel = new \Zend\View\Model\JsonModel($product['productList']);
    		return $jsonModel;
    	}
    	
    	return false;
    }
}
