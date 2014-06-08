<?php

namespace Validate\Validate;

use Zend\EventManager\EventManager;

class BaseValidator
{	
	protected $filter;//过滤
	protected $errorMessage;//报错信息
	protected $isDataError;//验证中的异常错误
	protected $isTry = true;//验证主方法出现的其它错误
	protected $uploadImage = array();//需要上传的图片
	protected $imageUploadModule;//图片上传模块
	protected $adapter;//数据库适配器
	protected $events;
	protected $chlidClass;//子类对象
	public $sourceData = array();//原数据
	public $data = array();//验证后返回的数据
	public $uploadPath = array();//图片上传路径
	
	public function init($imageUploadModule,$adapter,$chlidClass)
	{
		$this->filter = array('stringTrim','stripTags','htmlEntities','stripNewLines');
		$this->errorMessage = array();
		$this->isDataError = false;
		$this->events = new EventManager();
		$this->imageUploadModule = $imageUploadModule;
		$this->adapter = $adapter;
		$this->chlidClass = $chlidClass;
	}
	
	public function ErrorMessage()
	{
		return $this->errorMessage;
	}
	
	public function IsDataError()
	{
		return $this->isDataError;
	}
	
	protected function setLog($message,$level,$fileName,$line)
	{
		return $this->events->trigger('setLog', null, array('model' => 'validator','message' => $message,'level' => $level,'fileName' => $fileName,'line' => $line));
		throw new \Exception($message);
	}
	
	protected function GalidatorString($string) {
		$array = array(
			'%','\'',',',';','<','>','＼','゜','∥','￠','￡','￢','Α','Β','Γ','Δ','Ε','Ζ','Η','Θ',
			'Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω','γ','δ','ε','ζ','η',
			'θ','ι','κ','λ','μ','ν','ξ','ο','π','ρ','σ','τ','υ','φ','χ','ψ','ω','А','Б','В','Г',
			'Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч',
			'Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л',
			'м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','─',
			'│','┌','┐','┘','└','├','┬','┤','┴','┼','━','┃','┏','┓','┛','┗','┣','┳','┫','┻','╋',
			'┠','┯','┨','┷','┿','┝','┰','┥','┸','╂','㍉','㌔','㌢','㍍','㌘','㌧','㌃','㌶','㍑','㍗',
			'㌍','㌦','㌣','㌫','㍊','㌻','㎜','㎝','㎞','㎎','㎏','㏄','㍻','〝','〟','㏍','℡','㊤','㊥',
			'㊦','㊧','㊨','㍾','㍽','㍼','≒','≡','∫','∮','∑','√','⊥','∠','∟','⊿','∵','∩','∪'
		);
		
		$len = mb_strlen($string,'utf-8');
		if($len <= 0) return true; 
		for($i = 0; $i < $len; $i++)
		{
			$tempString = mb_substr($string,$i,1,'utf-8');
			if(in_array($tempString,$array)) return false;
		}
		
		return true;
	}
	
	/**
	 * ASCII範囲検証
	 * @param $string string
	 * @param $asciiLimits array
	 * array('min' => ,'max' => )
	 */
	protected function Ascii($string,$asciiLimits)
	{
		$len = mb_strlen($string);
		if($len <= 0 || !is_array($asciiLimits) || count($asciiLimits) <= 0) return true;

		$if = 'if(';
		foreach($asciiLimits as $ascii) {
			if(!isset($ascii['min']) ||  !isset($ascii['max'])) return false;
			for($i = 0; $i < $len; $i++)
			{
				$tempString = mb_substr($string,$i,1,'utf-8');
				$if .= 'bin2hex(\''.$tempString.'\') <= '.$ascii['min'].' || bin2hex(\''.$tempString.'\') >= '.$ascii['max'].' ||';
			}
		}
		$if = rtrim($if,'||').') return true;';
		eval($if);
		
		return false;
	}
	
