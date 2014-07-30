<?php

$this->menu=array(
	array('label'=>'Manage Categories','url'=>array('admin')),
);
?>

<h2>Update Category <?php echo $model->name; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
