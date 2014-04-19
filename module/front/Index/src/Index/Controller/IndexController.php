<?php

namespace Index\Controller;

use Application\Controller\Front\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
    	$productList = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductToList(1,array('name'));
        return array();
    }
}
