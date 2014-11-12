<?php

namespace Application\Logic\AsseticAdd;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

class JsTemplate implements FilterInterface
{
	private $leftDelimiter = '{{';
	private $rightDelimiter = '}}';
	private $keyWords;
	
	public function setConfig($config)
	{
		isset($config['leftDelimiter']) && trim((string)$config['leftDelimiter']) != '' && $this->leftDelimiter = $config['leftDelimiter'];
		isset($config['rightDelimiter']) && trim((string)$config['rightDelimiter']) != '' && $this->rightDelimiter = $config['rightDelimiter'];
	}
	
	public function setKeyWords($keyWords)
	{
		$this->keyWords = $keyWords;
	}
	
	public function filterLoad(AssetInterface $asset)
	{
	}
	
	public function filterDump(AssetInterface $asset)
	{
		$assetContent = $asset->getContent();
		$keyWords = $this->keyWords;

		if(is_array($keyWords) && count($keyWords) > 0) {
			foreach($keyWords as $key => $word) {
				$pattern = '/'.preg_quote($this->leftDelimiter.$key.$this->rightDelimiter).'+/i';
				$assetContent = preg_replace($pattern,$word,$assetContent);
			}
		}
		
		$asset->setContent($assetContent);
	}
}