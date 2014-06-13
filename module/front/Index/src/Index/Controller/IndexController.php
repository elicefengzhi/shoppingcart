<?php

namespace Index\Controller;

use Application\Controller\Front\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
    	$newProductList = $this->serviceLocator->get('DbSqls')->Product()->getProductToList(1,array('product_id','name','original_price','price','stock'));
    	$ossmProductList = $this->serviceLocator->get('DbSql')->Product()->getProductToList(2,array('product_id','name','original_price','price','stock'));
    	$productType = $this->serviceLocator->get('DbSql')->ProductType()->getType(array('parent_id' => 0));
    	$news = $this->serviceLocator->get('DbSql')->News()->getNews(false,false,array('news_id','news_title','update_time'),10);
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Front();
    	$viewHelper->setSourceData($newProductList,'newProductList');
    	$viewHelper->setSourceData($ossmProductList,'ossmProductList');
    	$viewHelper->setSourceData($productType,'productType');
    	$viewHelper->setSourceData($news,'news');
    	
        return array('viewHelper' => $viewHelper);
    }
}
