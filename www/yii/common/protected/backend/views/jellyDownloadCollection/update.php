<?php

$this->menu=array(
	array('label'=>'Manage Download Collections','url'=>array('admin')),
);
?>

<h2>Update Download Collection <?php echo $model->name; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
