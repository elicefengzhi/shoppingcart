<?php

namespace Product\Logic;

use Application\Logic\BaseLogic;

class ProductLogic extends BaseLogic
{
	public function getProductList($pageNum)
	{
	    $count = $this->serviceManager->get('DbSql')->dispatch('Product')->getProductAllCount();
    	$paging = $this->serviceManager->get('Paging');
    	$paging->paginate($count,1,$pageNum,2);
    	$productList = $this->serviceManager->get('DbSql')->dispatch('Product')->getProductAll($paging->getOffset(),$paging->getRowsPerPage());
    	foreach($productList as $key => $product) {
    		$typeName = $this->serviceManager->get('DbSql')->dispatch('ProductType')->getProductTypeByProductId((int)$product['product_id'],array('type_name' => 'name'),array());
    		$productList[$key]['type_name'] = $typeName;
    		$image = $this->serviceManager->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_path'),array('product_id' => (int)$product['product_id']),true,1);
    		$image !== false && $productList[$key]['image'] = $image['image_path'];
    	}
    	
    	return array('productList' => $productList,'paging' => $paging);
	}
}