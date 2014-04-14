<?php

namespace Application\Model;

class LayoutTitle
{
	private $event;
	
	function __construct($event)
	{
		$this->event = $event;
	}
	
	public function setLayoutTitle()
	{
		$event = $this->event;
		$matches = $event->getRouteMatch();
		$controller = $matches->getParam('controller');
		$title = '';
		switch ($controller) {
			case 'AdminIndex\Controller\AdminIndex':
				$title = '管理ページ';
				break;
			case 'AdminOrder\Controller\AdminOrder':
				$title = '注文管理';
				break;
			case 'AdminPage\Controller\AdminPage':
				$title = 'ページ管理';
				break;
			case 'AdminProduct\Controller\AdminProduct':
				$title = '商品管理';
				break;
			case 'AdminProductImage\Controller\AdminProductImage':
				$title = '商品画像';
				break;
			case 'AdminProductType\Controller\AdminProductType':
				$title = '商品カテゴリ管理';
				break;
			case 'AdminQuery\Controller\AdminQuery':
				$title = '質問管理';
				break;
		}
		
		$viewHelperManager = $event->getApplication()->getServiceManager()->get('viewHelperManager');
		$headTitleHelper = $viewHelperManager->get('headTitle');
		$headTitleHelper->append($title);
	}
}