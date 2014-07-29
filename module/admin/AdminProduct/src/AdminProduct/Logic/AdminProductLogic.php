<?php

namespace AdminProduct\Logic;

use Application\Logic\BaseLogic;

class AdminProductLogic extends BaseLogic
{
	public function setTimeData($isOnlyUpdate = false)
	{
		$events = \Zend\EventManager\StaticEventManager::getInstance();
		$events->attach('*','FormSubmit/DbBefore',function($event) use ($isOnlyUpdate){
			$target = $event->getTarget();
			$sourceData = $target->getValidatedData();
			$time = time();
			$isOnlyUpdate === false && $sourceData['creat_time'] = $time;
			$sourceData['update_time'] = $time;
			$target->setValidatedData($sourceData);
		});		
	}
	
	public function insertProductImageAndAd($productId = false,$type = false)
	{
		$events = \Zend\EventManager\StaticEventManager::getInstance();
		$events->attach('*','FormSubmit/DbAfter',function($event) use($productId,$type){
			$target = $event->getTarget();
			$serviceLocator = $target->getServiceLocator();
			$productId === false && $productId = $target->getLastInsertId();
			$chlidData = $target->getUploadedPath();
			if($chlidData != false) {
				foreach($chlidData as $data) {
					$return = $serviceLocator->get('DbSql')->ProductImage()->add(array('product_id' => $productId,'image_path' => $data));
					if($return === false) {
						$target->setIsRollBack(true);
						return false;
					}
				}	
			}

			$chlidData = $target->getHelperFunction('ChildColumns','getChildColumnsValues','AdProduct');
			if($chlidData != false) {
				$delReturn = true;
				//如果是更新商品广告，先删除原先数据内容
				$type == 'update' && $delReturn = $serviceLocator->get('DbSql')->AdProduct()->del(array('product_id' => $productId));
				if($delReturn === false) {
					$exists = $serviceLocator->get('DbSql')->AdProduct()->getExistsByProductId(array('product_id' => $productId));
					if($exists == true) {
						$target->setIsRollBack(true);
						return false;
					}
				}
				foreach($chlidData as $data) {
					$return = $serviceLocator->get('DbSql')->AdProduct()->add(array('product_id' => $productId,'ad_id' => $data));
					if($return === false) {
						$target->setIsRollBack(true);
						return false;
					}
				}
			}

			$chlidData = $target->getHelperFunction('ChildColumns','getChildColumnsValues','TypeProduct');
			if($chlidData != false) {
				$delReturn = true;
				$type == 'update' && $delReturn = $serviceLocator->get('DbSql')->ProductProductType()->del(array('product_id' => $productId));
				if($delReturn === false) {
					$exists = $serviceLocator->get('DbSql')->ProductProductType()->getExistsByProductId(array('product_id' => $productId));
					if($exists == true) {
						$target->setIsRollBack(true);
						return false;
					}
				}
				$chlidData = array_unique($chlidData);
				foreach($chlidData as $data) {
					$return = $serviceLocator->get('DbSql')->ProductProductType()->add(array('product_id' => $productId,'ptype_id' => $data));
					if($return === false) {
						$target->setIsRollBack(true);
						return false;
					}
				}
			}
		});
	}
}