<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class ProductTypeController extends BaseController
{
    public function indexAction()
    {
    	$pageNum = $this->params('pageNum',1);
        $paginator = $this->serviceLocator->get('DbSql')->ProductType()->getPaginator($pageNum,10);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	return array('viewHelper' => $viewHelper,'paginator' => $paginator);
    }

    public function addAction()
    {	
    	$errorMessage = '';
    	$user = $this->serviceLocator->get('FormSubmit')->Insert();
    	if($user !== false) {
    		$sourceData = $this->params()->fromPost();
    		//$return = $user->requestData()->table('product_type')->existsFields(array('name'))->validate($this->serviceLocator->get('Validate')->AdminProductType())->submit();
    		$return = $user->requestData()->table('product_type')->existsFields(array('name'))->validate($this->serviceLocator->get('Validate')->QuickValidate())
    				       ->validateFunction('quickValidate',
    				       		array(
    				       			'name' => array(
    				       				'data' => $sourceData['name'],
    				       				'notEmpty' => array('message' => '商品カテゴリを入力してください')
    				       			)
    							))
    					  ->validateErrorMessageFunction('getQuickErrorMessage')->submit();
    		if($return !== false) return $this->redirect()->toRoute('admin/product-type');

    		if($return === false && ($user->isVal() === false || $user->isExists() === true)) {
    			$errorMessage = $user->getValidateErrorMessage();
    		}
    	}

    	$productType = $this->serviceLocator->get('DbSql')->ProductType();
    	$typeList = $productType->getType(array('parent_id' => 0));
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($typeList,'typeParentList');
    	$viewHelper->setSourceData($errorMessage,'errorMessage');

    	return array('viewHelper' => $viewHelper,'url' => $this->url()->fromRoute('admin/product-type/add'));
    }
    
    public function editAction()
    {
    	$typeId = $this->params('typeId',false);
    	if($typeId === false) return $this->redirect()->toRoute('admin/product-type');
    	$errorMessage = '';
    	$typeList = false;
    	
    	$user = $this->serviceLocator->get('FormSubmit')->Update();
    	if($user !== false) {
    		$return = $user->update()->table('product_type')->where(array('ptype_id' => $typeId))->existsFields(array('name'))->validate($this->serviceLocator->get('Validate')->AdminProductType())->submit();
    		if($return !== false) {
    			return $this->redirect()->toRoute('admin/product-type');
    		}
    		$user->isVal() === false && $errorMessage = $user->getValidateErrorMessage();
    		$user->isExists() === true && $errorMessage = '商品カテゴリは既に登録されております';
    		$typeList = $user->getSourceData();
    	}
    	
    	$productType = $this->serviceLocator->get('DbSql')->ProductType();
    	$typeParentList = $productType->getType(array('parent_id' => 0));
    	$typeList === false && $typeList = $productType->getType(array('ptype_id' => $typeId),true);
    	
    	$viewHelper = $this->serviceLocator->get('ViewHelper')->Admin();
    	$viewHelper->setSourceData($typeList);
    	$viewHelper->setSourceData($typeParentList,'typeParentList');
    	
    	$viewModel = new \Zend\View\Model\ViewModel(array('viewHelper' => $viewHelper,'errorMessage' => $errorMessage,'url' => $this->url()->fromRoute('admin/product-type/edit',array('typeId' => $typeId))));
    	$viewModel->setTemplate('admin/product-type/add');
    	return $viewModel;
    }
    
    public function deleteAction()
    {
    	$request = $this->request;
    	if($request->isPost()) {
    		$postData = $request->getPost()->toArray();
    		if(isset($postData['delete'])) {
    			$productType = $this->serviceLocator->get('DbSql')->ProductType();
    			$productType->beginTransaction();
    			foreach($postData['delete'] as $data) {
    				$return = $productType->del(array('ptype_id' => (int)$data));
    				if($return === false) {
    					$productType->rollback();
    					return $this->redirect()->toRoute('admin/product-type');
    				}
    			}
    			$productType->commit();
    		}
    	}
    	
    	return $this->redirect()->toRoute('admin/product-type');
    }
}
