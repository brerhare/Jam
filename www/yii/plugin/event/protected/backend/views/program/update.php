<?php

/*
$this->menu=array(
	array('label'=>'List Program','url'=>array('index')),
	array('label'=>'Create Program','url'=>array('create')),
	array('label'=>'View Program','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Program','url'=>array('admin')),
);
*/
?>

<h4>Update Program <?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
