<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class ProductController extends BaseController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
    	$count = $this->serviceLocator->get('DbSql')->Product()->getProductAllCount();
    	$productList = false;
    	$forum = false;
    	$paging = false;
    	if($count > 0) {
    		$paging = $this->serviceLocator->get('Paging');
    		$paging->paginate($count,10,$pageNum,2);
    		$offset = $paging->getOffset();
    		$rowsperpage = $paging->getRowsPerPage();
    		$productList = $this->serviceLocator->get('DbSql')->Product()->getProductAll($offset,$rowsperpage);
    		$forum = $this->serviceLocator->get('DbSql')->Forum()->getForumAll();
    	}

    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($productList);
    	$viewHelper->setSourceData($forum,'Forum');
        return array('viewHelper' => $viewHelper,'paging' => $paging,'pageNum' => $pageNum);
    }
    
    public function addAction()
    {
    	$errorMessage = '';
    	$user = $this->serviceLocator->get('FormSubmit')->Insert();
    	if($user !== false) {
    		$logic = $this->serviceLocator->get('admin/product/logic');
    		$logic->setTimeData();
    		$logic->insertProductImageAndAd();
    		$return = $user->insert()->table('product')->existsFields(array('name'))->validate($this->serviceLocator->get('Validate')->AdminProduct())
    		->helper('ValidateAfter','ChildColumns','input','AdProduct','ad')
    		->helper('ValidateAfter','ChildColumns','input','TypeProduct','ptypeId')
    		->mediaUpload(false,false)->customFilter(array('editorValue' => null))->submit();
    		
    		//$return === false && $user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		//$return === false && $user->isExists() === true && $errorMessage[][] = '商品名は既に登録されております';
    		if($return === false && ($user->isVal() === false || $user->isExists() === false)) {
    			$errorMessage = $user->getValidateErrorMessage();
    		}
    		if($return !== false) return $this->redirect()->toRoute('admin/product');
    	}
    	
    	$ad = $this->serviceLocator->get('DbSql')->Ad();
    	$adList = $ad->getAdAll();
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($adList,'ad');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$user !== false && $return === false && $viewHelper->setSourceData($user->getSourceData());
    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/product/add'));
    }
    
    public function editAction()
    {
    	$pId = $this->params('pId',false);
    	$pId === false && $this->redirect()->toRoute('admin/product');
    	$errorMessage = '';
    	$productList = false;
    	
    	$product = $this->serviceLocator->get('FormSubmit')->Update();
    	if($product !== false) {
    		$logic = $this->serviceLocator->get('admin/product/logic');
    		$logic->insertProductImageAndAd($pId,'update');
    		$logic->setTimeData(true);
    		$return = $product->update()->table('product')->where(array('product_id' => $pId))->existsFields(array('name'))->validate($this->serviceLocator->get('Validate')->AdminProduct())
    		->helper('ValidateAfter','ChildColumns','input','AdProduct','ad')
    		->helper('ValidateAfter','ChildColumns','input','TypeProduct','ptypeId')
    		->mediaUpload(false,false)->customFilter(array('editorValue' => null))->submit();
    		
    		if($return === false) {
    			$product->isVal() === false && $errorMessage = $return->getValidateErrorMessage();
    			$product->isExists() === true && $errorMessage = '商品名は既に登録されております';
    			$productList = $product->getSourceData();
    		}
    		else {
    			return $this->redirect()->toRoute('admin/product');
    		}
    	}

    	if($productList === false) $productList = $this->serviceLocator->get('DbSql')->Product()->getProductById(array('product_id' => (int)$pId,'delete_flg' => 0));
    	if($productList === false) return $this->redirect()->toRoute('admin/product');
    	$ProductImage = $this->serviceLocator->get('DbSql')->ProductImage()->getImageByProductId(array('image_id','image_path'),array('product_id' => (int)$pId));

    	$ad = $this->serviceLocator->get('DbSql')->Ad();
    	$adList = $ad->getAdAll();
    	$pptList = $this->serviceLocator->get('DbSql')->ProductType()->getProductTypeByProductId((int)$pId,array('ptype_id','parent_id'),array());
    	$adProductList = $this->serviceLocator->get('DbSql')->AdProduct()->getAdProductByWhere(array('ad_id'),array('product_id' => (int)$pId));
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($adList,'ad');
    	$viewHelper->setSourceData($pptList,'productTypeList');
    	$viewHelper->setSourceData($adProductList,'adProductList');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');
    	$viewHelper->setSourceData($ProductImage,'ProductImage');
    	$viewHelper->setSourceData($productList);
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/product/edit',array('pId' => (int)$pId))));
    	$viewModel->setTemplate('admin/product/add');
    	return $viewModel;
    }
    
    public function deleteAction()
    {
    	$pId = $this->params('pId',false);
    	if($pId !== false) {
	    	$return = $this->serviceLocator->get('DbSql')->Product()->edit(array('delete_flg' => 1),array('product_id' => (int)$pId));
	    	$return === true ? $return = 'true' : $return = 'false';
	    	
	    	echo $return;
	    	exit;
    	}
    	
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$productType = $this->serviceLocator->get('DbSql')->Product();
    			$productType->beginTransaction();
    			$delete = array_merge($postData['delete'],array('update_time' => time()));
    			foreach($postData['delete'] as $data) {
    				$return = $productType->edit(array('delete_flg' => 1),array('product_id' => (int)$data));
    				if($return === false) {
    					$productType->rollback();
    					return $this->redirect()->toRoute('admin/product');
    				}
    			}
    			$productType->commit();
    		}
    	}
    	 
    	return $this->redirect()->toRoute('admin/product');
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
    			$productForum = $this->serviceLocator->get('DbSql')->ProductForum();
    			$productForum->beginTransaction();
    			foreach($product as $data) {
    				$forumSelect == 1 ? $return = $productForum->add(array('forum_id' => (int)$formId,'product_id' => (int)$data)) : $return = $productForum->del(array('forum_id' => (int)$formId,'product_id' => (int)$data));
    				if($return === false) {
    					$productForum->rollback();
    					return $this->redirect()->toRoute('admin/product/index',array('pageNum' => $pageNum));
    				}
    			}
    			$productForum->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin/product/index',array('pageNum' => $pageNum));
    }
}
