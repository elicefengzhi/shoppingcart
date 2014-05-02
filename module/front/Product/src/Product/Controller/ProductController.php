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
    
    public function showAction()
    {
    	$pId = $this->params('pId');
    	$product = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductById(array('delete_flg' => 0,'product_id' => (int)$pId));
    	$productImage = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_id','image_path'),array('product_id' => (int)$pId));
    	$pptList = $this->serviceLocator->get('DbSql')->dispatch('ProductType')->getProductTypeByProductId((int)$pId,array('name'),array());

    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Front');
    	$viewHelper->setSourceData($productImage,'productImage');
    	$viewHelper->setSourceData($pptList,'productType');
    	$viewHelper->setSourceData($product);
    	
    	return array('viewHelper' => $viewHelper);
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
