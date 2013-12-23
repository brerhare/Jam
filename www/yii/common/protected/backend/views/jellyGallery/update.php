<?php

$this->menu=array(
	array('label'=>'Manage Galleries','url'=>array('admin')),
);
?>

<h2>Update Gallery <?php echo $model->title; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
