<?php if($viewHelper->isLoop($loopName)): ?>
<?php foreach($viewHelper->getSourceData($loopName) as $errorMessage): ?>
<?php if($viewHelper->isLoop(false,$errorMessage)): ?>
<?php foreach($errorMessage as $data): ?>
<p class ="errorClass"><?php echo $data; ?></p>
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>