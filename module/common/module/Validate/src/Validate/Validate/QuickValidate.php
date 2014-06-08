<?php

namespace Validate\Validate;

use Validate\Validate\BaseValidator;

class QuickValidate extends BaseValidator
{
	protected $quickErrorMessage = array();//快速验证错误信息
	
	/**
	 * 快速验证
	 * @param array $validateParams
	 * @return boolean|array
	 */
	public function quickValidate(Array $validateArray)
	{
		if(count($validateArray) > 0) {
			foreach($validateArray as $field => $validateParams) {
				$value = '';
				if(array_key_exists('data',$validateParams)) {
					$value = $validateParams['data'];
				}
				else {
					return false;
				}
				array_key_exists('frilter',$validateParams) ? $frilter = $validateParams['frilter'] : $frilter = '';
				if(array_key_exists('isEmpty',$validateParams)) {
					array_key_exists('value',$validateParams['isEmpty']) ? $value = $validateParams['isEmpty']['value'] : $value = false;
					array_key_exists('message',$validateParams['isEmpty']) ? $message = $validateParams['isEmpty']['message'] : $message = false;
					array_key_exists('type',$validateParams['isEmpty']) ? $isVal = $this->NotEmety($value,$frilter,$validateParams['isEmpty']['type']) : $isVal = $this->NotEmety($value,$frilter);
					$isVal === false && $this->quickErrorMessage[$field]['isEmpty'] = $message;
				}
				if(array_key_exists('stringLength',$validateParams)) {
					$option = array();
					array_key_exists('min',$validateParams['stringLength']) ? $option['min'] = $validateParams['stringLength']['min'] : $option['min'] = 0;
					array_key_exists('max',$validateParams['stringLength']) && $option['max'] = $validateParams['stringLength']['max'];
					array_key_exists('message',$validateParams['stringLength']) ? $message = $validateParams['stringLength']['message'] : $message = false;
					$isVal = $this->StringLength($value,$option,$frilter);
					$isVal === false && $this->quickErrorMessage[$field]['stringLength'] = $message;
				}
				if(array_key_exists('int',$validateParams)) {
					$option = array();
					array_key_exists('min',$validateParams['int']) && $option['min'] = $validateParams['int']['min'];
					array_key_exists('max',$validateParams['int']) && $option['max'] = $validateParams['int']['max'];
					array_key_exists('isAllowEmpty',$validateParams['int']) && $option['isAllowEmpty'] = $validateParams['int']['isAllowEmpty'];
					array_key_exists('isAllowZero',$validateParams['int']) && $option['isAllowZero'] = $validateParams['int']['isAllowZero'];
					array_key_exists('isAllowSigne',$validateParams['int']) && $option['isAllowSigne'] = $validateParams['int']['isAllowSigne'];
					array_key_exists('message',$validateParams['int']) ? $message = $validateParams['int']['message'] : $message = false;
					$isVal = $this->Int($value,$frilter,$option);
					$isVal === false && $this->quickErrorMessage[$field]['int'] = $message;
				}
				if(array_key_exists('alpha',$validateParams)) {
					$option = array();
					array_key_exists('isAllowEmpty',$validateParams['alpha']) && $option['isAllowEmpty'] = $validateParams['alpha']['isAllowEmpty'];
					array_key_exists('locale',$validateParams['alpha']) && $option['locale'] = $validateParams['alpha']['locale'];
					array_key_exists('message',$validateParams['alpha']) ? $message = $validateParams['alpha']['message'] : $message = false;
					$isVal = $this->Alpha($value,$frilter,$option);
					$isVal === false && $this->quickErrorMessage[$field]['alpha'] = $message;
				}
				if(array_key_exists('alnum',$validateParams)) {
					$option = array();
					array_key_exists('isAllowEmpty',$validateParams['alnum']) && $option['isAllowEmpty'] = $validateParams['alnum']['isAllowEmpty'];
					array_key_exists('locale',$validateParams['alnum']) && $option['locale'] = $validateParams['alnum']['locale'];
					array_key_exists('message',$validateParams['alnum']) ? $message = $validateParams['alnum']['message'] : $message = false;
					$isVal = $this->Alnum($value,$frilter,$option);
					$isVal === false && $this->quickErrorMessage[$field]['alnum'] = $message;
				}
				if(array_key_exists('email',$validateParams)) {
					$option = array();
					array_key_exists('message',$validateParams['email']) ? $message = $validateParams['email']['message'] : $message = false;
					$isVal = $this->Email($value,$frilter);
					$isVal === false && $this->quickErrorMessage[$field]['email'] = $message;
				}	
			}
				
			return count($this->quickErrorMessage) > 0 ? $this->quickErrorMessage : true;
		}
	}
}