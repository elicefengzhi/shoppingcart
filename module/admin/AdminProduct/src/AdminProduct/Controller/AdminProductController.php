<?php

namespace AdminProduct\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdminProductController extends AbstractActionController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
    	$count = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductAllCount();
    	$productList = false;
    	$paging = false;
    	if($count > 0) {
    		$paging = $this->serviceLocator->get('Paging');
    		$paging->paginate($count,2,$pageNum,2);
    		$offset = $paging->getOffset();
    		$rowsperpage = $paging->getRowsPerPage();
    		$productList = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductAll($offset,$rowsperpage);
    	}

    	$viewHelper = $this->ViewHelper('Admin');
    	$viewHelper->setSourceData($productList);
        return array('viewHelper' => $viewHelper,'paging' => $paging);
    }
    
    public function addAction()
    {
    	$errorMessage = '';
    	$user = $this->serviceLocator->get('FormSubmit')->dispatch('Insert');
    	if($user !== false) {
    		$user->createChlidColumnsByFiles('productImage','image');
    		$model = new \AdminProduct\Model\AdminProductModel();
    		$model->setTimeData();
    		$model->insertProductImageAndAd();
    		$model->createChlidColumns('AdProduct','ad');
    		$model->createChlidColumns('TypeProduct','ptypeId');
    		$return = $user->insert(false,array('name'),'Product','AdminProduct');
    		$return === false && $user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		$return === false && $user->isExists() === true && $errorMessage[][] = '商品名已存在';
    		if($return !== false) return $this->redirect()->toRoute('admin-product');
    	}
    	
//     	$productType = $this->serviceLocator->get('DbSql')->dispatch('ProductType');
//     	$typeList = $productType->getType(array('parent_id' => 0));
    	$ad = $this->serviceLocator->get('DbSql')->dispatch('Ad');
    	$adList = $ad->getAdAll();
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
//     	$viewHelper->setSourceData($typeList,'type');
    	$viewHelper->setSourceData($adList,'ad');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$user !== false && $return === false && $viewHelper->setSourceData($user->getSourceData());
    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin-product/add'));
    }
    
    public function editAction()
    {
    	$pId = $this->params('pId',false);
    	$pId === false && $this->redirect()->toRoute('admin-product');
    	$errorMessage = '';
    	$productList = false;
    	
    	$product = $this->serviceLocator->get('FormSubmit')->dispatch('Update');
    	if($product !== false) {
    		$product->createChlidColumnsByFiles('productImage','image');
    		$model = new \AdminProduct\Model\AdminProductModel();
    		$model->insertProductImageAndAd($pId,'update');
    		$model->createChlidColumns('AdProduct','ad');
    		$model->setTimeData(true);
    		$return = $product->update(false,array('product_id' => $pId),array('name'),'Product','AdminProduct');
    		if($return === false) {
    			$product->isVal() === false && $errorMessage = $return->getValidateErrorMessage();
    			$product->isExists() === true && $errorMessage = '商品分类名已存在';
    			$productList = $product->getSourceData();
    		}
    		else {
    			return $this->redirect()->toRoute('admin-product');
    		}
    	}

    	if($productList === false) $productList = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductById(array('product_id' => $pId,'delete_flg' => 0));
    	if($productList === false) return $this->redirect()->toRoute('admin-product');
    	$ProductImage = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_id','image_path'),array('product_id' => $pId));
//     	$productType = $this->serviceLocator->get('DbSql')->dispatch('ProductType');
//     	$typeList = $productType->getType(array('parent_id' => 0));
    	$ad = $this->serviceLocator->get('DbSql')->dispatch('Ad');
    	$adList = $ad->getAdAll();
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
//     	$viewHelper->setSourceData($typeList,'type');
    	$viewHelper->setSourceData($adList,'ad');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$viewHelper->setSourceData($ProductImage,'ProductImage');
    	$viewHelper->setSourceData($productList);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin-product/edit',array('pId' => $pId))));
    	$viewModel->setTemplate('admin-product/admin-product/add');
    	return $viewModel;
    }
    
    public function deleteAction()
    {
    	$pId = $this->params('pId',false);
    	if($pId !== false) {
	    	$return = $this->serviceLocator->get('DbSql')->dispatch('Product')->edit(array('delete_flg' => 1),array('product_id' => (int)$pId));
	    	$return === true ? $return = 'true' : $return = 'false';
	    	
	    	echo $return;
	    	exit;
    	}
    	
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$productType = $this->serviceLocator->get('DbSql')->dispatch('Product');
    			$productType->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $productType->edit(array('delete_flg' => 1),array('product_id' => (int)$data));
    				if($return === false) {
    					$productType->rollback();
    					return $this->redirect()->toRoute('admin-product');
    				}
    			}
    			$productType->commit();
    		}
    	}
    	 
    	return $this->redirect()->toRoute('admin-product');
    }
}
