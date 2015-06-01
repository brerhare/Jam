<?php

$this->menu=array(
	array('label'=>'Manage Slider Content','url'=>array('admin')),
);
?>

<h2>Update Slider Content <?php //echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
