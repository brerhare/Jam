<?php

$this->menu=array(
	array('label'=>'Manage Ticker','url'=>array('admin')),
);
?>

<h2>Update JellyTicker <?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
