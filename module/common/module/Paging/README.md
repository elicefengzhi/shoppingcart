#控制器中调用(例)
$paging = $this->serviceLocator->get('Paging');
$paging->paginate(100,5,1,2);
$offset = $paging->getOffset();
$rowsperpage = $paging->getRowsPerPage();
#对应试图页调用
<?php if($this->paging->isFirstPage) : ?>
<a href="#">第一页</a>
<?php endif;?>
<?php foreach($this->paging->numList as $num) : ?>
<?php if($this->paging->isNow($num)) : ?>
<?php echo $num;?>
<?php else :?>
<?php echo '<a href="#">'.$num.'</a>';?>
<?php endif; ?>
<?php endforeach;?>
<?php if($this->paging->isLastPage) : ?>
<a href="#">最后一页</a>
<?php endif;?>
总页数<?php echo $this->paging->getPages(); ?>
#显示效果
第一页12345最后一页 总页数20
