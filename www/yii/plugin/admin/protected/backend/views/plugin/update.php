<?php

$this->menu=array(
	array('label'=>'Create Plugin','url'=>array('create')),
	array('label'=>'Manage Plugin','url'=>array('admin')),
);
?>

<h1>Update Plugin <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
