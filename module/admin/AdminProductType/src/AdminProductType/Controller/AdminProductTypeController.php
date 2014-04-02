<?php

namespace AdminProductType\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdminProductTypeController extends AbstractActionController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
    	$count = $this->serviceLocator->get('DbSql')->dispatch('ProductType')->getAllCount();
    	$typeList = false;
    	$paging = false;
    	if($count > 0) {
    		$paging = $this->serviceLocator->get('Paging');
    		$paging->paginate($count,10,$pageNum,2);
    		$offset = $paging->getOffset();
    		$rowsperpage = $paging->getRowsPerPage();
    		$typeList = $this->serviceLocator->get('DbSql')->dispatch('ProductType')->getTypeAll($offset,$rowsperpage);
    	}
    	
    	$viewHelper = $this->ViewHelper('Admin');
    	$viewHelper->setSourceData($typeList);
    	return array('viewHelper' => $viewHelper,'paging' => $paging);
    }

    public function addAction()
    {	
    	$errorMessage = '';
    	$user = $this->serviceLocator->get('FormSubmit')->dispatch('Insert');
    	if($user !== false) {
    		$return = $user->insert(false,array('name'),'ProductType','AdminProductType');
    		if($return !== false) return $this->redirect()->toRoute('admin-product-type');
    		$return === false && $user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		$return === false && $user->isExists() === true && $errorMessage = '商品分类名已存在';
    	}
    	
    	$productType = $this->serviceLocator->get('DbSql')->dispatch('ProductType');
    	$typeList = $productType->getType(array('parent_id' => 0));
    	$viewHelper = $this->ViewHelper('Admin');
    	$viewHelper->setSourceData($typeList,'typeParentList');

    	return array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin-product-type/add'));
    }
    
    public function editAction()
    {
    	$typeId = $this->params('typeId',false);
    	if($typeId === false) return $this->redirect()->toRoute('admin-product-type');
    	$errorMessage = '';
    	$typeList = false;
    	
    	$user = $this->serviceLocator->get('FormSubmit')->dispatch('Update');
    	if($user !== false) {
    		$return = $user->update(false,array('ptype_id' => $typeId),array('name'),'ProductType','AdminProductType');
    		if($return !== false) {
    			return $this->redirect()->toRoute('admin-product-type');
    		}
    		$user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		$user->isExists() === true && $errorMessage = '商品分类名已存在';
    		$typeList = $user->getSourceData();
    	}
    	
    	$productType = $this->serviceLocator->get('DbSql')->dispatch('ProductType');
    	$typeParentList = $productType->getType(array('parent_id' => 0));
    	$typeList === false && $typeList = $productType->getType(array('ptype_id' => $typeId),true);
    	
    	$viewHelper = $this->ViewHelper('Admin');
    	$viewHelper->setSourceData($typeList);
    	$viewHelper->setSourceData($typeParentList,'typeParentList');
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin-product-type/edit',array('typeId' => $typeId))));
    	$viewModel->setTemplate('admin-product-type/admin-product-type/add');
    	return $viewModel;
    }
    
    public function deleteAction()
    {
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$productType = $this->serviceLocator->get('DbSql')->dispatch('ProductType');
    			$productType->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $productType->del(array('ptype_id' => (int)$data));
    				if($return === false) {
    					$productType->rollback();
    					return $this->redirect()->toRoute('admin-product-type');
    				}
    			}
    			$productType->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin-product-type');
    }
}
