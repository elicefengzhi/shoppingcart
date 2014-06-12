<?php

namespace ShoppingCart\Controller;

use Application\Controller\Front\BaseController;

class ShoppingCartController extends BaseController
{
    public function indexAction()
    {
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Front();
    	$viewHelper->setSourceData($this->ZendCart()->cart());
    	$viewHelper->setSourceData($this->ZendCart()->total_items(),'totalItems');
    	$viewHelper->setSourceData($this->ZendCart()->total(),'total');
        return array($viewHelper);
    }
    
    public function addAction()
    {
    	$pId = (int)$this->params('pId');
    	$count = (int)$this->params()->fromQuery('count');
    	$count < 0 && $count = 1;
    	$size = $this->serviceLocator->get('front/shoppingCart/logic')->checkProductSize($this->params()->fromQuery('size'));
    	$product = $this->serviceLocator->get('DbSql')->Product()->getProductById(array('delete_flg' => 0,'product_id' => $pId));
    	$product = array('id' => $product['product_id'],'price' => $product['price'],'name' => $product['name'],'qty' => $count,'options' => array('Size' => $size));
    	$return = $this->ZendCart()->insert($product);
    	 
    	$jsonModel = new \Zend\View\Model\JsonModel(array('isOk' => $return));
    	return $jsonModel;
    }
    
    public function clearAction()
    {
    	$this->ZendCart()->destroy();
    	return $this->redirect()->toRoute('shopping-cart');
    }
}
