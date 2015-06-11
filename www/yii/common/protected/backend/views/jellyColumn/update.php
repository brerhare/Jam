<?php

$this->menu=array(
	array('label'=>'Manage Column Content','url'=>array('admin')),
);
?>

<h1>Update Column Content <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
