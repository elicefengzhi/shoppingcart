<?php

namespace Application\Logic\AsseticAdd;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

class UrlEmbed implements FilterInterface
{
	private $baseUrl;//项目相关资产URL
	private $fileReplaceUrl;//文件需要替换的URL
	const SEARCH_PATTERN = "%url\\(['\" ]*((?!data:|//)[^'\"#\?: ]+)['\" ]*\\)%U";
	
	protected function replace($matches)
	{
		$newUrl = str_replace($this->fileReplaceUrl,$this->baseUrl,$matches[1]);
		return "url($newUrl)";
	}
	
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}
	
	public function setFileReplaceUrl($fileReplaceUrl)
	{
		$this->fileReplaceUrl = $fileReplaceUrl;
	}
	
    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
    	$assetContent = $asset->getContent();
    	$newContent = preg_replace_callback(self::SEARCH_PATTERN, array($this, 'replace'), $assetContent);
        $asset->setContent($newContent);
    }
}
