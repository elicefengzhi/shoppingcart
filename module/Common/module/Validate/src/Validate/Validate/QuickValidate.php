<?php

namespace Validate\Validate;

use Validate\Validate\BaseValidator;

class QuickValidate extends BaseValidator
{
	protected $quickErrorMessage = array();//快速验证错误信息
	
	/**
	 * 获得验证错误信息
	 * @return array
	 */
	public function getQuickErrorMessage()
	{
		return $this->quickErrorMessage;
	}
	
	/**
	 * 快速验证
	 * @param array $validateParams 验证数组
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
				$isBreak = false;
				foreach(array_keys($validateParams) as $keys) {
					switch ($keys) {
						case 'notEmpty':
							array_key_exists('message',$validateParams['notEmpty']) ? $message = $validateParams['notEmpty']['message'] : $message = false;
							array_key_exists('type',$validateParams['notEmpty']) ? $isVal = $this->NotEmety($value,$frilter,$validateParams['notEmpty']['type']) : $isVal = $this->NotEmety($value,$frilter);
							$isVal === false && $this->quickErrorMessage[$field]['notEmpty'] = $message;
							$isVal === false && $isBreak = true;
							break;
						case 'int':
							$option = array();
							array_key_exists('min',$validateParams['int']) && $option['min'] = $validateParams['int']['min'];
							array_key_exists('max',$validateParams['int']) && $option['max'] = $validateParams['int']['max'];
							array_key_exists('isAllowEmpty',$validateParams['int']) && $option['isAllowEmpty'] = $validateParams['int']['isAllowEmpty'];
							array_key_exists('isAllowZero',$validateParams['int']) && $option['isAllowZero'] = $validateParams['int']['isAllowZero'];
							array_key_exists('isAllowSigne',$validateParams['int']) && $option['isAllowSigne'] = $validateParams['int']['isAllowSigne'];
							array_key_exists('message',$validateParams['int']) ? $message = $validateParams['int']['message'] : $message = false;
							$isVal = $this->Int($value,$frilter,$option);
							$isVal === false && $this->quickErrorMessage[$field]['int'] = $message;
							$isVal === false && $isBreak = true;
							break;
						case 'alpha':
							$option = array();
							array_key_exists('isAllowEmpty',$validateParams['alpha']) && $option['isAllowEmpty'] = $validateParams['alpha']['isAllowEmpty'];
							array_key_exists('locale',$validateParams['alpha']) && $option['locale'] = $validateParams['alpha']['locale'];
							array_key_exists('message',$validateParams['alpha']) ? $message = $validateParams['alpha']['message'] : $message = false;
							$isVal = $this->Alpha($value,$frilter,$option);
							$isVal === false && $this->quickErrorMessage[$field]['alpha'] = $message;
							$isVal === false && $isBreak = true;
							break;
						case 'alnum':
							$option = array();
							array_key_exists('isAllowEmpty',$validateParams['alnum']) && $option['isAllowEmpty'] = $validateParams['alnum']['isAllowEmpty'];
							array_key_exists('locale',$validateParams['alnum']) && $option['locale'] = $validateParams['alnum']['locale'];
							array_key_exists('message',$validateParams['alnum']) ? $message = $validateParams['alnum']['message'] : $message = false;
							$isVal = $this->Alnum($value,$frilter,$option);
							$isVal === false && $this->quickErrorMessage[$field]['alnum'] = $message;
							$isVal === false && $isBreak = true;
							break;
						case 'email':
							$option = array();
							array_key_exists('message',$validateParams['email']) ? $message = $validateParams['email']['message'] : $message = false;
							$isVal = $this->Email($value,$frilter);
							$isVal === false && $this->quickErrorMessage[$field]['email'] = $message;
							$isVal === false && $isBreak = true;
							break;
						case 'stringLength':
							$option = array();
							array_key_exists('min',$validateParams['stringLength']) ? $option['min'] = $validateParams['stringLength']['min'] : $option['min'] = 0;
							array_key_exists('max',$validateParams['stringLength']) && $option['max'] = $validateParams['stringLength']['max'];
							array_key_exists('message',$validateParams['stringLength']) ? $message = $validateParams['stringLength']['message'] : $message = false;
							$isVal = $this->StringLength($value,$option,$frilter);
							$isVal === false && $this->quickErrorMessage[$field]['stringLength'] = $message;
							break;
					}
					
					if($isBreak === true) break;
				}	
			}
				
			return count($this->quickErrorMessage) > 0 ? false : true;
		}
	}
}