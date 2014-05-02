<?php
namespace Validate\Model;

use Zend\EventManager\EventManager;

class BaseValidator
{	
	protected $filter;//濾過
	protected $errorMessage;//エラーの情報
	protected $isDataError;//異常なエラー
	protected $isTry = true;//検証中に他のエラー
	protected $uploadImage = array();//アップロードの画像
	protected $imageUploadModule;
	protected $adapter;
	protected $events;
	public $sourceData = array();//元のデーター
	public $data = array();//返すのデーター
	public $uploadPath = array();//アップロードのパス
	
	function __construct($imageUploadModule,$adapter)
	{
		$this->filter = array('stringTrim','stripTags','htmlEntities','stripNewLines');
		$this->errorMessage = array();
		$this->isDataError = false;
		$this->events = new EventManager();
		$this->imageUploadModule = $imageUploadModule;
		$this->adapter = $adapter;
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
	
	protected function GetAlabNum($fnum){
		$nums = array('０','１','２','３','４','５','６','７','８','９','．','＋','：');
		$fnums = array('0','1',  '2','3',  '4','5',  '6', '7','8',  '9','.', '+',':');
		$fnlen = count($fnums);
		for($i=0;$i<$fnlen;$i++) $fnum = str_replace($nums[$i],$fnums[$i],$fnum);
		$slen = strlen($fnum);
		$oknum = '';
		for($i=0;$i<$slen;$i++){
			if(ord($fnum[$i]) > 0x80) $i++;
			else $oknum .= $fnum[$i];
		}

		return $oknum;
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
		
		$len = mb_strlen($string);
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
	
	protected function NotEmety($data,$frilter = '',$isZero = true)
	{
		if($isZero === false && $data == 0) {
			return false;
		}
		$validator = new \Zend\Validator\NotEmpty();
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
	
	protected function Int($data,$frilter = '',$isZero = false,$isSigne = false,$option = '')
	{
		$validator = new \Zend\Validator\Digits();
		$data = $this->frilter($data,$frilter);
		$isEmpty = false;
		isset($option['isEmpty']) && $isEmpty = $option['isEmpty'];
		if($isEmpty === true && (string)$data == '') return '';
		if ($validator->isValid($data)) {
			if($isZero === false) {
				if($data == 0) {
					return false;
				}
			}
			
			if($isSigne == false) {
				if($data < 0) {
					return false;
				}
			}
			
			if(is_array($option)) {
				if(isset($option['min']) && isset($option['max'])) {
					$betweenValidator = $this->Between($data,$option['min'],$option['max'],false);
					if($betweenValidator === false) {
						return false;
					}
				}
				else if(isset($option['min']) && !isset($option['max'])) {
					$greaterValidator = $this->GreaterThan($data,$option['min'],false);
					if($greaterValidator === false) {
						return false;
					}
				}
				else if(!isset($option['min']) && isset($option['max'])) {
					$lessValidator = $this->LessThan($data,$option['max'],false);
					if($lessValidator === false) {
						return false;
					}
				}
			}
			return $this->GetAlabNum($data);
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
	 * 最小値検証
	 * @param string $data
	 * @param int $min
	 * @param array $frilter
	 * @param bool $inclusive 極値
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
	 * 最大値検証
	 * @param string $data
	 * @param int $max
	 * @param array $frilter
	 * @param bool $inclusive 極値
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
	
	protected function Date($data,$frilter = '')
	{
		$validator = new \Zend\Validator\Date(array('format'=>'Y/m/d'));
		$data = $this->frilter($data,$frilter);
		if ($validator->isValid($data)) {
			return $data;
		}
		return false;		
	}
	
	/**
	 * 数字字母検証
	 * @param string $data
	 * @param array $frilter
	 * @param array $option
	 * @return $data|bool
	 */
	protected function Alnum($data,$frilter = '',$option = '')
	{
		isset($option['isEmpty']) ? $isEmpty = $option['isEmpty'] : $isEmpty = false;
		$data = $this->frilter($data,$frilter);
		$isEmpty === true ? $matchString = '/[0-9a-zA-Z]*/' : $matchString = '/[0-9a-zA-Z]+/';
		if(preg_match($matchString, $data)) {
			return $data;
		}
		return false;
	}
	
	protected function Numeric($data,$frilter = '',$option = '')
	{
		isset($option['isEmpty']) ? $isEmpty = $option['isEmpty'] : $isEmpty = false;
		if($isEmpty === true && (string)trim($data) == '') {
			return true;
		}
		$data = $this->frilter($data,$frilter);
		if (is_numeric($data)) {
			if(isset($option['maxLenght'])) {
				$data = substr($data,0,$option['maxLenght']);
			}
			if(isset($option['min'])) {
				if($data < (int)$option['min']) {
					return false;
				}
			}
			if(isset($option['max'])) {
				if($data > (int)$option['max']) {
					return false;
				}
			}
			return $this->GetAlabNum($data);
		}
		return false;	
	}
	
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
	 * @param string|boolean $data
	 * @return boolean|string
	 * 
	 * $data为false时判断$file,有值判断$file[$data]
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
	
	protected function IntD($int,$decimal,$frilter='',$option='')
	{	
		if(is_array($int) && is_array($decimal)) {
			$isZero = true;
			$isSigne = false;
			$int['value'] = trim($int['value']);
			$decimal['value'] = trim($decimal['value']);
			isset($option['checkName']) ? $checkName = $option['checkName'] : $checkName = '';
			isset($option['checkNameSecond']) ? $checkNameSecond = $option['checkNameSecond'] : $checkNameSecond = '';
			isset($option['isEmpty']) ? $isEmpty = $option['isEmpty'] : $isEmpty = false;
			if(is_array($option)) {
				if(isset($option['isZero'])) {
					$isZero = (bool)$option['isZero'];
				}
				
				if(isset($option['isSigne'])) {
					$isSigne = (bool)$option['isSigne'];
				}
				
				if(isset($option['isRetrunZero'])) {
					if((bool)$option['isRetrunZero'] === true) {
						if($int['value'] == '' && (string)$decimal['value'] == '') {
							return 0;
						}
					}
					else {
						if(is_numeric($int['value']) && $int['value'] == 0) {
							if((string)$decimal['value'] == '' || (is_numeric($decimal['value']) && $decimal['value'] == 0)) {
								$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = $int['string'].'は0を入力しないてください' : $this->errorMessage[$checkName][] = $int['string'].'は0を入力しないてください';
								return false;
							}
						}				
					}
				}
			}

			if($isEmpty === false && (string)$int['value'] == '' && (string)$decimal['value'] == '') {
				$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = $int['string'].'が入力されていません' : $this->errorMessage[$checkName][] = $int['string'].'が入力されていません';
				return false;
			}

			$return = $this->Int($int['value'],$frilter,$isZero,$isSigne);
			if($return === false) {		
				$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = '正しい'.$int['string'].'を入力してください' : $this->errorMessage[$checkName][] = '正しい'.$int['string'].'を入力してください';
				return false;
			}	
			$return = $this->StringLength($int['value'], array('min' => $int['min'],'max' => $int['max']));
			if($return === false) {
				if($int['min'] != $int['max']) {
					$temp = '～'.$int['max'].'位の数字';
				}
				$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = $int['string'].'の整数部は'.$int['min'].'桁の数字'.$temp.'以内で入力してください' : $this->errorMessage[$checkName][] = $int['string'].'の整数部は'.$int['min'].'桁の数字'.$temp.'以内で入力してください';
				return false;
			}

			if((string)$decimal['value'] != '') {
				$return = $this->Int($decimal['value'],$frilter,$isZero,$isSigne);
				if($return === false) {
					$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = '正しい'.$decimal['string'].'を入力してください' : $this->errorMessage[$checkName][] = '正しい'.$decimal['string'].'を入力してください';
					return false;
				}
				$return = $this->StringLength($decimal['value'], array('min' => $decimal['min'],'max' => $decimal['max']));
				if($return === false) {
					if($decimal['min'] != $decimal['max']) {
						$temp = '～'.$decimal['max'].'位の数字';
					}
					$checkNameSecond != '' ? $this->errorMessage[$checkName][$checkNameSecond] = $decimal['string'].'の整数部は'.$decimal['min'].'桁の数字'.$temp.'以内で入力してください' : $this->errorMessage[$checkName][] = $decimal['string'].'の整数部は'.$decimal['min'].'桁の数字'.$temp.'以内で入力してください';
					return false;
				}	
			}

			return $this->GetAlabNum((float)($int['value'].'.'.rtrim($decimal['value'],'0')));
		}
		
		return 'arrayError';
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
	
	protected function ReturnData()
	{
		$returnData = true;
		count($this->errorMessage) > 0 ? $vailid = false : $vailid = true;
		
		if($this->isTry === false || $vailid === false || $this->isDataError === true) $returnData = false;
		
		$returnData === true && $returnData = $this->ImageUpload();

		return $returnData;
	}

}
