<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;
use Admin\Form\IndexForm;

class IndexController extends BaseController
{	
    public function indexAction()
    {
		return array();
    }
    
    public function loginAction()
    {
    	$form = new IndexForm();
    	$form->setAttribute('action',$this->url()->fromRoute('admin/index/login'));
    	
    	$request = $this->getRequest();
    	if($request->isPost()) {
    		$post = $request->getPost()->toArray();
    		$admin = $this->serviceLocator->get('DbSql')->Admin();
    		$current = $admin->getAdminBycolumns(array('id','leve'),array('uname' => $post['uname'],'pwd' => md5($post['pwd']),'delete_flg' => 0));
    		if($current !== false) {
    			$session = $this->serviceLocator->get('Fsession');
    			$session->setSession('adminId',$current['id']);
    			$session->setSession('adminLeve',$current['leve']);
    			return $this->redirect()->toRoute('admin');
    		}
    	}
    	return array('form' => $form);
    }
    
    public function logoutAction()
    {
    	$session = $this->serviceLocator->get('Fsession')->clear();
    	return $this->redirect()->toRoute('admin/index/login');
    }
}
