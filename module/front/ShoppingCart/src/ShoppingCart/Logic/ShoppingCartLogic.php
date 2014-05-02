<?php

namespace ShoppingCart\Logic;

use Application\Logic\BaseLogic;

class ShoppingCartLogic extends BaseLogic
{
	public function checkProductSize($size)
	{
		if($size != 's' || $size != 'l' || $size != 'm') {
			return 's';
		}
		return $size;
	}
}