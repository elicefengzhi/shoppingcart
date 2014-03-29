<?php

namespace Paging\Paging;

class Paging{
	private $rowsperpage; //每页几条
	private $totalpages; //总页数
	private $range; //页码左右添加数
	private $offset; //limit 起始数
	private $currentpage = 0; //当前页码
	public $isFirstPage = false;//是否是首页
	public $isLastPage = false;//是否是最后一页
	public $numList = array();//页码数组
	
	/**
	 * 分页函数
	 * @param number $numrows 总行数
	 * @param number $row 一页行数
	 * @param number $nowPage 当前页码
	 * @param number $numlinks 页码左右添加数
	 */
	public function paginate($numrows,$row = 4,$nowPage = 1,$numlinks = 1)
	{
		$this->rowsperpage = $row;
		$this->totalpages = ceil($numrows/$this->rowsperpage);
		$this->currentpage = $nowPage;

		if ($this->currentpage > $this->totalpages){
			$this->currentpage = $this->totalpages;
		}

		if ($this->currentpage < 1){
			$this->currentpage = 1;
		}

		$this->offset = ($this->currentpage -1) * $this->rowsperpage;
		
		$this->counter($numlinks);
	} 
	
	/**
	 * 页码相关参数赋值
	 * @param number $numlinks 页码左右添加数
	 */
	private function counter($numlinks)
	{
		if ($this->currentpage > 1 || ($this->currentpage == $this->totalpages && $this->totalpages > 1)){
			$this->isFirstPage = true;
		}

		$this->range = $numlinks;
	
		$xStart = $this->currentpage - $this->range;
		$xEnd = ($this->currentpage + $this->range) +1;
		$range = $this->range + $this->range;
		if($xStart <= 1) {
			$xStart = 1;
			$xEnd = $range + 2;
		}
		if($xEnd > $this->totalpages) {
			$xStart = $this->totalpages - $range;
			$xEnd = $this->totalpages + 1;
		} 
		for ($x = $xStart; $x < $xEnd; $x++){
			if ($x > 0 && $x <= $this->totalpages){
				$this->numList[] = $x;
			} 
        } 
	    if(($this->currentpage == 1 && $this->totalpages > 1) || $this->currentpage > 1 && $this->currentpage != $this->totalpages){
	    	$this->isLastPage = true;
	    }
	}
	
	/**
	 * 获得每页几条
	 * @return number
	 */
	function getRowsPerPage()
    {
		return $this->rowsperpage;
    } 
	
    /**
     * limit 起始数
     * @return number
     */
	function getOffset()
	{
		return $this->offset;
	} 
	
	/**
	 * 获得总页数
	 * @return number
	 */
	function getPages()
	{
		return $this->totalpages;
	}
	
	/**
	 * 指定页码是否为当前页
	 * @param number $num
	 * @return boolean
	 */
	function isNow($num)
	{
		return $this->currentpage <= $this->totalpages ? $this->currentpage == $num ? true : false : $num == $this->totalpages ? true : false; 
	}
}
?>