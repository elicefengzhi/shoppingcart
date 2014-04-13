<div style="margin-top:10px;" id="paging-div">
	<?php if($paging) : ?>
	<?php if($paging->isFirstPage) : ?>
	<a href="<?php echo $this->url($routerName,array('pageNum' => 1)); ?>">第一页</a>
	<?php endif;?>
	<?php foreach($paging->numList as $num) : ?>
	<?php if($paging->isNow($num)) : ?>
	<?php echo $num;?>
	<?php else :?>
	<a href="<?php echo $this->url($routerName,array('pageNum' => $num)); ?>"><?php echo $num; ?></a>
	<?php endif; ?>
	<?php endforeach;?>
	<?php if($paging->isLastPage) : ?>
	<a href="<?php echo $this->url($routerName,array('pageNum' => $paging->getPages())); ?>">最后一页</a>
	<?php endif;?>
	总页数&nbsp;<?php echo $paging->getPages(); ?>
	<?php endif; ?>
</div>