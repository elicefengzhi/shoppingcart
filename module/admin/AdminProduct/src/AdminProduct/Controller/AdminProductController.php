<?php

namespace AdminProduct\Controller;

use Application\Controller\Admin\BaseController;

class AdminProductController extends BaseController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
    	$count = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductAllCount();
    	$productList = false;
    	$forum = false;
    	$paging = false;
    	if($count > 0) {
    		$paging = $this->serviceLocator->get('Paging');
    		$paging->paginate($count,10,$pageNum,2);
    		$offset = $paging->getOffset();
    		$rowsperpage = $paging->getRowsPerPage();
    		$productList = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductAll($offset,$rowsperpage);
    		$forum = $this->serviceLocator->get('DbSql')->dispatch('Forum')->getForumAll();
    	}

    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($productList);
    	$viewHelper->setSourceData($forum,'Forum');
        return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
    	$errorMessage = '';
    	$user = $this->serviceLocator->get('FormSubmit')->dispatch('Insert');
    	if($user !== false) {
    		$user->createChlidColumnsByFiles('productImage','image');
    		$logic = $this->serviceLocator->get('front/product/logic');
    		$logic->setTimeData();
    		$logic->insertProductImageAndAd();
    		$logic->createChlidColumns('AdProduct','ad');
    		$logic->createChlidColumns('TypeProduct','ptypeId');
    		$return = $user->insert(false,array('name'),'Product','AdminProduct');
    		$return === false && $user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		$return === false && $user->isExists() === true && $errorMessage[][] = '商品名は既に登録されております';
    		if($return !== false) return $this->redirect()->toRoute('admin-product');
    	}
    	
    	$ad = $this->serviceLocator->get('DbSql')->dispatch('Ad');
    	$adList = $ad->getAdAll();
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
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
    		$logic = $this->serviceLocator->get('front/product/logic');
    		$logic->insertProductImageAndAd($pId,'update');
    		$logic->createChlidColumns('AdProduct','ad');
    		$logic->createChlidColumns('TypeProduct','ptypeId');
    		$logic->setTimeData(true);
    		$return = $product->update(false,array('product_id' => $pId),array('name'),'Product','AdminProduct');
    		if($return === false) {
    			$product->isVal() === false && $errorMessage = $return->getValidateErrorMessage();
    			$product->isExists() === true && $errorMessage = '商品名は既に登録されております';
    			$productList = $product->getSourceData();
    		}
    		else {
    			return $this->redirect()->toRoute('admin-product');
    		}
    	}

    	if($productList === false) $productList = $this->serviceLocator->get('DbSql')->dispatch('Product')->getProductById(array('product_id' => (int)$pId,'delete_flg' => 0));
    	if($productList === false) return $this->redirect()->toRoute('admin-product');
    	$ProductImage = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_id','image_path'),array('product_id' => (int)$pId));

    	$ad = $this->serviceLocator->get('DbSql')->dispatch('Ad');
    	$adList = $ad->getAdAll();
    	$pptList = $this->serviceLocator->get('DbSql')->dispatch('ProductType')->getProductTypeByProductId((int)$pId,array('ptype_id','parent_id'),array());
    	$adProductList = $this->serviceLocator->get('DbSql')->dispatch('AdProduct')->getAdProductByWhere(array('ad_id'),array('product_id' => (int)$pId));
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->dispatch('Admin');
    	$viewHelper->setSourceData($adList,'ad');
    	$viewHelper->setSourceData($pptList,'productTypeList');
    	$viewHelper->setSourceData($adProductList,'adProductList');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$viewHelper->setSourceData($ProductImage,'ProductImage');
    	$viewHelper->setSourceData($productList);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin-product/edit',array('pId' => (int)$pId))));
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
    			$delete = array_merge($postData['delete'],array('update_time' => time()));
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
    
    public function forumAction()
    {
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		$forumSelect = $postData['forumSelect'];
    		$pageNum = $postData['pageNum'];
    		$formId = $postData['formId'];
    		$product = $postData['product'];
    		if(count($product) > 0) {
    			$productForum = $this->serviceLocator->get('DbSql')->dispatch('ProductForum');
    			$productForum->beginTransaction();
    			foreach($product as $data) {
    				$forumSelect == 1 ? $return = $productForum->add(array('forum_id' => (int)$formId,'product_id' => (int)$data)) : $return = $productForum->del(array('forum_id' => (int)$formId,'product_id' => (int)$data));
    				if($return === false) {
    					$productForum->rollback();
    					return $this->redirect()->toRoute('admin-product/index',array('pageNum' => $pageNum));
    				}
    			}
    			$productForum->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin-product/index',array('pageNum' => $pageNum));
    }
}
