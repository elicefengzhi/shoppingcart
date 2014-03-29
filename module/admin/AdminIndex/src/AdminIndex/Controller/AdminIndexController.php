<?php

namespace AdminIndex\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdminIndexController extends AbstractActionController
{	
    public function indexAction()
    {
		return array();
    }
    
    public function loginAction()
    {
    	$request = $this->getRequest();
    	if($request->isPost()) {
    		$post = $request->getPost()->toArray();
    		$admin = $this->serviceLocator->get('DbSql')->dispatch('Admin');
    		$current = $admin->getAdminBycolumns(array('id','leve'),array('uname' => $post['uname'],'pwd' => md5($post['pwd']),'delete_flg' => 0));
    		if($current !== false) {
    			$session = $this->serviceLocator->get('Fsession');
    			$session->setSession('adminId',$current['id']);
    			$session->setSession('adminLeve',$current['leve']);
    			$this->redirect()->toRoute('admin-index');
    		}
    	}
    	return array();
    }
}
