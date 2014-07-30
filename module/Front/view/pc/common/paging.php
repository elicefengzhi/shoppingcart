<div style="margin-top:10px;" id="paging-div">
	<?php if($paging) : ?>
	<?php if($paging->isFirstPage) : ?>
	<a class="product-paging" data-src="<?php echo $this->url($routerName,array('pageNum' => 1)); ?>" href="javascript:;">最初のページ</a>
	<?php endif;?>
	<?php foreach($paging->numList as $num) : ?>
	<?php if($paging->isNow($num)) : ?>
	<?php echo $num;?>
	<?php else :?>
	<a class="product-paging" data-src="<?php echo $this->url($routerName,array('pageNum' => $num)); ?>" href="javascript:;"><?php echo $num; ?></a>
	<?php endif; ?>
	<?php endforeach;?>
	<?php if($paging->isLastPage) : ?>
	<a class="product-paging" data-src="<?php echo $this->url($routerName,array('pageNum' => $paging->getPages())); ?>" href="javascript:;">最後のページ</a>
	<?php endif;?>
	<span>総ページ数</span>&nbsp;<?php echo $paging->getPages(); ?>
	<?php endif; ?>
</div>