	protected function frilter($data,$frilter = '')
	{
		if($frilter !== false) {
			$frilter == '' && $frilter = $this->filter;
			
			if(is_array($frilter)) {
				if(in_array('stringTrim',$frilter)) {
					$StringTrim = new \Zend\Filter\StringTrim();
					$data = $StringTrim->filter($data);
				}
			}

			if(is_array($frilter)) {
				if(in_array('stripTags',$frilter)) {
					$StripTags = new \Zend\Filter\StripTags();
					$data = $StripTags->filter($data);
				}
			}
			
			if(is_array($frilter)) {
				if(in_array('htmlEntities',$frilter)) {
					$htmlEntities = new \Zend\Filter\HtmlEntities(array('quotestyle'=>ENT_QUOTES));
					$data = $htmlEntities->filter($data);
				}
			}
			
			if(is_array($frilter)) {
				if(in_array('stripNewLines',$frilter)) {
					$stripNewLines = new \Zend\Filter\StripNewlines();
					$data = $stripNewLines->filter($data);
				}
			}
		}

		return $data;
	}
	
	/**
	 * 不为空验证
	 * @param string $data 原数据
	 * @param string $frilter 过滤
	 * @param array $optionArray 附加参数
	 *  <br/>
	 *  boolean: Returns FALSE when the boolean value is FALSE.<br/>
	    integer: Returns FALSE when an integer 0 value is given. Per default this validation is not activated and returns TRUE on any integer values.<br/>
	    float: Returns FALSE when an float 0.0 value is given. Per default this validation is not activated and returns TRUE on any float values.<br/>
	    string: Returns FALSE when an empty string ‘’ is given.<br/>
	    zero: Returns FALSE when the single character zero (‘0’) is given.<br/>
	    empty_array: Returns FALSE when an empty array is given.<br/>
	    null: Returns FALSE when an NULL value is given.<br/>
	    php: Returns FALSE on the same reasons where PHP method empty() would return TRUE.<br/>
	    space: Returns FALSE when an string is given which contains only whitespaces.<br/>
	    object: Returns TRUE. FALSE will be returned when object is not allowed but an object is given.<br/>
	    object_string: Returns FALSE when an object is given and it’s __toString() method returns an empty string.<br/>
	    object_count: Returns FALSE when an object is given, it has an Countable interface and it’s count is 0.<br/>
	    all: Returns FALSE on all above types.<br/>
	    
	 * @return string|boolean
	 */
	protected function NotEmety($data,$frilter = '',Array $optionArray = null)
	{
		$option = array(\Zend\Validator\NotEmpty::STRING,\Zend\Validator\NotEmpty::SPACE,\Zend\Validator\NotEmpty::BOOLEAN);
		if(!is_null($optionArray)) {
			foreach($optionArray as $options) {
				$options = strtoupper($options);
				array_push($option,\Zend\Validator\NotEmpty::$options);
			}
		}

		$validator = new \Zend\Validator\NotEmpty($option);
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $data;
		} 
		return false;
	}
	
	protected function StringLength($data,$length,$frilter = '')
	{
		$validator = new \Zend\Validator\StringLength($length);
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $data;
		}
		return false;	
	}
	
	/**
	 * 整数验证
	 * @param string $data 原数据
	 * @param string $frilter 过滤
	 * @param array $optionArray 附加参数
	 * <br/>
	 * isEmpty是否允许为空(默认false)<br/>
	 * isZeroOk是否允许为零(默认false)<br/>
	 * isSigneOk是否允许为负数(默认false)<br/>
	 * min最小值(不包含极值)<br/>
	 * max最大值(不包含极值)
	 * @return string|boolean
	 */
	protected function Int($data,$frilter = '',Array $optionArray = null)
	{
		$validator = new \Zend\Validator\Digits();
		$data = $this->frilter($data,$frilter);
		$isAllowEmpty = $isAllowZero = $isAllowSigne = false;
		isset($optionArray['isAllowEmpty']) && $isAllowEmpty = $optionArray['isAllowEmpty'];
		isset($optionArray['isAllowZero']) && $isAllowZero = $optionArray['isAllowZero'];
		isset($optionArray['isAllowSigne']) && $isAllowSigne = $optionArray['isAllowSigne'];
		if($isAllowEmpty === true && (string)$data == '') return '';
		if ($validator->isValid($data)) {
			if($isAllowZero === false) {
				if($data == 0) {
					return false;
				}
			}
			
			if($isAllowSigne == false) {
				if($data < 0) {
					return false;
				}
			}
			
			if(is_array($optionArray)) {
				if(isset($optionArray['min']) && isset($optionArray['max'])) {
					$betweenValidator = $this->Between($data,$optionArray['min'],$optionArray['max'],false);
					if($betweenValidator === false) {
						return false;
					}
				}
				else if(isset($optionArray['min']) && !isset($optionArray['max'])) {
					$greaterValidator = $this->GreaterThan($data,$optionArray['min'],false);
					if($greaterValidator === false) {
						return false;
					}
				}
				else if(!isset($optionArray['min']) && isset($optionArray['max'])) {
					$lessValidator = $this->LessThan($data,$optionArray['max'],false);
					if($lessValidator === false) {
						return false;
					}
				}
			}
			return $data;
		}
		else {
			return false;
		}	
	}
	
	protected function Between($data,$min,$max,$frilter = '')
	{
		$validator = new \Zend\Validator\Between(array('min' => $min,'max' => $max));
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $this->GetAlabNum($data);
		}
		return false;		
	}
	
	/**
	 * 最小値验证
	 * @param string $data
	 * @param int $min
	 * @param array $frilter
	 * @param bool $inclusive 是否包含极值
	 * @return bool
	 */
	protected function GreaterThan($data,$min,$frilter = '',$inclusive = true)
	{
		$validator = new \Zend\Validator\GreaterThan(array('min' => $min,'inclusive' => $inclusive));
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $this->GetAlabNum($data);
		}
		return false;
	}
	
	/**
	 * 最大値验证
	 * @param string $data
	 * @param int $max
	 * @param array $frilter
	 * @param bool $inclusive 是否包含极值
	 * @return bool
	 */
	protected function LessThan($data,$max,$frilter = '',$inclusive = true)
	{
		$validator = new \Zend\Validator\LessThan(array('max' => $max,'inclusive' => $inclusive));
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $this->GetAlabNum($data);
		}
		return false;
	}
	
	/**
	 * 是否是时间字符串
	 * @param string $data 原数据
	 * @param string $format 格式化 默认：Y/m/d
	 * @param string $frilter 过滤
	 * @return string|boolean
	 */
	protected function Date($data,$format = 'Y/m/d',$frilter = '')
	{
		$validator = new \Zend\Validator\Date(array('format'=>$format));
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $data;
		}
		return false;		
	}
	
	/**
	 * 数字字母验证
	 * @param string $data 原数据
	 * @param array $frilter 过滤
	 * @param array $option 附加参数<br/>
	 * isAllowEmpty允许为空<br/>
	 * locale本地化
	 * @return $data|bool
	 */
	protected function Alnum($data,$frilter = '',$option = '')
	{
		isset($option['isAllowEmpty']) ? $isAllowEmpty = $option['isAllowEmpty'] : $isAllowEmpty = false;
		isset($option['locale']) ? $locale = $option['locale'] : $locale = false;
		$data = $this->frilter($data,$frilter);
		
		//是否能加载intl
		if(extension_loaded('intl')) {
			$optionArray = array();
			$isAllowEmpty === true && $optionArray['allowWhiteSpace'] = true;
			$locale !== false && $optionArray['locale'] = $locale;
			count($optionArray) > 0 ? $validator = new \Zend\I18n\Validator\Alnum($optionArray) : $validator = new \Zend\I18n\Validator\Alnum();
			if($validator->isValid($data)) {
				return $data;
			}
			return false;
		}
		
		$isAllowEmpty === true ? $matchString = '/[0-9a-zA-Z]*/' : $matchString = '/[0-9a-zA-Z]+/';
		if(preg_match($matchString, $data)) {
			return $data;
		}
		return false;
	}
	
	/**
	 * 字母验证
	 * @param string $data 原数据
	 * @param array $frilter 过滤
	 * @param array $option 附加参数<br/>
	 * isAllowEmpty允许为空<br/>
	 * locale本地化
	 * @return $data|bool
	 */
	protected function Alpha($data,$frilter = '',Array $option = null)
	{
		isset($option['isAllowEmpty']) ? $isAllowEmpty = $option['isAllowEmpty'] : $isAllowEmpty = false;
		isset($option['locale']) ? $locale = $option['locale'] : $locale = false;
		$data = $this->frilter($data,$frilter);
		
		//是否能加载intl
		if(extension_loaded('intl')) {
			$optionArray = array();
			$isAllowEmpty === true && $optionArray['allowWhiteSpace'] = true;
			$locale !== false && $optionArray['locale'] = $locale;
			count($optionArray) > 0 ? $validator = new \Zend\I18n\Validator\Alpha($optionArray) : $validator = new \Zend\I18n\Validator\Alpha();
			if($validator->isValid($data)) {
				return $data;
			}
			return false;
		}
		
		$isAllowEmpty === true ? $matchString = '/[a-zA-Z]*/' : $matchString = '/[a-zA-Z]+/';
		if(preg_match($matchString, $data)) {
			return $data;
		}
		return false;
	}
	
	/**
	 * 数字验证
	 * @param string $data 原数据
	 * @param array $frilter 过滤
	 * @return $data|bool
	 */
	protected function Digits($data,$frilter = '')
	{
		$validator = new \Zend\Validator\Digits();
		$data = $this->frilter($data,$frilter);
		if($validator->isValid($data)) {
			return $data;
		}
		return false;
	}
	
	/**
	 * 浮点数验证
	 * @param string $data 原数据
	 * @param array $frilter 过滤
	 * @param array $option 附加参数<br/>
	 * locale本地化
	 * @return $data|bool
	 */
	protected function Float($data,$frilter = '',$option = '')
	{
		isset($option['locale']) ? $locale = $option['locale'] : $locale = false;
		$data = $this->frilter($data,$frilter);
		
		//是否能加载intl
		if(extension_loaded('intl')) {
			$optionArray = array();
			$locale !== false && $optionArray['locale'] = $locale;
			count($optionArray) > 0 ? $validator = new \Zend\I18n\Validator\Float($optionArray) : $validator = new \Zend\I18n\Validator\Float();
			if($validator->isValid($data)) {
				return $data;
			}
			return false;
		}
		
		if(is_float($data)) {
			return $data;
		}
		return false;
	}
	
	/**
	 * 布尔值验证
	 * @param string $data 原数据
	 * @param array $frilter 过滤
	 * @return $data|bool
	 */
	protected function Bool($data,$frilter = '')
	{
		$returnData = $this->frilter($data,$frilter);
		if ($returnData == 1 || $returnData == 0) {
			return $returnData;
		}
		return false;
	}
	
	/**
	 * 上传文件判断
	 * @param array $file
	 * @param string|boolean $data<br/>
	 * false时判断$file,有值判断$file[$data]
	 * @return boolean|string
	 */
	protected function Upload($file,$data = false)
	{
		if(!isset($file[$data])) {
			throw new \Exception("upload file data error");
			return false;
		} 
		$validator = new \Zend\Validator\File\UploadFile();
		if ($validator->isValid($file[$data])) {
			return $data;
		}
		return false;
	}
	
	protected function Regex($data,$regString,$frilter = '',$isTrue = false)
	{
		$validator = new \Zend\Validator\Regex(array('pattern' => $regString));
		$data = $this->frilter($data,$frilter);
		$isTrue === false ? $return = !$validator->isValid($data) : $return = $validator->isValid($data);
		if ($return) {
			return $data;
		} 
		return false;
	}
	
	/**
	 * 関連ブランク検証
	 * @param array $nameData
	 * @param string $frilter
	 * @param array $option
	 * @return bool
	 */
	protected function RelevanceEmpty($nameData,$frilter='',$option='')
	{
		$isEmpty = true;
		$sourceData = $this->sourceData;
		isset($option['isZero']) ? $isZero = $option['isZero'] : $isZero = 0;
		isset($option['mainData']) ? $mainData = $option['mainData'] : $mainData = false;

		if(is_array($nameData) && is_array($sourceData)) { 
			isset($sourceData[$mainData]) ? $sourceMainData = trim($sourceData[$mainData]) : $sourceMainData = '';
			if($mainData !== false && (string)$sourceMainData != '') {
				$isEmpty = false;
			}
			else {
				$where = false;
				foreach($nameData as $n) {
					if(isset($sourceData[$n])) {
						$sourceData[$n] = trim($sourceData[$n]);
						$isZero == 1 && $sourceData[$n] != 0 && $where = true;
						if((string)$sourceData[$n] != '' || $where) {
							$isEmpty = false;
							break;
						}
					}
				}
			}

			$return = true;
			if($isEmpty == false) {
				$where = false;
				foreach($nameData as $n) {
					isset($sourceData[$n]) ? $sourceData[$n] = trim($sourceData[$n]) : $sourceData[$n] = '';
					$isZero == 1 && (string)$sourceData[$n] != '' && $sourceData[$n] == 0 && $where = true;
					if(isset($sourceData[$n]) === false) {
						$return = false;
						break; 
					}
					
					if((string)$sourceData[$n] == '' || $where) {
						$return = false;
						break;
					}
				}
			}
			
			return $return;
		}
			
		return true;
		
	}
	
	/**
	 * 関連データーの一貫性検証
	 * @param array $relevanceData
	 * @return bool
	 * 条件全部クリアの場合は「ture」
	 */
	protected function RelevanceData($relevanceData){
		$sourceData = $this->sourceData;
		$isRelevance = false;
		$return = true;
		
		foreach($relevanceData as $key => $d) {
			if(isset($sourceData[$key])) {
				$nowData = trim($sourceData[$key]);
				(string)$nowData == '0' && $nowData = (string)$nowData;
				
				if(is_bool($d)) {
					$nowData === $d && $isRelevance = true;
				}
				else {
					$nowData == $d && $isRelevance = true;
				}	 
				if($isRelevance === true) break;
			}
		}
		
		if($isRelevance === true) {
			foreach($relevanceData as $key => $d) {
				if(isset($sourceData[$key])) {
					$nowData = trim($sourceData[$key]);
					(string)$nowData == '0' && $nowData = (string)$nowData;
					
					if(is_bool($d)) {
						$nowData !== $d && $return = false;
					}
					else {
						$nowData != $d && $return = false;
					}
					
					if($return === false) break;
				}
			}		
		}
		
		return $return;
	}
	
	/**
	 * データー初期化
	 * @param string $name
	 * @param bool $Emety
	 * @return bool|string
	 * @example
	 * $isSet等于true时，指定参数不存在则返回'error'。<br/>
	 * $isSet等于false时，指定参数不存在不返回'error'。<br/>
	 * $isEmety等于false为可以是空值，但是必须存在。否则返回'error';<br/>
	 * $isEmety等于'notAll'的情况，只要其中一个非未定义和非空就返回true;
	 */
	protected function dataInit($name,$isEmety = false,$isSet = true){
		$data = $this->sourceData;
		$return = true;
		foreach($name as $n) {
			if($isSet === false && !isset($data[$n])) {
				return false;
			}
			if($isEmety === true) {
				if(!isset($data[$n])) {
					$return = 'error';
					$this->isDataError = true;
					$this->setLog('param is warn from '.$n.' to init','WARN',__FILE__,__LINE__);
				}
				break;
			}
			else {
				if(!isset($data[$n])) {
					$return = false;
					if($isEmety != 'notAll') break;
				}
				else if((string)trim($data[$n]) == '') {
					$return = false;
					if($isEmety != 'notAll') break;
				}
				else{
					if($isEmety == 'notAll') return true;

				}
			}		
		}
		return $return;
	}
	
	private function ImageUpload()
	{
		$ReturnData = true;
		$uploadImage = $this->uploadImage;
		if(count($uploadImage) > 0) {
			$return = $this->imageUploadModule->upload($uploadImage);
			if($return === false) $ReturnData = false;
			else {
				$this->data = array_merge($this->data,$return);
				$this->uploadPath = array_merge($this->uploadPath,$return);
			}

			$errorMessage = $this->imageUploadModule->errormessage;
			if(count($errorMessage) > 0) {
				$this->errorMessage['画像アップロードチェック'] = $this->imageUploadModule->errormessage;
				$ReturnData = false;
			}
			else if($ReturnData === false){
				$this->errorMessage['画像アップロードチェック'] = '画像アップロード失敗';
				$ReturnData = false;
			}
		}
		return $ReturnData;
	}
	
	/**
	 * email验证
	 * @param string $data 原数据
	 * @param string $frilter 过滤
	 * @return string>|boolean
	 */
	protected function Email($data,$frilter = '')
	{
		$validator = new \Zend\Validator\EmailAddress();
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $data;
		}
		return false;
	}
	
	/**
	 * 数据在数据库内是否已存在
	 * @param string $table
	 * @param array|string $field
	 * @param array|string $data
	 * @return boolean;
	 */
	protected function DbExists($table,$field,$data)
	{
		$validator = new \Zend\Validator\Db\NoRecordExists(
			array(
				'table' => $table,
				'field' => $field,
				'adapter' => $this->adapter,
			)
		);

		return $validator->isValid($data) ? $data :false;
	}
	
	/**
	 * 返回验证结果
	 * @return boolean
	 */
	protected function ReturnData()
	{
		$returnData = true;
		count($this->errorMessage) > 0 ? $vailid = false : $vailid = true;
		
		if($this->isTry === false || $vailid === false || $this->isDataError === true) $returnData = false;
		
		$returnData === true && $returnData = $this->ImageUpload();

		return $returnData;
	}
	
	/**
	 * 初始化返回数据
	 * @param array $initParams
	 */
	private function initParams(Array $initParams)
	{
		if(count($initParams) > 0) {
			foreach($initParams as $key => $params) {
				$this->data[$key] = $params;
			}
		}
	}

	/**
	 * 验证主函数
	 * @param array $sourceData 原数据
	 * @param array $validateMethod 子类验证方法<br/>
	 * 第一个参数：方法名，第二个参数：需要验证字段名
	 * @param boolean|array $initParams 子类初始化数据<br/>
	 * 第一个参数：需要初始化字段名，第二个参数：初始化值
	 * @throws \Exception
	 * @return boolean
	 */
	protected function validate($sourceData,Array $validateMethod,Array $initParams = null)
	{
		$this->sourceData = $sourceData;
		is_null($initParams) === false && $this->initParams($initParams);
	
		try {
			//循环调用子类验证方法
			foreach($validateMethod as $method => $dataString) {
				is_null($dataString) ? $isVal = $this->chlidClass->$method() : $isVal = $this->chlidClass->$method($dataString);
			}
		}
		catch (\Exception $e){
			$this->isTry = false;
			throw new \Exception($e->getMessage());
			$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
		}
	
		return $this->ReturnData();
	}
}
